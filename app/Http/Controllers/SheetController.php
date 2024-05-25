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

            // Retrieve submitted date from request
            $date = $request->get('date');

            // Filter data based on date (if provided)
            if ($date) {
                $filteredDataRows = [];
                $dateColIndex = 6 ; // Replace with actual index
                foreach ($dataRows as $rowData) {
                    $cellDate = isset($rowData[$dateColIndex]) ? date('m/d/Y', strtotime($rowData[$dataRows[$index][$dateColIndex]])) : '';
                    if ($cellDate === $date) {
                        $filteredDataRows[] = $rowData;
                    }
                }
                $dataRows = $filteredDataRows;
            }
    
            // Pass the data, header, sheet name, and sheetId to the view
            return view('sheets.sheettables', compact('header', 'dataRows', 'sheetName', 'sheetId')); // Include $sheetId

        } catch (\Exception $e) {
            // Handle potential errors during Google Sheets API interaction
            return view('sheets.sheettables', ['message' => 'Failed to fetch data from Google Sheet', 'header' => [], 'dataRows' => []]);

        }
    }
    
    public function update(Request $request, Sheet $sheet) // Use Sheet model for binding
    {
        $data = $request->input('data');
        $sheetData = array_merge([array_keys($data[0])], $data);
    
        Sheets::spreadsheet($sheet->sheet_id)->sheet('Sheet1')->update($sheetData); // Use sheet_id from model
    
        return view('sheet.sheettables')->with('success', 'Sheet updated successfully.');
    }
    

}
