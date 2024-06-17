<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                {{ __('Orders') }}
            </h3>
        </div>

        <div class="card-actions">
            <x-action.create route="{{ route('orders.create') }}" />
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
                    <input type="text" wire:model.live="search" class="form-control form-control-sm"
                        aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner />

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
                <tr>
                    <th class="align-middle text-center w-1">
                        {{ __('No.') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('order_no')" href="#" role="button">
                            {{ __('Order No.') }}
                            
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Amount') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Quantity') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Item') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Client Name') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('City') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('date')" href="#" role="button">
                            {{ __('Date') }}
                           
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Phone') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('status')" href="#" role="button">
                            {{ __('Status') }}
                           
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Action') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td class="align-middle text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $order->order_no }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $order->amount }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $order->quantity }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $order->item }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $order->client_name }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $order->city }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $order->date }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $order->phone }}
                        </td>
                        <td class="align-middle text-center">
                            {{ $order->status }}
                        </td>
                        <td class="align-middle text-center">
                            <!-- Add your action buttons here -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="align-middle text-center" colspan="11">
                            No results found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $orders->firstItem() }}</span> to <span>{{ $orders->lastItem() }}</span> of
            <span>{{ $orders->total() }}</span> entries
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $orders->links() }}
        </ul>
    </div>
</div>
