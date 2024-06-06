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
    
            if ($date || $status) {
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
    
                    if ($status && isset($rowData[$statusColIndex])) {
                        if (strtolower($rowData[$statusColIndex]) !== strtolower($status)) {
                            $match = false;
                        }
                    }
    
                    if ($match) {
                        $filteredDataRows[] = $rowData;
                    }
                }
                $dataRows = $filteredDataRows;
            }
    
            return view('dispatch.show', compact('header', 'dataRows', 'sheetNameFilter', 'sheetId', 'status', 'date'));
    
        } catch (\Exception $e) {
            return view('dispatch.show', [
                'message' => 'Failed to fetch data from Google Sheet',
                'header' => [],
                'dataRows' => [],
                'sheetId' => $sheetId,
                'sheetNameFilter' => $sheetNameFilter,
                'status' => '',
                'date' => ''
            ]);
        }
    }

    public function update(Request $request, $sheetId)
{
    try {
        // Fetch the sheet
        $sheet = Sheet::where('sheet_id', $sheetId)->first();

        if (!$sheet) {
            return abort(404);
        }

        // Get the sheet name filter
        $sheetNameFilter = $request->get('sheet_name', 'Sheet1');

        // Fetch data from the specified sheet
        $sheetData = Sheets::spreadsheet($sheetId)->sheet($sheetNameFilter)->all();

        if (empty($sheetData)) {
            return redirect()->back()->withErrors(['message' => 'No data found in the selected sheet']);
        }

        // Skip the first row (which contains column headers)
        $sheetData = array_slice($sheetData, 1);

        // Update the status value to 'awaiting dispatch' in the sheet
        $updateRange = 'J2:J'; // Assuming the status column is 'J'
        $statusValues = [];
        foreach ($sheetData as $rowData) {
            $statusValues[] = ['awaiting dispatch'];
        }
        Sheets::spreadsheet($sheetId)->sheet($sheetNameFilter)->range($updateRange)->update($statusValues);

        // Initialize an array to store generated waybill filenames
        $generatedWaybills = [];

        // Iterate through each row in the sheet data
        foreach ($sheetData as $rowData) {
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
                // Add more data as needed
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

        // Return a response indicating the generated waybill filenames
        $successMessage = 'Waybills generated: ' . implode(', ', $generatedWaybills);
        return response()->json(['message' => $successMessage, 'waybills' => $generatedWaybills]);

    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['message' => 'Failed to generate waybills']);
    }
}

    
    

    



    

    // public function show()
    // {
    //     return view('dispatch.show');
    // }

   
}
