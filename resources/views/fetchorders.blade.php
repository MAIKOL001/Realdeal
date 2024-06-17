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
                <div>
                    <h3 class="card-title">
                        Assign Order to Riders
                    </h3>
                </div>

                <div class="card-actions btn-actions">
                    <div class="dropdown">
                        

                        <div class="dropdown-menu dropdown-menu-end" style="">
                            <form action="" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-success"
                                    onclick="return confirm('Are you sure you want to approve this order?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24H0z" fill="none" />
                                        <path d="M5 12l5 5l10-10" />
                                    </svg>
                                    {{ __('Approve Order') }}
                                </button>
                            </form>
                        </div>
                    </div>
                    <x-action.close route="{{ route('dispatch.index') }}" />
                </div>
            </div>

            <div class="card-body">
                <div class="row row-cards mb-3">
                    <div class="col">
                        <label for="date" class="form-label required">Date</label>
                        <input type="date" id="date" class="form-control" value="">
                    </div>
                    <form id="searchForm" method="POST" action="{{ route('search.order') }}" class="mt-3">
                        @csrf <!-- Add this line to include CSRF token -->
                        <label for="order_no" class="form-label required">Order No</label>
                        <div class="input-group">
                            <input type="text" id="order_no" name="order_no" class="form-control"
                                placeholder="Enter Order No" aria-label="Enter Order No" aria-describedby="searchBtn">
                            <button type="submit" id="searchBtn" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                    <form action="{{ route('assign.rider') }}" method="POST">
                        @csrf
                        <div class="col">
                            <label for="agent" class="form-label required">Rider</label>
                            <div class="col">
                                <select class="form-select" id="agent" name="agent">
                                    @foreach ($distributors as $distributor)
                                    <option value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                    
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="align-middle text-center">Order Id</th>
                                <th scope="col" class="align-middle text-center">Order No.</th>
                                <th scope="col" class="align-middle text-center">Amount</th>
                                <th scope="col" class="align-middle text-center">Quantity</th>
                                <th scope="col" class="align-middle text-center">Item</th>
                                <th scope="col" class="align-middle text-center">Client Name</th>
                                <th scope="col" class="align-middle text-center">Client City</th>
                                <th scope="col" class="align-middle text-center">Phone</th>
                                <th scope="col" class="align-middle text-center">Agent</th>
                                <th scope="col" class="align-middle text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($searchedOrders as $order)
                            <tr>
                                <td class="align-middle text-center">
                                    {{ $order->id }}
                                    <input type="hidden" name="order_ids[]" value="{{ $order->id }}">
                                </td>
                                <td class="align-middle text-center">{{ $order->order_no }}</td>
                                <td class="align-middle text-center">{{ $order->amount }}</td>
                                <td class="align-middle text-center">{{ $order->quantity }}</td>
                                <td class="align-middle text-center">{{ $order->item }}</td>
                                <td class="align-middle text-center">{{ $order->client_name }}</td>
                                <td class="align-middle text-center">{{ $order->client_city }}</td>
                                <td class="align-middle text-center">{{ $order->phone }}</td>
                                <td class="align-middle text-center">{{ $order->agent }}</td>
                                <td class="align-middle text-center">
                                    <!-- Action button to remove the order from the table -->
                                    <button class="btn btn-danger" onclick="removeOrder(this)">Remove</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Assign Rider</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    function removeOrder(button) {
        // Get the row of the clicked button
        var row = button.parentNode.parentNode;
        // Remove the row from the table
        row.parentNode.removeChild(row);
    }
</script>
@endsection
