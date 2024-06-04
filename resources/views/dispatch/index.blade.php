@extends('layouts.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Google Sheets
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#linkSheetModal">
                            <x-icon.plus />
                            Add new sheet
                        </button>
                        <button class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#linkSheetModal" aria-label="Link new sheet">
                            <x-icon.plus />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="row row-cards">
                        @foreach($sheets as $sheet)
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="text-white avatar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
                                                        <path fill="#43a047" d="M37,45H11c-1.657,0-3-1.343-3-3V6c0-1.657,1.343-3,3-3h19l10,10v29C40,43.657,38.657,45,37,45z"></path>
                                                        <path fill="#c8e6c9" d="M40 13L30 13 30 3z"></path>
                                                        <path fill="#2e7d32" d="M30 13L40 23 40 13z"></path>
                                                        <path fill="#e8f5e9" d="M31,23H17h-2v2v2v2v2v2v2v2h18v-2v-2v-2v-2v-2v-2v-2H31z M17,25h4v2h-4V25z M17,29h4v2h-4V29z M17,33h4v2h-4V33z M31,35h-8v-2h8V35z M31,31h-8v-2h8V31z M31,27h-8v-2h8V27z"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="col">
                                                <div class="font-weight-medium">
                                                    <a href="{{ route('dispatch.show', $sheet->sheet_id) }}">
                                                        {{ $sheet->sheet_name }}
                                                    </a>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for linking new sheet -->
    <div class="modal fade" id="linkSheetModal" tabindex="-1" role="dialog" aria-labelledby="linkSheetModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('sheets.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="linkSheetModalLabel">Link New Sheet</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="sheet_id" class="form-label">Sheet ID</label>
                            <input type="text" class="form-control" id="sheet_id" name="sheet_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="sheet_name" class="form-label">Sheet Name</label>
                            <input type="text" class="form-control" id="sheet_name" name="sheet_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
