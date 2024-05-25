@extends('layouts.tabler')

@section('content')
<div class="page-body">
   
       
        <div class="container-xl">
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
                            {{ __('Distributors') }}
                        </h3>
                    </div>
            
                    <div class="card-actions">
                        <x-action.create route="{{ route('distributors.create') }}" />
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
                                {{ __('ID.') }}
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('name')" href="#" role="button">
                                    {{ __('Name') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'name']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('email')" href="#" role="button">
                                    {{ __('Email address') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'email']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('shopname')" href="#" role="button">
                                    {{ __('Region name') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'shopname']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('type')" href="#" role="button">
                                    {{ __('Type') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'type']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                <a wire:click.prevent="sortBy('created_at')" href="#" role="button">
                                    {{ __('Created at') }}
                                    {{-- @include('inclues._sort-icon', ['field' => 'created_at']) --}}
                                </a>
                            </th>
                            <th scope="col" class="align-middle text-center">
                                {{ __('Action') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($distributors as $distributor)
                                <tr>
                                    <td class="align-middle text-center">
                                        {{ $loop->index + 1 }}  </td>
                                    <td class="align-middle text-center">
                                        {{ $distributor->name }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $distributor->email }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $distributor->region }}  </td>
                                    <td class="align-middle text-center">
                                        {{ $distributor->type }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $distributor->created_at->format('Y-m-d') }}  </td>
                                        <td class="align-middle text-center">
                                            
                                            <x-button.edit class="btn-icon" route="{{ route('distributors.edit', $distributor->id) }}"/>
                                           
                                               
                                                <x-button.delete class="btn-icon" route="{{ route('distributors.destroy', $distributor->id) }}"/>
                                           
                                        </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td class="align-middle text-center" colspan="8">
                                        No distributors found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-secondary">
                        Showing <span>
                            {{-- {{ $suppliers->firstItem() }} --}}
                        </span> to <span>
                            {{-- {{ $suppliers->lastItem() }} --}}
                        </span> of <span>
                            {{-- {{ $suppliers->total() }} --}}
                        </span> entries
                    </p>
            
                    <ul class="pagination m-0 ms-auto">
                        {{-- {{ $suppliers->links() }} --}}
                    </ul>
                </div>
            </div>
            
        </div>

</div>
@endsection
