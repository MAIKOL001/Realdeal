<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\DispatchData;
use Carbon\Carbon;
use App\Models\Distributor;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Str;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

use App\Models\Sheet;
use Google\Service\Sheets\Sheet as SheetsSheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\Storage;


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
    
            // Update the status value to 'awaiting dispatch' in the filtered data
            $statusColIndex = 9; // Assuming the status column is 'J'
            $filteredRows = [];
            foreach ($dataRows as $index => $rowData) {
                if (isset($rowData[$statusColIndex]) && strtolower($rowData[$statusColIndex]) == 'schedulled') {
                    $rowData[$statusColIndex] = 'awaiting dispatch';
                    $filteredRows[$index + 2] = $rowData; // Storing row index to update in Google Sheets later
                }
            }
    
            // Initialize an array to store generated waybill filenames
            $generatedWaybills = [];
    
            // Iterate through each filtered row
            foreach ($filteredRows as $index => $rowData) {
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
    
            // Return a response indicating the generated waybill filenames
            $successMessage = 'Waybills generated: ' . implode(', ', $generatedWaybills);
            return redirect()->back()->with('success', $successMessage);
    
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => 'Failed to generate waybills: ' . $e->getMessage()]);
        }
    }
     


    

    
    
    

    



    

    // public function show()
    // {
    //     return view('dispatch.show');
    // }

   
}
