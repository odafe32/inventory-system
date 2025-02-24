{{-- resources/views/inventory/orders/edit-order.blade.php --}}
@extends('layout.application_layout')

@section('content')
    <div class="container-xxl">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {!! session('error') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Edit Order</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('orders-details', $order->id) }}" class="btn btn-light btn-sm">
                                <iconify-icon icon="solar:arrow-left-broken" class="align-middle fs-18"></iconify-icon>
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="editOrderForm" method="POST" action="{{ route('orders.update', $order->id) }}"
                            class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <!-- Order Information Section -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Order Information</h5>

                                    <div class="mb-3">
                                        <label class="form-label">Order ID</label>
                                        <input type="text" class="form-control" value="{{ $order->order_id }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="priority">Priority</label>
                                        <select name="priority" id="priority" class="form-select" required>
                                            <option value="Normal" {{ $order->priority == 'Normal' ? 'selected' : '' }}>
                                                Normal</option>
                                            <option value="High" {{ $order->priority == 'High' ? 'selected' : '' }}>High
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">Please select a priority level.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="payment_status">Payment Status</label>
                                        <select name="payment_status" id="payment_status" class="form-select" required>
                                            <option value="Paid" {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>
                                                Paid</option>
                                            <option value="Unpaid"
                                                {{ $order->payment_status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            <option value="Refund"
                                                {{ $order->payment_status == 'Refund' ? 'selected' : '' }}>Refund</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a payment status.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="order_status">Order Status</label>
                                        <select name="order_status" id="order_status" class="form-select" required>
                                            <option value="Draft" {{ $order->order_status == 'Draft' ? 'selected' : '' }}>
                                                Draft</option>
                                            <option value="Packaging"
                                                {{ $order->order_status == 'Packaging' ? 'selected' : '' }}>Packaging
                                            </option>
                                            <option value="Shipping"
                                                {{ $order->order_status == 'Shipping' ? 'selected' : '' }}>Shipping
                                            </option>
                                            <option value="Completed"
                                                {{ $order->order_status == 'Completed' ? 'selected' : '' }}>Completed
                                            </option>
                                            <option value="Canceled"
                                                {{ $order->order_status == 'Canceled' ? 'selected' : '' }}>Canceled
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">Please select an order status.</div>
                                    </div>
                                </div>

                                <!-- Customer Information Section -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">Customer Information</h5>

                                    <div class="mb-3">
                                        <label class="form-label" for="customer_name">Customer Name</label>
                                        <input type="text" id="customer_name" name="customer_name" class="form-control"
                                            value="{{ $order->customer_name }}" required>
                                        <div class="invalid-feedback">Please enter customer name.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{ $order->email }}" required>
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            value="{{ $order->phone }}" required>
                                        <div class="invalid-feedback">Please enter phone number.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea id="address" name="address" class="form-control" rows="3" required>{{ $order->address }}</textarea>
                                        <div class="invalid-feedback">Please enter delivery address.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="mb-3">Order Items</h5>
                                    <div id="orderItems">
                                        @foreach ($order->orderItems as $index => $item)
                                            <div class="row mb-3 order-item">
                                                <div class="col-md-4">
                                                    <label class="form-label">Product</label>
                                                    <select name="products[]" class="form-select product-select" required>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                data-sizes="{{ $product->size }}"
                                                                {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                                {{ $product->name }}
                                                                (₦{{ number_format($product->price, 2) }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">Please select a product.</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Size</label>
                                                    <select name="sizes[]" class="form-select size-select" required>
                                                        @foreach (explode(',', $item->product->size) as $size)
                                                            <option value="{{ $size }}"
                                                                {{ $item->size == $size ? 'selected' : '' }}>
                                                                {{ $size }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">Please select a size.</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" name="quantities[]" class="form-control"
                                                        value="{{ $item->quantity }}" min="1" required>
                                                    <div class="invalid-feedback">Please enter a valid quantity.</div>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    @if ($index > 0)
                                                        <button type="button" class="btn btn-danger btn-sm remove-item">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mb-4">
                                        <button type="button" class="btn btn-light btn-sm" id="addItem">
                                            <iconify-icon icon="solar:add-circle-broken"
                                                class="align-middle fs-18"></iconify-icon>
                                            Add Item
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Order</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this order? This action cannot be undone.</p>
                    <p class="mb-0"><strong>Order ID:</strong> <span id="deleteOrderId"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteOrderForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const products = @json($products);
            const form = document.getElementById('editOrderForm');
            const addItemBtn = document.getElementById('addItem');
            const orderItems = document.getElementById('orderItems');

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add new item
            addItemBtn.addEventListener('click', function() {
                const itemTemplate = orderItems.children[0].cloneNode(true);
                const inputs = itemTemplate.querySelectorAll('input, select');
                inputs.forEach(input => input.value = '');

                // Add remove button if not present
                if (!itemTemplate.querySelector('.remove-item')) {
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-danger btn-sm remove-item';
                    removeBtn.innerHTML =
                        '<iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>';
                    itemTemplate.querySelector('.col-md-2').appendChild(removeBtn);
                }

                orderItems.appendChild(itemTemplate);

                // Initialize the new product's size options
                const newProductSelect = itemTemplate.querySelector('select[name="products[]"]');
                updateSizeOptions(newProductSelect);
            });

            // Remove item
            orderItems.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-item');
                if (removeBtn) {
                    const orderItem = removeBtn.closest('.order-item');
                    orderItem.remove();
                }
            });

            // Function to update size options
            function updateSizeOptions(productSelect) {
                const orderItem = productSelect.closest('.order-item');
                const sizeSelect = orderItem.querySelector('select[name="sizes[]"]');
                const selectedProduct = products.find(p => p.id === parseInt(productSelect.value));

                if (selectedProduct) {
                    const sizes = selectedProduct.size.split(',');
                    const currentSize = sizeSelect.value;

                    sizeSelect.innerHTML = sizes.map(size => {
                        const trimmedSize = size.trim();
                        return `<option value="${trimmedSize}" ${currentSize === trimmedSize ? 'selected' : ''}>
                        ${trimmedSize}
                    </option>`;
                    }).join('');
                }
            }

            // Handle product selection change
            orderItems.addEventListener('change', function(e) {
                if (e.target.name === 'products[]') {
                    updateSizeOptions(e.target);
                }
            });

            // Form validation and submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                const formData = new FormData(form);
                formData.append('_method', 'PUT');

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message and redirect
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = data.redirect;
                            });
                        } else {
                            // Show error message
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while updating the order. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Delete button functionality
            const deleteOrderBtn = document.getElementById('deleteOrderBtn');
            if (deleteOrderBtn) {
                deleteOrderBtn.addEventListener('click', function() {
                    confirmDelete('{{ $order->id }}', '{{ $order->order_id }}');
                });
            }

            // Initialize the delete modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteOrderModal'));

            window.confirmDelete = function(orderId, orderNumber) {
                document.getElementById('deleteOrderId').textContent = orderNumber;
                const deleteForm = document.getElementById('deleteOrderForm');
                deleteForm.action = `/orders/${orderId}`;
                deleteModal.show();
            }

            // Handle delete form submission
            document.getElementById('deleteOrderForm').addEventListener('submit', function(e) {
                e.preventDefault();

                fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        deleteModal.hide();

                        if (data.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message || 'Order has been deleted successfully.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = data.redirect ||
                                    '{{ route('orders-list') }}';
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to delete order.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        deleteModal.hide();
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the order. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            // Initialize size options for all existing items on page load
            document.querySelectorAll('.product-select').forEach(function(productSelect) {
                updateSizeOptions(productSelect);
            });
        });
    </script>
@endpush
