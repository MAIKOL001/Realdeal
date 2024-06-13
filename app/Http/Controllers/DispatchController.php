<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
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


class DispatchController extends Controller
{
    public function create()
    {
        $distributors = Distributor::all();
        return view('dispatch.create', compact('distributors'));
    }

    public function index()
    {$sheets = Sheet::all();
        
        return view('dispatch.index', compact('sheets'));
    }


    public function show(Request $request, $sheetId)
    {
        try {
            $sheet = Sheet::where('sheet_id', $sheetId)->first(['sheet_id', 'sheet_name']);
    
            if (!$sheet) {
                return abort(404);
            }
    
            // Default to 'Sheet1' if no sheet name is provided in the request
            $sheetNameFilter = $request->get('sheet_name', 'Sheet1');
    
            // Fetch data from the specified sheet
            $sheetData = Sheets::spreadsheet($sheetId)->sheet($sheetNameFilter)->all();
    
            if (empty($sheetData)) {
                return view('dispatch.show', [
                    'message' => 'No data found in the selected sheet',
                    'header' => [],
                    'dataRows' => [],
                    'sheetId' => $sheetId,
                    'sheetNameFilter' => $sheetNameFilter,
                    'status' => '',
                    'date' => ''
                ]);
            }
    
            $header = $sheetData[0];
            $dataRows = array_slice($sheetData, 1);
    
            $date = $request->get('date');
            $status = $request->get('status', '');  // Default to empty string
    
            $filteredDataRows = [];
            $dateColIndex = 6;
            $statusColIndex = 9;
    
            foreach ($dataRows as $rowData) {
                $match = true;
    
                if ($date) {
                    $cellDate = isset($rowData[$dateColIndex]) ? date('Y-m-d', strtotime($rowData[$dateColIndex])) : '';
                    if ($cellDate !== $date) {
                        $match = false;
                    }
                }
    
                $allowedStatuses = ['schedulled', 'reschedulled']; // List of allowed statuses
                if (isset($rowData[$statusColIndex])) {
                    $statusValue = strtolower($rowData[$statusColIndex]);
                    if (!in_array($statusValue, $allowedStatuses)) {
                        $match = false;
                    }
                } else {
                    // Handle rows without a status (optional)
                    // You can decide to exclude them (here) or display them with a default message
                }
    
                if ($match) {
                    $filteredDataRows[] = $rowData;
                }
            }
            $dataRows = $filteredDataRows;
            return view('dispatch.show', compact('header', 'dataRows', 'sheetNameFilter', 'sheetId', 'status', 'date'));

            return view('dispatch.show', compact('header', 'dataRows', 'sheetNameFilter', 'sheetId', 'status', 'date'));
    
        } catch (\Exception $e) {
            return view('dispatch.show', [
                'header' => $header,
                'dataRows' => $filteredDataRows,
                'sheetNameFilter' => $sheetNameFilter,
                'sheetId' => $sheetId,
                'status' => $status,
                'date' => $date
            ]);
        }
    }
 
    
  

