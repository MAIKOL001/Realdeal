@extends('layouts.tabler')

@section('content')
<div class="page-body">
    {{-- @if(!$purchases)
    <x-empty
        title="No purchases found"
        message="Try adjusting your search or filter to find what you're looking for."
        button_label="{{ __('Add your first Purchase') }}"
        button_route="{{ route('.create') }}"
    />
    @else --}}
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        {{ __('Dispatched Orders') }}
                    </h3>
                </div>
        
                <div class="card-actions">
                    <x-action.create route="{{ route('dispatch.create') }}" />
                </div>
            </div>
        
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-secondary">
                        Show
                        <div class="mx-2 d-inline-block">
                            <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
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
                            <input type="text" wire:model.live="search" class="form-control form-control-sm" aria-label="Search invoice">
                        </div>
                    </div>
                </div>
            </div>
        
            <x-spinner.loading-spinner/>
        
            <div class="table-responsive">
                <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
                    <thead class="thead-light">
                        <tr>
                            <th class="align-middle text-center w-1">
                                {{ __('No.') }}
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('purchase_no')" href="#" role="button">
                                    {{ __('Purchase No.') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'purchase_no']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('supplier_id')" href="#" role="button">
                                    {{ __('Supplier') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'supplier_id']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('date')" href="#" role="button">
                                    {{ __('Date') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'date']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('total_amount')" href="#" role="button">
                                    {{ __('Total') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'total_amount']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('status')" href="#" role="button">
                                    {{ __('Status') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'status']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                {{ __('Action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                   
                        <tr>
                            <td class="align-middle text-center">
                               
                            </td>
                            <td class="align-middle text-center">
                               
                            </td>
                            <td class="align-middle text-center">
                               
                            </td>
                            <td class="align-middle text-center">
                               
                            </td>
                           
        
                            
                                <td class="align-middle text-center">
                                    
                                </td>
                                <td class="align-middle text-center">
                                    
                                </td>
                           
                               
                                <td class="align-middle text-center" style="width: 10%">
                                    <x-button.show class="btn-icon" route=""/>
                        
                                    <x-button.delete class="btn-icon" onclick="return confirm('Are you sure!')" route=""/>
                                </td>
                            
                        </tr>
                        
                        <tr>
                            <td class="align-middle text-center" colspan="7">
                                No results found
                            </td>
                        </tr>
                   
                    </tbody>
                </table>
            </div>
        
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-secondary">
                    Showing <span></span>
                    to <span></span> of <span></span> entries
                </p>
        
                <ul class="pagination m-0 ms-auto">
                
                </ul>
            </div>
        </div>
        
 
</div>
@endsection
