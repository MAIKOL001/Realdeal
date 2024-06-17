@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <h3 class="mb-1">Success</h3>
                <p>{{ session('success') }}</p>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                @if (session('pdf_download'))
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                var link = document.createElement('a');
                                link.href = "{{ session('pdf_download') }}";
                                link.download = "waybills.pdf";
                                link.click();
                            }, 5000); // Delay for 5000 milliseconds (5 seconds)
                        };
                    </script>
                @endif
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="card-title">Spreadsheet Name: {{ request('sheet_name', 'Sheet1') }}</h3>
                    </div>
                </div>
            </div>
            <!-- Filter Form -->
            <form method="GET" action="{{ route('dispatch.show', $sheetId) }}">
                @csrf
                <div class="row gx-3 mb-3 p-3">
                    <div class="col-md-4">
                        <select class="form-select" id="status" name="status">
                            <option value="">All</option>
                            <option value="schedulled" @if(request('status') == 'schedulled') selected @endif>Schedulled</option>
                            <option value="reschedulled" @if(request('status') == 'reschedulled') selected @endif>Reschedulled</option>
                        </select>
                    </div>
                    <!-- Other fields -->
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="sheet_name" name="sheet_name">
                            <option value="Sheet1" {{ request('sheet_name') == 'Sheet1' ? 'selected' : '' }}>Sheet1</option>
                            <option value="Sheet2" {{ request('sheet_name') == 'Sheet2' ? 'selected' : '' }}>Sheet2</option>
                            <option value="JUNE 14" {{ request('sheet_name') == 'JUNE 14' ? 'selected' : '' }}>JUNE 14</option>
                            <option value="JUNE 15" {{ request('sheet_name') == 'JUNE 15' ? 'selected' : '' }}>JUNE 15</option>
                            <!-- Add more sheets as needed -->
                        </select>
                    </div>
                    <div class="col-md-4" style="padding-top: 0.5rem;">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <!-- Upload Form -->
            <form method="POST" action="{{ route('dispatch.update', $sheetId) }}">
                @csrf
                <input type="hidden" name="status" value="{{ request('status', '') }}">
                <input type="hidden" name="sheet_name" value="{{ request('sheet_name', 'Sheet1') }}">

                <!-- Include header and dataRows as hidden inputs -->
                <input type="hidden" name="header" value="{{ json_encode($header) }}">
                <input type="hidden" name="dataRows" value="{{ json_encode($dataRows) }}">
                <input type="hidden" class="form-control" id="date" name="date" value="{{ $date }}">

                <x-spinner.loading-spinner />
                <button type="submit" class="btn btn-success">Generate Waybills</button>
            </form>
        </div>
    </div>
</div>

<div class="table-responsive" style="padding-top: 1rem;">
    @if (isset($header) && isset($dataRows))
        <table wire:loading.remove class="table table-bordered text-nowrap datatable">
            <thead>
                <tr>
                    @foreach ($header as $heading)
                        <th scope="col" class="align-middle text-center">{{ $heading }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($dataRows as $index => $rowData)
                    <tr>
                        @foreach ($rowData as $colIndex => $cell)
                            <td class="align-middle text-center">
                                <input type="text" name="data[{{ $index }}][{{ $colIndex }}]" value="{{ $cell }}" class="form-control">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No data to display.</p>
    @endif
</div>
@endsection
