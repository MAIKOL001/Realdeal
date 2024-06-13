@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <x-alert/>

        <div class="row row-cards">
            <form action="{{ route('orders.search') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('orders by riders') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="date" class="form-label required">{{ __('Delivery Date') }}</label>
                                        <input name="date" id="date" type="date"
                                               class="form-control example-date-input @error('date') is-invalid @enderror"
                                               required>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('date') }}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="agent" class="form-label required">{{ __('Rider/Agent') }}</label>
                                        <select class="form-select" id="agent" name="agent" required>
                                            <option value="">Select Rider/Agent</option>
                                            @foreach ($distributors as $distributor)
                                                <option value="{{ $distributor->name }}">{{ $distributor->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('agent') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                </div>
                            </form>
                            <div>
                                <table class="table table-bordered" id="products_table">
                                    <!-- Table Header -->
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="align-middle">Order No</th>
                                            <th class="align-middle">Product</th>
                                            <th class="align-middle text-center">Quantity</th>
                                            <th class="align-middle text-center">Price</th>
                                            <th class="align-middle text-center">Client Name</th>
                                            <th class="align-middle text-center">Phone No.</th>
                                            <th class="align-middle text-center">Address</th>
                                            <th class="align-middle text-center">Rider/Agent</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <!-- Table Body -->
                                    <tbody>
                                        @if (isset($orders))
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td class="align-middle">{{ $order->order_no }}</td>
                                                    <td class="align-middle text-center">{{ $order->item }}</td>
                                                    <td class="align-middle text-center">{{ $order->quantity }}</td>
                                                    <td class="align-middle text-center">{{ $order->amount }}</td>
                                                    <td class="align-middle text-center">{{ $order->client_name }}</td>
                                                    <td class="align-middle text-center">{{ $order->phone }}</td>
                                                    <td class="align-middle text-center">{{ $order->client_city }}</td>
                                                    <td class="align-middle text-center">{{ $order->agent }}</td>
                                                    <td class="align-middle text-center">
                                                        <button type="button" class="btn btn-icon btn-outline-danger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M4 7l16 0" />
                                                                <path d="M10 11l0 6" />
                                                                <path d="M14 11l0 6" />
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Button for PDF generation -->
                            <form action="{{ route('orders.generate_pdf') }}" method="POST" target="_blank">
                                @csrf
                                <input type="hidden" name="data" value="{{ json_encode($orders) }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <input type="hidden" name="agent" value="{{ $agent }}">
                                <button type="submit" class="btn btn-primary">{{ __('Generate PDF') }}</button>
                            </form>
                            
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