    public function update(Request $request, $sheetId)
{
    // Validate the request data
    $request->validate([
        'header' => 'required|string', // Expecting JSON string
        'dataRows' => 'required|string', // Expecting JSON string
    ]);

    try {
        // Fetch the sheet by its ID
        $sheet = Sheet::where('sheet_id', $sheetId)->first();

        if (!$sheet) {
            return abort(404);
        }

        // Decode JSON strings back into arrays
        $header = json_decode($request->get('header'), true);
        $dataRows = json_decode($request->get('dataRows'), true);

        // Log the received data for debugging
        Log::info('Header:', ['header' => $header]);
        Log::info('Data Rows:', ['dataRows' => $dataRows]);

        if (empty($dataRows)) {
            return redirect()->back()->withErrors(['message' => 'No filtered data found']);
        }

        // Get the sheet name filter from the request (default to 'Sheet1' if not provided)
        $sheetNameFilter = $request->get('sheet_name', 'Sheet1');

        // Fetch data from the specified sheet
        $sheetData = Sheets::spreadsheet($sheetId)->sheet($sheetNameFilter)->all();

        // Ensure we have data to work with
        if (empty($sheetData)) {
            return redirect()->back()->withErrors(['message' => 'No data found in the sheet']);
        }

        // Extract the header and data rows from the sheet data
        $header = $sheetData[0];
        $dataRows = array_slice($sheetData, 1);

        // Get date filter from the request
        $date = $request->get('date');

        // Filter the data rows based on the date and status
        $statusColIndex = 9; // Assuming the status column is 'J'
        $dateColIndex = 6; // Assuming the date column is 'G'
        $filteredRows = [];

        foreach ($dataRows as $index => $rowData) {
            $match = true;

            if ($date) {
                $cellDate = isset($rowData[$dateColIndex]) ? date('Y-m-d', strtotime($rowData[$dateColIndex])) : '';
                if ($cellDate !== $date) {
                    $match = false;
                }
            }

            if ($match && isset($rowData[$statusColIndex]) && strtolower($rowData[$statusColIndex]) == 'schedulled') {
                $rowData[$statusColIndex] = 'awaiting dispatch';
                $filteredRows[$index + 2] = $rowData; // Storing row index to update in Google Sheets later
            }
        }

        // Log the filtered data for debugging
        Log::info('Filtered Data Rows:', ['filteredRows' => $filteredRows]);

        // Initialize variables to store aggregated HTML content
        $aggregatedHtml = '';

        // Iterate through each filtered row
        foreach ($filteredRows as $index => $rowData) {
            // Append the waybill HTML content for the current order to the aggregated HTML
            $aggregatedHtml .= view('waybill', [
                'orderNo' => $rowData[0], // Assuming the order no is in the first column
                'amount' => $rowData[1], // Assuming the amount is in the second column
                'quantity' => $rowData[2], // Assuming the quantity is in the third column
                'item' => $rowData[3], // Assuming the item is in the fourth column
                'clientName' => $rowData[4], // Assuming the client name is in the fifth column
                'clientCity' => $rowData[5], // Assuming the client city is in the sixth column
                'date' => $rowData[6], // Assuming the date is in the seventh column
                'phone' => $rowData[7], // Assuming the phone is in the eighth column
            ])->render();
        }

        // Prepare the data for Google Sheets update
        $statusUpdates = [];
        foreach ($filteredRows as $index => $rowData) {
            $statusUpdates[] = [$index, $rowData[$statusColIndex]]; // Row index and status value
        }

        // Update the Google Sheet with the new status values
        foreach ($statusUpdates as $update) {
            Sheets::spreadsheet($sheetId)
                ->sheet($sheetNameFilter)
                ->range('J' . $update[0])
                ->update([[$update[1]]]);
        }

        // Store the orders in the database
        $this->storeOrders(json_encode($filteredRows), $sheetId, $sheetNameFilter);

        // Generate a PDF file for the waybill using the aggregated HTML content
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($aggregatedHtml);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save the PDF to a publicly accessible directory
        $filename = 'waybills_' . time() . '.pdf';
        $pdfPath = storage_path('app/public/waybills/' . $filename);
        file_put_contents($pdfPath, $dompdf->output());

        // Prepare the success message
        $successMessage = 'Status updated & Waybills generated. Download will start automatically.';
        session()->flash('success', $successMessage);
        session()->flash('pdf_download', asset('storage/waybills/' . $filename));

        return redirect()->back();

    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['message' => 'Failed to generate waybills: ' . $e->getMessage()]);
    }
}

    


public function storeOrders($dataRows, $sheetId, $sheetName)
{
    try {
        // Decode the JSON string into an array
        $orders = json_decode($dataRows, true);
        Log::info('Storing orders', ['orders' => $orders]);
        // Iterate through each order and store it in the database
        foreach ($orders as $order) {
            SheetOrder::create([
                'order_no' => $order[0],
                'amount' => $order[1],
                'quantity' => $order[2],
                'item' => $order[3],
                'client_name' => $order[4],
                'client_city' => $order[5],
                'date' => $order[6],
                'phone' => $order[7],
                'agent' => $order[8] ?? null,
                'status' => $order[9] ?? null,
                'code' => $order[10] ?? null,
                'email' => $order[11] ?? null,
                'invoice_code' => $order[12] ?? null,
                'sheet_id' => $sheetId,
                'sheet_name' => $sheetName,
                // Add more fields as needed
            ]);
        }

        // Orders stored successfully
        return true;
        Log::info('Orders stored successfully');
    } catch (\Exception $e) {
        // Log the error and return false
        Log::error('Failed to store orders: ' . $e->getMessage());
        return false;
    }
}


