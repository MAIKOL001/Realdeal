<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use App\Models\SheetOrder;
use Google\Service\Sheets\Sheet as SheetsSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;
use Google_Client;
use Google_Service_Sheets;

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
        $rows[] = array_values($row);
    }

    // Debugging: Log the data to be updated
    Log::info('Updating Google Sheet with the following data:', ['sheetId' => $sheetId, 'sheetName' => $sheetName, 'rows' => $rows]);

    try {
        // Update the specified range in the Google Sheet
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

    public function sync()
    {
        $sheetOrders = SheetOrder::all();
        return view('syncsheets', compact('sheetOrders'));
    }

    public function updateSheet(Request $request)
{
    $orderNos = $request->input('order_no');
    $statuses = $request->input('status');
    $agents = $request->input('agent');

    // Initialize Google Sheets API
    $client = new \Google_Client();
    $client->setAuthConfig(storage_path('project-423911-84ac0fbdde59.json'));
    $client->addScope(\Google_Service_Sheets::SPREADSHEETS);
    $service = new \Google_Service_Sheets($client);

    // Verify inputs are arrays
    if (is_array($orderNos) && is_array($statuses) && is_array($agents)) {
        try {
            // Loop through each sheet
            foreach ($orderNos as $index => $orderNo) {
                // Fetch the corresponding SheetOrder record
                $sheetOrder = SheetOrder::where('order_no', $orderNo)->first();
                if (!$sheetOrder) {
                    Log::warning("SheetOrder not found for Order No: $orderNo. Skipping update.");
                    continue;
                }

                $spreadsheetId = $sheetOrder->sheet_id;
                $sheetName = $sheetOrder->sheet_name; // Assuming sheet_name is fetched correctly

                // Log the data being updated
                Log::info("Updating Google Sheet: Order No - $orderNo, Status - $statuses[$index], Agent - $agents[$index], Sheet ID - $spreadsheetId, Sheet Name - $sheetName");

                // Find and update the row in the specific sheet
                $range = "$sheetName!A:Z"; // Adjust range as needed
                $response = $service->spreadsheets_values->get($spreadsheetId, $range);
                $values = $response->getValues();

                if (empty($values)) {
                    Log::warning("No data found in sheet: $sheetName. Skipping update.");
                    continue;
                }

                $updated = false;
                foreach ($values as $i => $row) {
                    if (isset($row[0]) && $row[0] == $orderNo) { // Assuming Order No is in the first column
                        // Update status in column J (index 9) and agent in column I (index 8)
                        $updateRangeStatus = "$sheetName!J" . ($i + 1);
                        $updateRangeAgent = "$sheetName!I" . ($i + 1);

                        // Prepare values for status and agent updates
                        $valuesStatus = [
                            [$statuses[$index]]
                        ];
                        $valuesAgent = [
                            [$agents[$index]]
                        ];

                        // Prepare value ranges for status and agent updates
                        $bodyStatus = new \Google_Service_Sheets_ValueRange([
                            'values' => $valuesStatus
                        ]);
                        $bodyAgent = new \Google_Service_Sheets_ValueRange([
                            'values' => $valuesAgent
                        ]);

                        // Update status
                        $resultStatus = $service->spreadsheets_values->update($spreadsheetId, $updateRangeStatus, $bodyStatus, ['valueInputOption' => 'RAW']);
                        // Update agent
                        $resultAgent = $service->spreadsheets_values->update($spreadsheetId, $updateRangeAgent, $bodyAgent, ['valueInputOption' => 'RAW']);

                        // Log API responses for debugging
                        Log::info("Google Sheets API response for Status update: " . json_encode($resultStatus));
                        Log::info("Google Sheets API response for Agent update: " . json_encode($resultAgent));

                        $updated = true;
                        break; // Break out of the loop once updated
                    }
                }

                if (!$updated) {
                    Log::warning("Row not found for Order No: $orderNo in Sheet Name: $sheetName. Skipping update.");
                }
            }

            return redirect()->back()->with('success', 'Orders updated successfully in the sheets');
        } catch (\Exception $e) {
            Log::error("Failed to update Google Sheet: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update data.');
        }
    } else {
        // Log an error if input arrays are not as expected
        Log::error("Invalid input data format: order_no, status, or agent is not an array.");
        return redirect()->back()->with('error', 'Invalid input data format.');
    }
}

    

    
}
