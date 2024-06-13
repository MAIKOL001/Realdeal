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
                            <h3 class="card-title">Sync Orders</h3>
                        </div>
                    </div>
                </div>
                <!-- Filter Form -->
                <form method="GET" action="{{ route('sheetssync') }}">
                    <div class="row gx-3 mb-3 p-3">
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-4" style="padding-top: 0.5rem;">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
                <form method="POST" action="{{ route('updateSheet') }}">
                    @csrf
                    <div class="table-responsive" style="padding-top:1rem;">
                        <table class="table table-bordered text-nowrap datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="align-middle text-center">Date</th>
                                    <th scope="col" class="align-middle text-center">Order No.</th>
                                    <th scope="col" class="align-middle text-center">Agent</th>
                                    <th scope="col" class="align-middle text-center">Status</th>
                                    <th scope="col" class="align-middle text-center">Sheet Id</th>
                                    <th scope="col" class="align-middle text-center">Sheet Name</th>
                                    <th scope="col" class="align-middle text-center">Actions</th>
                                </tr>
                            </thead>
                            @if ($sheetOrders->count())
                                <tbody>
                                    @foreach ($sheetOrders as $sheetOrder)
                                        <tr>
                                            <td class="align-middle text-center">{{ $sheetOrder->date }}</td>
                                            <td class="align-middle text-center">
                                                <input type="hidden" name="order_no[]" value="{{ $sheetOrder->order_no }}">
                                                {{ $sheetOrder->order_no }}
                                            </td>
                                            <td class="align-middle text-center">
                                                <input type="text" name="agent[]" value="{{ $sheetOrder->agent }}" class="form-control">
                                            </td>
                                            <td class="align-middle text-center">
                                                <input type="text" name="status[]" value="{{ $sheetOrder->status }}" class="form-control">
                                            </td>
                                            <td class="align-middle text-center">{{ $sheetOrder->sheet_id }}</td>
                                            <td class="align-middle text-center">{{ $sheetOrder->sheet_name }}</td>
                                            <td class="align-middle text-center">
                                                <button type="submit" class="btn btn-success">Update</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="align-middle text-center">No data to display.</td>
                                    </tr>
                                </tbody>
                            @endif
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