public function dispatch(Request $request)
{
    $distributors = Distributor::all(); // Get all distributors

    // Initialize or retrieve searched orders from session
    $searchedOrders = Session::get('searchedOrders', []);

    if ($request->has('order_no')) {
        $orderNo = $request->input('order_no');
        $order = SheetOrder::where('order_no', $orderNo)->first();
        if ($order) {
            // Append the searched order to the array of searched orders
            $searchedOrders[] = $order;
            // Store the updated array back in the session
            Session::put('searchedOrders', $searchedOrders);
        }
    }

    return view('riderdispatch', compact('distributors', 'searchedOrders'));
}



public function searchOrder(Request $request)
{
    $distributors = Distributor::all(); // Get all distributors

    // Initialize or retrieve searched orders from session
    $searchedOrders = $request->session()->get('searchedOrders', []);

    if ($request->has('order_no')) {
        $orderNo = $request->input('order_no');
        $order = SheetOrder::where('order_no', $orderNo)->first();
        if ($order) {
            // Append the searched order to the array of searched orders
            $searchedOrders[] = $order;
            // Store the updated array back in the session as flash data
            $request->session()->flash('searchedOrders', $searchedOrders);

            // Redirect to a different route to prevent form resubmission
          
        }
    }

    return view('riderdispatch', compact('distributors', 'searchedOrders'));
}


public function clearSearch()
{
    // Clear the session data for searched orders
    Session::forget('searchedOrders');

    // Redirect back to the previous page or a specific route
    return redirect()->back();
}

public function assignRider(Request $request)
{
    // Validate the request
    $request->validate([
        'agent' => 'required|exists:distributors,id', // Assuming your distributor model is named 'Distributor'
        'order_ids' => 'required|array', // Ensure order_ids is an array
    ]);

    // Get the selected agent's name
    $agentName = Distributor::findOrFail($request->input('agent'))->name;

    // Retrieve the order IDs from the form data
    $orderIds = $request->input('order_ids');

    // Update the 'agent' column for each order with the selected agent's name
    foreach ($orderIds as $orderId) {
        $order = SheetOrder::findOrFail($orderId);
        $order->update(['agent' => $agentName, 'status' => 'dispatched']);
    }

    // Redirect back with success message or do something else
    return redirect('riderdispatch')->with('success', 'Rider assigned successfully to selected orders.');
}

public function sheet()
{
    $distributors = Distributor::all();
    $orders = collect(); // Initialize $orders as an empty collection
    $date = null; // Default value for date
    $agent = null; // Default value for agent
    
    return view('ridersheetpdfs', compact('distributors', 'orders', 'date', 'agent'));
}


    

    public function search(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'date' => 'required|date',
            'agent' => 'required|string|exists:distributors,name',
        ]);
    
        // Retrieve and format the input values
        $date = Carbon::parse($validatedData['date'])->format('Y-m-d');
        $agent = $validatedData['agent'];
    
        // Query orders by date and agent name
        $orders = SheetOrder::whereDate('date', $date)
                              ->where('agent', $agent)
                              ->get();
    
        // Check if the request is for PDF
        if ($request->input('generate_pdf')) {
            // Load the existing Blade view
            $pdf = $this->generatePDF($orders, $date, $agent);
            return $pdf->stream('orders.pdf');
        }
    
        // Get the list of distributors again for the dropdown in the view
        $distributors = Distributor::all();
    
        // Pass the orders and distributors to the view
        return view('ridersheetpdfs', compact('orders', 'distributors','date','agent'));
    }
    
    
    public function generatePdf(Request $request)
    {
        // Retrieve the orders data from the request
        $orders = json_decode($request->input('data'));
    
        // Get the date and agent from the request
        $date = $request->input('date');
        $agent = $request->input('agent');
    
        // Create a new Dompdf instance
        $dompdf = new Dompdf();
    
        // Load HTML content from the orderspdf blade view
        $html = view('orderspdf', compact('date', 'agent','orders'))->render();
    
        // Set options for Dompdf (if needed)
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Enable remote file loading if necessary
    
        // Apply options to the Dompdf instance
        $dompdf->setOptions($options);
    
        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);
    
        // Render the PDF
        $dompdf->render();
    
        // Output the generated PDF
        return $dompdf->stream('orders.pdf');
    }
     


    

    
    
    

    



    

    // public function show()
    // {
    //     return view('dispatch.show');
    // }

   
}
