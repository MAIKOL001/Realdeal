<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use Google\Service\Sheets\Sheet as SheetsSheet;
use Illuminate\Http\Request;

use Revolution\Google\Sheets\Facades\Sheets;


class SheetController extends Controller
{
    //
    public function index()
    {
        $sheets = Sheet::all();
        
        // Assuming Sheet is your Eloquent model for the 'sheets' table
        return view('sheets.index', compact('sheets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sheet_id' => 'required|string|unique:sheets,sheet_id',
            'sheet_name' => 'required|string',
        ]);

        $sheet = new Sheet;
        $sheet->sheet_id = $request->sheet_id;
        $sheet->sheet_name = $request->sheet_name;
        $sheet->save();

        return redirect()->back()->with('success', 'Sheet linked successfully.');
    }

    public function show(Request $request, $sheetId)
{
    try {
        $sheet = Sheet::where('sheet_id', $sheetId)->first();

        if (!$sheet) {
            // Handle case where sheet not found (optional: redirect or display error message)
            return abort(404);
        }

        $sheetName = $sheet->sheet_name;
        $sheetData = Sheets::spreadsheet($sheetId)->sheet('Sheet1')->all();

        $header = $sheetData[0]; // Access the first element (header row)
        $dataRows = array_slice($sheetData, 1); // Extract data rows

        // Retrieve submitted date and status from request
        $date = $request->get('date');
        $status = $request->get('status');

        // Filter data based on date (if provided)
        if ($date) {
            $filteredDataRows = [];
            $dateColIndex = 6; 
            $statusColIndex=9; // Assuming "Date" column is at index 6

            // Iterate over data rows and filter based on the provided date
            foreach ($dataRows as $rowData) {
                // Check if the date column exists in the current row
                if (isset($rowData[$dateColIndex])) {
                    // Format the cell date to match the provided date format
                    $cellDate = date('Y-m-d', strtotime($rowData[$dateColIndex]));

                    // Compare the formatted cell date with the provided date
                    if ($cellDate === $date) {
                        // If status is also provided, check and filter by status
                        if (!$status || $rowData[$statusColIndex] === $status) {
                            $filteredDataRows[] = $rowData; // Add the row to filtered data
                        }
                    }
                }
            }
//dd($filteredDataRows);
            // Assign filtered data to dataRows
            $dataRows = $filteredDataRows;
        }

        // Pass the data, header, sheet name, and sheetId to the view
        return view('sheets.sheettables', compact('header', 'dataRows', 'sheetName', 'sheetId'));

    } catch (\Exception $e) {
        // Handle potential errors during Google Sheets API interaction
        return view('sheets.sheettables', [
            'message' => 'Failed to fetch data from Google Sheet',
            'header' => [],
            'dataRows' => [],
            'sheetId' => $sheetId
        ]);
    }
}

    

    public function update(Request $request, Sheet $sheet) // Use Sheet model for binding
    {
        $data = $request->input('data');
        $sheetData = array_merge([array_keys($data[0])], $data);
    
        Sheets::spreadsheet($sheet->sheet_id)->sheet('Sheet1')->update($sheetData); // Use sheet_id from model
    
        return view('sheet.sheettables')->with('success', 'Sheet updated successfully.');
    }
     
    public function waybills()
    {
        return View ('waybill');
    }
    

}
