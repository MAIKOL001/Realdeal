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
        </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="card-title">Spreadsheet Name: {{ $sheetNameFilter }}</h3>
                    </div>
                </div>
            </div>
            <!-- Filter Form -->
            <form method="GET" action="{{ route('sheets.show', $sheetId) }}">
                @csrf
                <div class="row gx-3 mb-3 p-3">
                    <div class="col-md-4">
                        <select class="form-select" id="status" name="status">
                            <option value="">All</option>
                            <option value="schedulled" @if($status == 'schedulled') selected @endif>Schedulled</option>
                            <option value="cancelled" @if($status == 'cancelled') selected @endif>Cancelled</option>
                            <option value="returned" @if($status == 'returned') selected @endif>Returned</option>
                            <option value="reschedulled" @if($status == 'reschedulled') selected @endif>Reschedulled</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="date" class="form-control" id="date" name="date" value="{{ $date }}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="sheet_name" name="sheet_name">
                            <option value="Sheet1" {{ request('sheet_name') == 'Sheet1' ? 'selected' : '' }}>Sheet1</option>
                            <option value="Sheet2" {{ request('sheet_name') == 'Sheet2' ? 'selected' : '' }}>Sheet2</option>
                            <option value="Sheet3" {{ request('sheet_name') == 'Sheet3' ? 'selected' : '' }}>Sheet3</option>
                            <!-- Add more sheets as needed -->
                        </select>
                    </div>
                    <div class="col-md-4" style="padding-top: 0.5rem;">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
            <div class="d-flex">
                <div class="text-secondary">
                </div>
                <div class="ms-auto text-secondary">
                    <form method="POST" action="{{ route('sheets.update', $sheetId) }}">
                        @csrf
                        @method('PUT')
                        <div class="col-md-4">
                        </div>
                </div>
            </div>
            <div class="col-md-4" hidden>
                <label for="sheet_name" class="form-label">Sheet Name:</label>
                <input type="text" class="form-control" id="sheet_name" name="sheet_name" placeholder="Sheet1" required value="{{ $sheetNameFilter }}">
            </div>
            <x-spinner.loading-spinner />
            <button type="submit" class="btn btn-success">Update All</button>
        </div>
    </div>
    <div class="table-responsive" style="padding-top:1rem;">
        @if (isset($header) && isset($dataRows))
        <table wire:loading.remove class="table table-bordered text-nowrap datatable">
            <thead>
                <tr>
                    @foreach ($header as $heading)
                    <th scope="col" class="align-middle text-center">{{ $heading }}</th>
                    @endforeach
                    <th scope="col" class="align-middle text-center">Actions</th>
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
                    <td class="align-middle text-center">
                        <button type="button" class="btn btn-success call-button" data-bs-toggle="modal" data-bs-target="#dialPadModal">Call</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No data to display.</p>
        @endif
    </div>
    </form>
</div>
</div>
</div>

<!-- Dial Pad Modal -->
<div class="modal fade" id="dialPadModal" tabindex="-1" aria-labelledby="dialPadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dialPadModalLabel">Dial Pad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="dialedNumber" class="form-control mb-3 text-center" style="max-width: 320px; margin: 0 auto;" placeholder="Dialed number">
                <div class="dial-pad text-center">
                    <div class="row mb-2">
                        <div class="col"><button class="btn btn-light dial-button" data-value="1">1</button></div>
                        <div class="col"><button class="btn btn-light dial-button" data-value="2">2</button></div>
                        <div class="col"><button class="btn btn-light dial-button" data-value="3">3</button></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col"><button class="btn btn-light dial-button" data-value="4">4</button></div>
                        <div class="col"><button class="btn btn-light dial-button" data-value="5">5</button></div>
                        <div class="col"><button class="btn btn-light dial-button" data-value="6">6</button></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col"><button class="btn btn-light dial-button" data-value="7">7</button></div>
                        <div class="col"><button class="btn btn-light dial-button" data-value="8">8</button></div>
                        <div class="col"><button class="btn btn-light dial-button" data-value="9">9</button></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col"><button class="btn btn-light dial-button" data-value="*">*</button></div>
                        <div class="col"><button class="btn btn-light dial-button" data-value="0">0</button></div>
                        <div class="col"><button class="btn btn-light dial-button" data-value="#">#</button></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">End Call</button>
                <button type="button" class="btn btn-primary">Call</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.call-button').forEach(function (button) {
            button.addEventListener('click', function () {
                var modal = new bootstrap.Modal(document.getElementById('dialPadModal'));
                modal.show();
            });
        });

        document.querySelectorAll('.dial-button').forEach(function (button) {
            button.addEventListener('click', function () {
                var dialedNumber = document.getElementById('dialedNumber');
                dialedNumber.value += this.getAttribute('data-value');
            });
        });
    });
</script>
@endsection
