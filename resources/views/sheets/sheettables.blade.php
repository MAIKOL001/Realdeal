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
                            <option value="JUNE 14" {{ request('sheet_name') == 'JUNE 14' ? 'selected' : '' }}>JUNE 14</option>
                            <option value="JUNE 15" {{ request('sheet_name') == 'JUNE 15' ? 'selected' : '' }}>JUNE 15</option>
                            <!-- Add more sheets as needed -->
                        </select>
                    </div>
                    
                    <div class="col-md-1" style="padding-top: 0.5rem;">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                    <div class="col-md-3" style="padding-top: 0.5rem;">
                        <button type="button" class="btn btn-success call-button" data-bs-toggle="modal" data-bs-target="#dialPadModal">Call</button>
                        <button type="button" class="btn btn-success sms-button" data-bs-toggle="modal" data-bs-target="#smsModal">Text</button>
                        <button type="submit" form="storeToDbForm" class="btn btn-danger">Store to DB</button>
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
        <table wire:loading.remove class="table table-bordered text-nowrap datatable" style="width: 100%;">
            <thead>
                <tr>
                    @foreach ($header as $heading)
                    <th scope="col" class="align-middle text-center" style="width: 200px;">{{ $heading }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($dataRows as $index => $rowData)
                <tr>
                    @foreach ($rowData as $colIndex => $cell)
                    <td class="align-middle text-center" style="width: 200px;">
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
    </form>
</div>
</div>
</div>

<!-- Store to DB Form -->
<form id="storeToDbForm" method="POST" action="{{ route('sheets.push') }}">
    @csrf
    
    <input type="hidden" name="sheet_name" value="{{ $sheetNameFilter }}">
    <input type="hidden" name="sheet_id" value="{{ $sheetId }}">
    <input type="hidden" name="data" value="{{ json_encode($dataRows) }}"> <!-- Encode dataRows array to JSON -->
</form>


<!-- Dial Pad Modal -->
<div class="modal fade" id="dialPadModal" tabindex="-1" aria-labelledby="dialPadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dialPadModalLabel">Dial Pad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="callFrom" class="form-control mb-3 text-center" style="max-width: 320px; margin: 0 auto;" value="+254212345678" readonly hidden>
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
                <button type="button" class="btn btn-primary" id="makeCallButton">Call</button>
            </div>
        </div>
    </div>
</div>

<!-- SMS Modal -->
<div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="smsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smsModalLabel">Send Text</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="smsForm">
                    <div class="mb-3">
                        <label for="messageType" class="form-label">Message Type</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="messageType" id="smsOption" value="sms" checked>
                                <label class="form-check-label" for="smsOption">SMS</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="messageType" id="whatsappOption" value="whatsapp">
                                <label class="form-check-label" for="whatsappOption">WhatsApp</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="recipientNumber" class="form-label">Recipient Number</label>
                        <input type="text" class="form-control" id="recipientNumber" placeholder="Enter recipient number">
                    </div>
                    <div class="mb-3">
                        <label for="messageText" class="form-label">Message</label>
                        <textarea class="form-control" id="messageText" rows="3" placeholder="Enter your message"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sendSms()">Send Message</button>
            </div>
        </div>
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

    document.querySelectorAll('.sms-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var modal = new bootstrap.Modal(document.getElementById('smsModal'));
            modal.show();
        });
    });

    document.querySelectorAll('.dial-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var dialedNumber = document.getElementById('dialedNumber');
            dialedNumber.value += this.getAttribute('data-value');
        });
    });

    document.getElementById('makeCallButton').addEventListener('click', function () {
        var callFrom = document.getElementById('callFrom').value;
        var dialedNumber = document.getElementById('dialedNumber').value;

        fetch('{{ route("make-call") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ from: callFrom, to: dialedNumber })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Call initiated:', data);
            alert('Call initiated successfully!');
            var modal = bootstrap.Modal.getInstance(document.getElementById('dialPadModal'));
            modal.hide();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while initiating the call.');
        });
    });
});

function sendSms() {
    var messageType = document.querySelector('input[name="messageType"]:checked').value;
    var recipientNumber = document.getElementById('recipientNumber').value;
    var messageText = document.getElementById('messageText').value;

    console.log('Message Type:', messageType);
    console.log('Sending to:', recipientNumber);
    console.log('Message:', messageText);

    var modal = bootstrap.Modal.getInstance(document.getElementById('smsModal'));
    modal.hide();
}
</script>
@endsection
