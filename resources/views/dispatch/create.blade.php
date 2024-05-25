@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <x-alert/>

        <div class="row row-cards">

            <form action="{{ route('purchases.store') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h3 class="card-title">
                                        {{ __('Assign Order for Delivery') }}
                                    </h3>
                                </div>

                                
                            </div>
                            <div class="card-body">

                                <div class="row gx-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="date" class="form-label required">
                                            {{ __('Delivery Date') }}
                                        </label>

                                        <input name="date" id="date" type="date"
                                               class="form-control example-date-input

                                               @error('date') is-invalid @enderror"
                                               value="{{ old('date') ?? now()->format('Y-m-d') }}"
                                               required
                                        >

                                        @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>


                                    <x-tom-select
                                        label="Suppliers"
                                        id="supplier_id"
                                        name="supplier_id"
                                        placeholder="Select Customer"
                                       
                                    />

                                    <div class="col-md-4">
                                        <label for="reference" class="form-label required">
                                            {{ __('Rider/Agent') }}
                                        </label>

                                        <input type="text" class="form-control"
                                               id="distributor"
                                               name="distributor"
                                               value=""
                                               
                                        >

                                        @error('reference')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="reference" class="form-label required">
                                            {{ __('Scan barcode') }}
                                        </label>

                                        <input type="text" class="form-control"
                                               id="barcode"
                                               name="barcode"
                                               value=""
                                               
                                        >

                                        @error('reference')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <table class="table table-bordered" id="products_table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="align-middle">Order No</th>
                                                <th class="align-middle">Product</th>
                                                <th class="align-middle text-center">Quantity</th>
                                                <th class="align-middle text-center">Price</th>
                                                <th class="align-middle text-center">Client Name</th>
                                                <th class="align-middle text-center">Address</th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                
                                        <tbody>
                                            
                                            <tr>
                                                <td class="align-middle">
                                                   
                                                        <input type="hidden" name="]" value="">
                                
                                                       
                                
                                                        @error('invoiceProducts.')
                                                            <em class="text-danger">
                                                                {{ $message }}
                                                            </em>
                                                        @enderror
                                                   
                                                </td>
                                
                                                <td class="align-middle text-center">
                                                </td>
                                
                                                <td class="align-middle text-center">
                                                </td>
                                                
                                                {{--- Unit Price ---}}
                                                <td class="align-middle text-center">
                                                   
                                
                                                        <input type="hidden"
                                                               name="invoiceProducts"
                                                               value=""
                                                        >
                                                    
                                                </td>
                                
                                                {{--- Total ---}}
                                                <td class="align-middle text-center">
                                                    
                                
                                                    <input type="hidden"
                                                           name="invoiceProducts"
                                                           value=""
                                                    >
                                                </td>

                                                <td class="align-middle text-center">
                                            </td>
                                                <td class="align-middle text-center">
                                                   
                                             
                                                    <button type="button" wire:click="removeProduct" class="btn btn-icon btn-outline-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                    
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Assign') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
