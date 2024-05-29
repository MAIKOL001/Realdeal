@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <x-alert/>

        <div class="row row-cards">

            @if (isset($product))
                <form action="" method="POST">
                    @csrf
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div>
                                        <h3 class="card-title">
                                            {{ __('Create QrCodes') }}
                                        </h3>
                                    </div>

                                    <div class="card-actions btn-actions">
                                        <a href="{{ route('products.index') }}" class="btn-action">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="row gx-3 mb-1">
                                        <div class="col-md-4">
                                            <label for="productname" class="form-label required">
                                                {{ __('Product Name') }}
                                            </label>

                                            <input name="product_name" id="name" type="text"
                                                class="form-control example-date-input"
                                                value="{{ $product->name }}"
                                                readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="product code" class="form-label required">
                                                {{ __('Product Code') }}
                                            </label>

                                            <input type="text" class="form-control"
                                                id="reference"
                                                name="product_code"  value="{{ $product->code }} -"
                                                readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="product code" class="form-label required">
                                                {{ __('Parent product id') }}
                                            </label>

                                            <input type="text" class="form-control"
                                                id="reference"
                                                name=" Parent product id"  value="{{ $product->id }}"
                                                readonly>
                                        </div>


                                        <div class="col-md-4">
                                            <label for="product code" class="form-label required">
                                                {{ __('Quantity') }}
                                            </label>

                                            <input type="number" class="form-control"
                                                id="reference"
                                                name="quantity"  value=""
                                                required>
                                        </div>
                                       
                                        <div class="col-md-4" hidden>
                                            <label for="product code" class="form-label required">
                                                {{ __('Product unit id') }}
                                            </label>
    
                                            <input type="text" class="form-control"
                                                id="reference"
                                                name="product_code"  value="{{ $product->unit_id}}"
                                                readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="product code" class="form-label required">
                                                {{ __('Date generated') }}
                                            </label>
    
                                            <input type="date" class="form-control"
                                                id="reference"
                                                name="product_code"  value="date">
                                        </div>
                                        
                                        
                                    </div>
                                   
                                </form>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Generate QRCodes') }}
                                    </button>
                                    </div>

                                    <div>
                                        <form>
                                        <table class="table table-bordered" id="products_table">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="align-middle">Code</th>
                                                    <th class="align-middle text-center">Qr codes</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                   
                                                    <td class="align-middle text-center">PC-09-00000000001</td>
                                                   
                                                    <td class="align-middle text-center">
                                                        <img src="">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                    </div>
                                    
                                       
                                   
                                </div>

                               
                            </div>
                        </div>
                    </div>
               
                <button type="submit" class="btn btn-primary">
                    {{ __('Upload') }}
                </button>
            @endif 
        </div>
    </div>
</div>
@endsection