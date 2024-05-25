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
                            {{-- <h3 class="card-title">Googlesheet Name: {{ $sheetName }}</h3> --}}
                        </div>
                       
                    </div>
                    
                </div>
                <form method="GET" action="{{ route('sheets.show', $sheetId) }}">  @csrf
                    <div class="input-group">
                        <input type="date" name="date" class="form-control" placeholder="Select Date">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-secondary">
                            Show
                            <div class="mx-2 d-inline-block">
                                <select wire:model="perPage" class="form-select form-select-sm" aria-label="result per page">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="25">25</option>
                                </select>
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-secondary">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" wire:model="search" class="form-control form-control-sm" aria-label="Search invoice">
                            </div>
                        </div>
                    </div>
                </div>
                
                <x-spinner.loading-spinner />
                
                <div class="table-responsive">
                    @if (isset($header) && isset($dataRows))  <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    @foreach ($header as $heading)
                                        <th scope="col" class="align-middle text-center">{{ $heading }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($date) && isset($statusValue))
                                    <?php // Access column indexes once outside the loop ?>
                                     @php
                                        $statusColIndex = 9;
                                        $dateColIndex = 6;
                                    @endphp
                                    @foreach ($dataRows as $index => $rowData)
                                        <?php // Access data and format date within the loop ?>
                                        @php
                                            $cellStatus = isset($rowData[$statusColIndex]) ? strtolower($rowData[$statusColIndex]) : '';
                                            $cellDate = isset($rowData[$dateColIndex]) ? date('m/d/Y', strtotime($rowData[$dataRows[$index][$dateColIndex]])) : '';
                                        @endphp
                                        @if ($cellStatus === $statusValue && $cellDate === $date)
                                            <tr>
                                                @foreach ($rowData as $colIndex => $cell)
                                                    <td class="align-middle text-center" style="width: 12rem;">
                                                        {{ $cell }}
                                                    </td>
                                                
                                                @endforeach
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($dataRows as $index => $rowData)
                                        <tr>
                                            @foreach ($rowData as $colIndex => $cell)
                                                <td class="align-middle text-center" style="width: 12rem;">
                                                    {{ $cell }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    @else
                                <p>No data to display.</p>
                            @endif
                           
                        
                    </div>
                </div>
                    
                    
                


            </div>
        
    </div>
@endsection
