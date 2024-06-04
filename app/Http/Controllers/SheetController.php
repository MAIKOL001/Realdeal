<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use Google\Service\Sheets\Sheet as SheetsSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;

class SheetController extends Controller
{
    public function index()
    {
        $sheets = Sheet::all();
        
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
                return abort(404);
            }
    
            // Default to 'Sheet1' if no sheet name is provided in the request
            $sheetNameFilter = $request->get('sheet_name', 'Sheet1');
    
            // Fetch data from the specified sheet
            $sheetData = Sheets::spreadsheet($sheetId)->sheet($sheetNameFilter)->all();
    
            if (empty($sheetData)) {
                return view('sheets.sheettables', [
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
    
            $userEmail = Auth::user()->email;
    
            if ($userEmail) {
                $filteredDataRows = [];
                $emailColIndex = 11;
    
                foreach ($dataRows as $rowData) {
                    if (isset($rowData[$emailColIndex]) && $rowData[$emailColIndex] === $userEmail) {
                        $filteredDataRows[] = $rowData;
                    }
                }
                $dataRows = $filteredDataRows;
            }
    
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
    
            return view('sheets.sheettables', compact('header', 'dataRows', 'sheetNameFilter', 'sheetId', 'status', 'date'));
    
        } catch (\Exception $e) {
            return view('sheets.sheettables', [
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
        $request->validate([
            'data' => 'required|array',
            'sheet_name' => 'required|string', // Ensure sheet_name is provided
        ]);
    
        $data = $request->input('data');
        $sheetName = $request->input('sheet_name'); // Get the sheet name from the request
        $rows = [];
    
        foreach ($data as $index => $row) {
            // Ensure that each row is an array of values
            $rows[] = array_values($row);
        }
    
        // Debugging: Log the data to be updated
        Log::info('Updating Google Sheet with the following data:', ['sheetId' => $sheetId, 'sheetName' => $sheetName, 'rows' => $rows]);
    
        try {
            $response = Sheets::spreadsheet($sheetId)->sheet($sheetName)->range('A2')->update($rows);
    
            // Debugging: Log the API response
            Log::info('Google Sheets API response:', ['response' => $response]);
    
            return redirect()->back()->with('success', 'Data updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update Google Sheet: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update data.');
        }
    }
    


    public function waybills()
    {
        return view('waybill');
    }
}
