<div class="card">
    <form method="POST" action="{{ route('ordersimport.update') }}">
        @csrf
    <div class="card-header">
        <div>
            <h3 class="card-title">{{ __('Orders') }}</h3>
        </div>
        
        <div class="card-actions">
            <a href="#">
                <button type="submit" class="btn btn-primary">Update Sheet</button>
            </a>
        </div>
    </div>
    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Show
                <div class="mx-2 d-inline-block">
                    <select wire:model="perPage" class="form-select form-select-sm" aria-label="result per page">
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
                    <input type="text" wire:model="search" class="form-control form-control-sm" aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner />

    <div class="table-responsive">
        
            <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
                <thead class="thead-light">
                    <tr>
                        <th class="align-middle text-center w-1">{{ __('No.') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Order No.') }}</th>
                        <form method="POST" action="{{ route('ordersimport.update') }}">
                            @csrf
                        <th scope="col" class="align-middle text-center">{{ __('Amount') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Quantity') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Item') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Client Name') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Client City') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Phone') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Agent') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Order Status') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Transaction Code') }}</th>
                        <th scope="col" class="align-middle text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataRows as $index => $rowData)
                        <tr>
                            <td class="align-middle text-center">{{ $index + 1 }}</td>
                            @foreach ($rowData as $colIndex => $cell)
                                <td class="align-middle text-center" style="width: 12rem;">
                                    <input type="text" name="data[{{ $index }}][{{ $colIndex }}]" value="{{ $cell }}" class="form-control">
                                </td>
                            @endforeach
                            <td class="align-middle text-center">
                                <button type="submit" class="btn btn-primary">update</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
           
        </form>
    </div>
</div>
    
    
