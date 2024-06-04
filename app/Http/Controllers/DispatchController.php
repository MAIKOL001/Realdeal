<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Distributor;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Str;


use App\Models\Sheet;
use Google\Service\Sheets\Sheet as SheetsSheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;


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
            $sheet = Sheet::where('sheet_id', $sheetId)->first();
    
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

    // public function show()
    // {
    //     return view('dispatch.show');
    // }

   
}
