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
                                <h3 class="card-title">{{ __('Orders by Riders') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-3">
                                        <label for="date" class="form-label required">{{ __('Delivery Date') }}</label>
                                        <input name="date" id="date" type="date"
                                               class="form-control example-date-input @error('date') is-invalid @enderror"
                                               required>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('date') }}
                                        </div>
                                    </div>

                                    <div class="col-md-3">
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

                                    <div class="col-md-3">
                                        <label for="order_no" class="form-label">{{ __('Order Number') }}</label>
                                        <input name="order_no" id="order_no" type="text"
                                               class="form-control @error('order_no') is-invalid @enderror">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('order_no') }}
                                        </div>
                                    </div>

                                    <div class="col-md-3 align-self-end">
                                        <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="col-lg-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Orders List</h3>
                    </div>
                    <div class="card-body">
                        <div style="padding-top: 1rem;" class="table-responsive">
                            <table class="table table-bordered" id="products_table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="align-middle">Order No</th>
                                        <th class="align-middle">Product</th>
                                        <th class="align-middle text-center">Quantity</th>
                                        <th class="align-middle text-center">Price</th>
                                        <th class="align-middle text-center">Client Name</th>
                                        <th class="align-middle text-center">Phone No.</th>
                                        <th class="align-middle text-center">Address</th>
                                        <th class="align-middle text-center">Status</th>
                                        <th class="align-middle text-center">Code</th>
                                        <th class="align-middle text-center">Rider/Agent</th>
                                        <th class="align-middle text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($orders))
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="align-middle" hidden>{{ $order->id }}</td> <!-- Hidden order ID -->
                                                <td class="align-middle">{{ $order->order_no }}</td>
                                                <td class="align-middle text-center">{{ $order->item }}</td>
                                                <td class="align-middle text-center">{{ $order->quantity }}</td>
                                                <td class="align-middle text-center">{{ $order->amount }}</td> <!-- Display amount -->
                                                <td class="align-middle text-center">{{ $order->client_name }}</td>
                                                <td class="align-middle text-center">{{ $order->phone }}</td>
                                                <td class="align-middle text-center">{{ $order->client_city }}</td>
                                                <td class="align-middle text-center">{{ $order->status }}</td>
                                                <td class="align-middle text-center">{{ $order->code }}</td>
                                                <td class="align-middle text-center">{{ $order->agent }}</td>
                                                <td class="align-middle text-center">
                                                    <button type="button" class="btn btn-icon btn-outline-danger pay-button"
                                                            data-id="{{ $order->id }}"
                                                            data-order-no="{{ $order->order_no }}"
                                                            data-phone="{{ $order->phone }}"
                                                            data-amount="{{ $order->amount }}"> <!-- Add data-amount attribute -->
                                                        Pay
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.pay-button').forEach(function(button) {
            button.addEventListener('click', function() {
                // Fetch id, order number, phone number, and amount from the button's data attributes
                const id = button.getAttribute('data-id');
                const orderNo = button.getAttribute('data-order-no');
                const phone = button.getAttribute('data-phone');
                const amount = button.getAttribute('data-amount');

                // Make an AJAX request to initiate the STK push
                fetch(`/mpesa/stk`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: id,
                        order_no: orderNo,
                        phone: phone,
                        amount: amount // Include amount in the request body
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('STK Push initiated successfully.');
                    } else {
                        alert('Failed to initiate STK Push.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while initiating the STK Push.');
                });
            });
        });
    });
</script>

@endsection
