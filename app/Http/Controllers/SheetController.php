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
    
            // Access user email using Laravel authentication
            $userEmail = Auth::user()->email;
            // dd($userEmail); // Optional for debugging
    
            // Filter data based on user email (if provided)
            if ($userEmail) {
                $filteredDataRows = [];
                $emailColIndex = 11; /* Index of the column containing email addresses */;
    
                foreach ($dataRows as $rowData) {
                    // Check if the email column exists and has a value
                    if (isset($rowData[$emailColIndex])) {
                        $email = $rowData[$emailColIndex]; // Get the email value
                        
                        // ... rest of your filtering logic using $email ...
                        if ($email === $userEmail) {
                            $filteredDataRows[] = $rowData; // Add the row to filtered data
                        }
                        
                    } else {
                        // Handle rows with empty email values (optional: log error, skip row, assign placeholder)
                        // You can comment out this block if you don't want to handle empty values
                        // echo "Row skipped: Email column is empty"; // Optional: Log a message
                        continue; // Skip rows with empty email columns
                    }
                }
          //  dd($email);
                // dd($filteredDataRows); // Optional for debugging after email filtering
                $dataRows = $filteredDataRows;
            }
    
            // **Apply additional filtering based on date and status (if provided)**
            $date = $request->get('date');
            $status = $request->get('status');
            $email = $request->get('email');
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
               // dd($filteredDataRows);
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

    

    public function update(Request $request, Sheet $sheet)
{
    $data = $request->input('data');

    // Log the received data and sheet model for debugging
    Log::info('Received data:', ['data' => $data]);

    // Option 1: Log specific sheet model properties
    $sheetData = [
        'id' => $sheet->id,
        'name' => $sheet->name,
        // Add other relevant properties as needed
    ];
    Log::info('Sheet model:', $sheetData);

    // Option 2 (less efficient): Log the entire sheet model (as JSON)
    // Log::info('Sheet model (JSON):', $sheet->toJson()); // Use this if needed

    // Validate the data structure
    if (!is_array($data) || empty($data)) {
        return redirect()->back()->withErrors(['Invalid data format or no data provided.']);
    }

    foreach ($data as $row) {
        if (!is_array($row)) {
            return redirect()->back()->withErrors(['Each row of data must be an array.']);
        }
    }

    // Convert data to the format required by Google Sheets
    $header = array_keys(reset($data));
    $sheetData = array_merge([$header], array_map('array_values', $data));

    try {
        Sheets::spreadsheet($sheet->sheet_id)->sheet('Sheet1')->update($sheetData);
        return redirect()->back()->with('success', 'Sheet updated successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['Error updating sheet: ' . $e->getMessage()]);
    }
}

     
    public function waybills()
    {
        return View ('waybill');
    }

}
