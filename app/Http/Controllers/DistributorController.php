<?php

namespace App\Http\Controllers;

use App\Http\Requests\Distributor\StoreDistributorRequest;
use App\Http\Requests\Distributor\UpdateDistributorRequest;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Str;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\DispatchData;
use Carbon\Carbon;

use Haruncpi\LaravelIdGenerator\IdGenerator;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_BatchUpdateValuesRequest;
use App\Models\Sheet;
use Google\Service\Sheets\Sheet as SheetsSheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\Storage;


class DistributorController extends Controller
{
    public function index()
    {
        $distributors = Distributor::all(); // Get all distributors
        return view('distributors.index', compact('distributors'));
    }

    public function create()
    {
        return view('distributors.create');
    }

    public function update(Request $request, $sheetId)
    {
        $request->validate([
            'header' => 'required|string', // Expecting JSON string
            'dataRows' => 'required|string', // Expecting JSON string
        ]);
    
        try {
            // Fetch the sheet
            $sheet = Sheet::where('sheet_id', $sheetId)->first();
    
            if (!$sheet) {
                return abort(404);
            }
    
            // Get the sheet name filter
            $sheetNameFilter = $request->get('sheet_name', 'Sheet1');
    
            // Decode JSON strings back into arrays
            $header = json_decode($request->get('header'), true);
            $dataRows = json_decode($request->get('dataRows'), true);
    
            // Log the received data for debugging
            Log::info('Header:', ['header' => $header]);
            Log::info('Data Rows:', ['dataRows' => $dataRows]);
    
            if (empty($dataRows)) {
                return redirect()->back()->withErrors(['message' => 'No filtered data found']);
            }
    
            // Update the status value to 'awaiting dispatch' in the filtered data
            $statusColIndex = 9; // Assuming the status column is 'J'
            foreach ($dataRows as &$rowData) {
                $rowData[$statusColIndex] = 'awaiting dispatch';
            }
            unset($rowData);
    
            // Initialize an array to store generated waybill filenames
            $generatedWaybills = [];
    
            // Iterate through each row in the filtered data
            foreach ($dataRows as $rowData) {
                // Create a new waybill HTML content based on the waybill template
                $waybillHtml = view('waybill', [
                    'orderNo' => $rowData[0], // Assuming the order no is in the first column
                    'amount' => $rowData[1], // Assuming the amount is in the second column
                    'quantity' => $rowData[2], // Assuming the quantity is in the third column
                    'item' => $rowData[3], // Assuming the item is in the fourth column
                    'clientName' => $rowData[4], // Assuming the client name is in the fifth column
                    'clientCity' => $rowData[5], // Assuming the client city is in the sixth column
                    'date' => $rowData[6], // Assuming the date is in the seventh column
                    'phone' => $rowData[7], // Assuming the phone is in the eighth column
                ])->render();
    
                // Generate a PDF file for the waybill using the HTML content
                $dompdf = new Dompdf();
                $dompdf->loadHtml($waybillHtml);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
    
                // Save the PDF file to storage
                $filename = 'waybill_' . $rowData[0] . '.pdf'; // Assuming the order no is used for filename
                Storage::put('waybills/' . $filename, $dompdf->output());
    
                // Store the generated waybill filename
                $generatedWaybills[] = $filename;
            }
    
            // Prepare the data for Google Sheets update
            $statusUpdates = [];
            $cellRanges = [];
            foreach ($dataRows as $rowData) {
                $statusUpdates[] = [$rowData[$statusColIndex]]; // Wrap each status in an array
                $cellRanges[] = 'J' . $rowData[10]; // Assuming the column 'code' is used to determine the cell range
            }
    
            // Log the values to be updated
            Log::info('Values for Google Sheets update:', [
                'values' => $statusUpdates,
                'cellRanges' => $cellRanges,
            ]);
    
            // Update the Google Sheet with the new status values
            Sheets::spreadsheet($sheetId)
                ->sheet($sheetNameFilter)
                ->range($cellRanges)
                ->update($statusUpdates);
    
            // Log the Google Sheets API response
            Log::info('Google Sheets API Response:', [
                'response' => Sheets::getLastResponse(),
            ]);
    
            // Return a response indicating the generated waybill filenames
            $successMessage = 'Waybills generated: ' . implode(', ', $generatedWaybills);
            return response()->json(['message' => $successMessage, 'waybills' => $generatedWaybills]);
    
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to generate waybills: ' . $e->getMessage()]);
        }
    }
    
}
