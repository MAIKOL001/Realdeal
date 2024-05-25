@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">
                                {{ __('Print Order') }}
                            </h3>
                        </div>

                        
                    </div>
                    <form action="{{ route('invoice.create') }}" method="POST">
                    @csrf
                        <div class="card-body">
                            <div class="row gx-3 mb-3">
                                @include('partials.session')
                                <div class="col-md-4">
                                    <label for="purchase_date" class="small my-1">
                                        {{ __('Date') }}
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input name="purchase_date" id="purchase_date" type="date"
                                           class="form-control example-date-input @error('purchase_date') is-invalid @enderror"
                                           value="{{ old('purchase_date') ?? now()->format('Y-m-d') }}"
                                           required
                                    >

                                    @error('purchase_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                               
                                

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered align-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">{{ __('Order No.') }}</th>
                                            <th scope="col">{{ __('Product') }}</th>
                                            <th scope="col" class="text-center">{{ __('Quantity') }}</th>
                                            <th scope="col" class="text-center">{{ __('Price') }}</th>
                                            <th scope="col" class="text-center">{{ __('phone') }}</th>
                                            <th scope="col" class="text-center">{{ __('city') }}</th>
                                            <th scope="col" class="text-center">{{ __('address') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td style="min-width: 170px;">
                                                
                                            </td>
                                            <td class="text-center">
                                                
                                            </td>
                                            <td class="text-center">
                                                
                                            </td>
                                            <td class="text-center">
                                                
                                            </td>
                                            <td class="text-center">
                                                
                                            </td>
                                            
                                        </tr>
                              
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-success add-list mx-1">
                                {{ __('Print Orders') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>


           

        </div>
    </div>
</div>
@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpushonce
