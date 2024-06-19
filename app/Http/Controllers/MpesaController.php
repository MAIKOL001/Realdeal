<?php
namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Models\SheetOrder;
use Dompdf\Options;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Session;
use App\Models\Sheet;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\DB;
use Safaricom\Mpesa\Mpesa;
use Illuminate\Support\Str;

class MpesaController extends Controller
{
    //

    public function stk(Request $request)
{
    try {
        // Validate and format phone number
        $phone = $request->phone;
        $phone = preg_replace('/\D/', '', $phone); // Remove non-digit characters

        // Add country code if missing (assuming Kenya)
        if (!Str::startsWith($phone, '254')) {
            $phone = '254' . substr($phone, -9); // Assuming the last 9 digits are the local number
        }

        // Log the phone number received and formatted for STK Push
        Log::info('Phone Number for STK Push: ' . $phone);

        // Initialize M-Pesa SDK
        $mpesa = new \Safaricom\Mpesa\Mpesa();

        // Parameters for STK push simulation
        $BusinessShortCode = '174379';  // Your business shortcode
        $LipaNaMpesaPasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';  // Lipa Na M-Pesa Online Passkey
        $TransactionType = 'CustomerPayBillOnline';  // Transaction type
        $Amount = (float) str_replace(',', '', $request->amount);  // Remove any commas if present
        $PartyA = $phone;  // Customer phone number
        $PartyB = '174379';  // Your business shortcode
        $PhoneNumber = $phone;  // Customer phone number
        $CallBackURL = 'https://realdealsystem.com/mpesa/confirmation';  // Callback URL for transaction status
        $AccountReference = $request->order_no;  // Order number as Account Reference
        $TransactionDesc = 'Payment for Order ' . $request->order_no;  // Transaction description
        $Remarks = 'Payment for Order ID: ' . $request->id;  // Additional remarks

        // Perform STK push simulation
        $stkPushSimulation = $mpesa->STKPushSimulation(
            $BusinessShortCode,
            $LipaNaMpesaPasskey,
            $TransactionType,
            $Amount,
            $PartyA,
            $PartyB,
            $PhoneNumber,
            $CallBackURL,
            $AccountReference,
            $TransactionDesc,
            $Remarks
        );
        Log::info('Amount for STK Push: ' . $Amount);

        // Log the response for debugging or auditing purposes
        Log::info('STK Push response: ' . json_encode($stkPushSimulation));

        // Return success response with STK push simulation data
        return response()->json(['success' => true, 'data' => $stkPushSimulation]);

    } catch (\Exception $e) {
        // Log and return failure response if STK push fails
        Log::error('STK Push failed: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'STK Push failed: ' . $e->getMessage()]);
    }
}

public function handleCallback(Request $request)
{
    // Log the callback for debugging purposes
    Log::info('M-Pesa Callback:', $request->all());

    // Get the callback data
    $callbackData = $request->all();

    // Extract necessary data
    $transactionStatus = $callbackData['Body']['stkCallback']['ResultCode'];
    $transactionMessage = $callbackData['Body']['stkCallback']['ResultDesc'];
    $checkoutRequestID = $callbackData['Body']['stkCallback']['CheckoutRequestID'];
    $merchantRequestID = $callbackData['Body']['stkCallback']['MerchantRequestID'];
    $amount = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
    $mpesaReceiptNumber = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
    $transactionDate = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'];
    $phoneNumber = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];

    if ($transactionStatus == 0) {
        // Successful transaction
        Log::info('Transaction Successful:', [
            'CheckoutRequestID' => $checkoutRequestID,
            'MerchantRequestID' => $merchantRequestID,
            'MpesaReceiptNumber' => $mpesaReceiptNumber,
            'TransactionDate' => $transactionDate,
            'Amount' => $amount,
            'PhoneNumber' => $phoneNumber,
        ]);

        // Update the transaction status in the database or perform any other necessary actions
        $order = SheetOrder::where('phone', $phoneNumber)->where('amount', $amount)->first();
        if ($order) {
            $order->code = $mpesaReceiptNumber;
            $order->status = 'Delivered';
            $order->save();
        }

    } else {
        // Failed transaction
        Log::error('Transaction Failed:', [
            'CheckoutRequestID' => $checkoutRequestID,
            'MerchantRequestID' => $merchantRequestID,
            'ResultCode' => $transactionStatus,
            'ResultDesc' => $transactionMessage,
        ]);

        // Handle the failed transaction appropriately
    }

    // Return a response to acknowledge receipt of the callback
    return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
}

}

