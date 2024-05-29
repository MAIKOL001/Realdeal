@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Purchase Edit') }}
                    </h3>
                </div>

                <div class="card-actions btn-actions">
                   
                    <a href="{{ route('dispatch.index') }}" class="btn-action">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">Name</label>
                        <div class="form-control form-control-solid">Michael wambua</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">Email</label>
                        <div class="form-control form-control-solid">m@gmail.com</div>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">Phone</label>
                        <div class="form-control form-control-solid">011109542</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">Order Date</label>
                        <div class="form-control form-control-solid">09-07-1912</div>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">No Purchase</label>
                        <div class="form-control">PU-0989-L0</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">Total</label>
                        <div class="form-control form-control-solid">7890</div>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">Created By</label>
                        <div class="form-control form-control-solid">Maikol</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">Updated By</label>
                        <div class="form-control form-control-solid">Maikol</div>
                    </div>
                </div>
                <div class="mb-3">
                    <label  class="small mb-1">Address</label>
                    <div class="form-control form-control-solid">Shinyalavandu</div>
                </div>
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="align-middle text-center">No.</th>
                                    <th scope="col" class="align-middle text-center">Photo</th>
                                    <th scope="col" class="align-middle text-center">Product Name</th>
                                    <th scope="col" class="align-middle text-center">Product Code</th>
                                    <th scope="col" class="align-middle text-center">Current Stock</th>
                                    <th scope="col" class="align-middle text-center">Quantity</th>
                                    <th scope="col" class="align-middle text-center">Price</th>
                                    <th scope="col" class="align-middle text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                    <tr>
                                        <td class="align-middle text-center"></td>
                                        <td class="align-middle justify-content-center text-center">
                                            <div style="max-height: 80px; max-width: 80px;">
                                                <img class="img-fluid"
                                                    {{-- src="{{->product->product_image ? asset('storage/' . ->product->product_image) : asset('assets/img/products/default.webp') }}"> --}} >
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-indigo-lt">
                                                
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-primary-lt">
                                               
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-primary-lt">
                                                
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                           
                                        </td>
                                        <td class="align-middle text-center">
                                            
                                        </td>
                                    </tr>
                                
                                {{-- created by --}}
                                <tr>
                                    <td class="align-middle text-end" colspan="7">
                                        Created By
                                    </td>
                                    <td class="align-middle text-center">
                                        
                                    </td>
                                </tr>

                                <tr>
                                    <td class="align-middle text-end" colspan="7">
                                        Tax Percentage
                                    </td>
                                    <td class="align-middle text-center">
                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle text-end" colspan="7">
                                        Tax Amount
                                    </td>
                                    <td class="align-middle text-center">
                                        
                                    </td>
                                </tr>

                                <tr>
                                    <td class="align-middle text-end" colspan="7">
                                        Status
                                    </td>
                                    <td class="align-middle text-center">
                                      
                                            <span class="badge bg-success-lt">
                                                Approve
                                            </span>
                                      
                                            <span class="badge bg-warning-lt">
                                                Pending
                                            </span>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
              
                    <form action="" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="">

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Are you sure you want to approve this purchase?')"
                        >
                            {{ __('Approve Purchase') }}
                        </button>
                    </form>
                
            </div>
        </div>
    </div>
</div>
@endsection
