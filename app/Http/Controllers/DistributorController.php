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

    public function store(StoreDistributorRequest $request)
{
    // dd($request->all());
    $validatedData = $request->validated();

    // Handle profile image upload (if applicable)
    if ($request->hasFile('photo')) {
        $image = $request->file('photo')->store("distributors", "public");
        $validatedData['photo'] = $image; // Update the validated data with the image path
    }

    Distributor::create($validatedData);

    return redirect()->route('distributors.index')->with('success', 'Distributor created successfully!');
}

public function edit(Distributor $distributor)
{
    
    return view('distributors.edit', compact('distributor'));
}

// Update functionality (optional)
public function update(UpdateDistributorRequest $request, Distributor $distributor)
{
$validatedData = $request->validated();

// Handle profile image update (if applicable)
if ($request->hasFile('photo')) {
    // Handle existing image deletion (optional)
    if ($distributor->photo) {
        Storage::delete($distributor->photo); // Delete existing image
    }

    $image = $request->file('photo')->store("distributors", "public");
    $validatedData['photo'] = $image; // Update validated data with new image path
}

$distributor->update($validatedData);

return redirect()->route('distributors.index')->with('success', 'Distributor updated successfully!');
}

public function destroy(Distributor $distributor)
{
$distributor->delete();

return redirect()->route('distributors.index')->with('success', 'Distributor deleted successfully!');
}

    
}
