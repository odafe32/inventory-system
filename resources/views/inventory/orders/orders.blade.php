@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">All Order List</h4>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('orders') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus"></i> Create Order
                            </a>

                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Created at</th>
                                        <th>Customer</th>
                                        <th>Priority</th>
                                        <th>Total</th>
                                        <th>Payment Status</th>
                                        <th>Items</th>
                                        <th>Tax</th>
                                        <th>Order Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td>{{ $order->order_id }}</td>
                                            <td>{{ $order->formatted_date }}</td>
                                            <td class="text-primary">

                                                {{ $order->customer_name }}
                                            </td>
                                            <td>{{ $order->priority }}</td>
                                            <td>{{ $order->formatted_total }}</td>
                                            <td>
                                                <span class="badge {{ $order->payment_status_class }} px-2 py-1 fs-13">
                                                    {{ $order->payment_status }}
                                                </span>
                                            </td>
                                            <td>{{ $order->total_items }}</td>
                                            <td>₦{{ $order->tax ?? '-' }}</td>
                                            <td>
                                                <span class="badge {{ $order->order_status_class }} px-2 py-1 fs-13">
                                                    {{ $order->order_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('orders-details', $order->id) }}"
                                                        class="btn btn-light btn-sm">
                                                        <iconify-icon icon="solar:eye-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('edit-order', $order->id) }}"
                                                        class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <form action="{{ route('orders.delete', $order->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete order {{ $order->order_id }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">No orders found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        {{ $orders->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->

    <!-- Add this delete confirmation modal at the end of your orders.blade.php file -->
    <div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this order?</p>
                    <p class="mb-0"><strong>Order ID:</strong> <span id="deleteOrderId"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteOrderForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .page-item.active .btn-primary {
            background-color: #556ee6;
            border-color: #556ee6;
            color: #fff;
        }

        .page-item .btn-light {
            color: #6c757d;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .page-item.disabled .btn-light {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
            opacity: 0.65;
            pointer-events: none;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Delete order confirmation
                const deleteModalElement = document.getElementById('deleteOrderModal');

                if (deleteModalElement) {
                    const deleteModal = new bootstrap.Modal(deleteModalElement);
                    const deleteForm = document.getElementById('deleteOrderForm');

                    // Function to show modal and set form action
                    window.confirmDelete = function(orderId, orderNumber) {
                        alert('Delete function called: ' + orderNumber);
                        document.getElementById('deleteOrderId').textContent = orderNumber;
                        deleteForm.action = `/orders/${orderId}`;
                        deleteModal.show();
                    };
                    console.log('Bootstrap Modal instance:', deleteModal);
                }
            });
        </script>
    @endpush
@endsection
