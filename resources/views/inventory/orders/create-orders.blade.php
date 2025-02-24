@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {!! session('error') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div id="success-alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
            <span id="success-message"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
            @csrf
            <div class="row">
                <!-- Order Summary Card -->
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-dark fw-medium mb-2">Order ID:</label>
                                <div class="bg-light-subtle p-2 rounded">
                                    <span id="displayOrderId">#{{ rand(100000, 999999) }}/80</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-dark fw-medium mb-2">Total Items:</label>
                                <div class="bg-light-subtle p-2 rounded">
                                    <span id="totalItems">0</span> products
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-dark fw-medium mb-2">Total Amount:</label>
                                <div class="bg-light-subtle p-2 rounded">
                                    <span id="displayTotal">₦0.00</span>
                                </div>
                            </div>

                            <div class="alert alert-info mb-3">
                                <small>
                                    <i class="bx bx-info-circle me-1"></i>
                                    Tax rate: 7.5% will be applied to the subtotal
                                </small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary w-100">Create Order</button>
                            <a href="{{ route('orders') }}" class="btn btn-light w-100 mt-2">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8">
                    <!-- Customer Information -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">Customer Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Name</label>
                                        <input type="text" class="form-control" name="customer_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" class="form-control" name="phone" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Priority</label>
                                        <select class="form-control" name="priority" required>
                                            <option value="">Select Priority</option>
                                            <option value="Normal">Normal</option>
                                            <option value="High">High</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Delivery Address</label>
                                        <textarea class="form-control" name="address" rows="2" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">Order Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Payment Status</label>
                                        <select class="form-control" name="payment_status" required>
                                            <option value="">Select Status</option>
                                            <option value="Paid">Paid</option>
                                            <option value="Unpaid">Unpaid</option>
                                            <option value="Refund">Refund</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Order Status</label>
                                        <select class="form-control" name="order_status" required>
                                            <option value="">Select Status</option>
                                            <option value="Draft">Draft</option>
                                            <option value="Packaging">Packaging</option>
                                            <option value="Shipping">Shipping</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Canceled">Canceled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Products</h4>
                            <button type="button" class="btn btn-primary btn-sm" id="addProductRow">
                                <i class="bx bx-plus"></i> Add Product
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="productsTable">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Size</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="product-row">
                                            <!-- Product select -->
                                            <td>
                                                <select class="form-control product-select" name="products[]" required>
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        @php
                                                            // Debug output
                                                            \Log::info('Product sizes:', [
                                                                'product' => $product->name,
                                                                'sizes' => $product->size,
                                                            ]);

                                                            // Ensure sizes are properly formatted
                                                            $sizes = $product->size ? explode(',', $product->size) : [];
                                                            $sizes = array_map('trim', $sizes);
                                                            $sizes = array_filter($sizes);
                                                            $sizesJson = json_encode($sizes);
                                                        @endphp
                                                        <option value="{{ $product->id }}"
                                                            data-price="{{ number_format($product->price, 2) }}"
                                                            data-sizes='{{ $product->size }}'
                                                            data-debug-sizes="{{ $sizesJson }}">
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <!-- Size select -->
                                            <td>
                                                <select class="form-control size-select" name="sizes[]" required>
                                                    <option value="">Select Size</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity" name="quantities[]"
                                                    min="1" value="1" required>
                                            </td>
                                            <td class="price">₦0.00</td>
                                            <td class="total">₦0.00</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end">Subtotal:</td>
                                            <td class="subtotal">₦0.00</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">Tax (7.5%):</td>
                                            <td class="tax">₦0.00</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Total:</td>
                                            <td class="grand-total fw-bold">₦0.00</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize product select handlers
            initializeProductSelects();

            // Add product row button handler
            document.getElementById('addProductRow').addEventListener('click', function() {
                const tbody = document.querySelector('#productsTable tbody');
                const newRow = tbody.querySelector('.product-row').cloneNode(true);

                // Reset the new row's values
                newRow.querySelector('.product-select').value = '';
                newRow.querySelector('.size-select').innerHTML = '<option value="">Select Size</option>';
                newRow.querySelector('.quantity').value = '1';
                newRow.querySelector('.price').textContent = '₦0.00';
                newRow.querySelector('.total').textContent = '₦0.00';

                tbody.appendChild(newRow);
                initializeProductSelects();
                updateTotals();
            });

            // Function to initialize product select handlers
            function initializeProductSelects() {
                document.querySelectorAll('.product-row').forEach(row => {
                    const productSelect = row.querySelector('.product-select');
                    const sizeSelect = row.querySelector('.size-select');
                    const quantityInput = row.querySelector('.quantity');
                    const removeButton = row.querySelector('.remove-row');

                    // Product selection change handler
                    productSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        console.log('Selected product:', selectedOption.text);
                        console.log('Raw sizes data:', selectedOption.dataset.sizes);

                        if (this.value) {
                            // Parse sizes - handle both string and JSON formats
                            let sizes = [];
                            try {
                                // Try parsing as JSON first
                                const sizesData = selectedOption.dataset.sizes;
                                if (sizesData.startsWith('[')) {
                                    sizes = JSON.parse(sizesData);
                                } else {
                                    // If not JSON, split by comma
                                    sizes = sizesData.split(',').map(size => size.trim()).filter(
                                        Boolean);
                                }
                            } catch (e) {
                                console.error('Error parsing sizes:', e);
                                sizes = selectedOption.dataset.sizes.split(',').map(size => size
                                    .trim()).filter(Boolean);
                            }

                            console.log('Parsed sizes:', sizes);

                            // Update size dropdown
                            sizeSelect.innerHTML = '<option value="">Select Size</option>';
                            sizes.forEach(size => {
                                if (size) {
                                    sizeSelect.innerHTML +=
                                        `<option value="${size}">${size}</option>`;
                                }
                            });

                            // Update price
                            const price = parseFloat(selectedOption.dataset.price) || 0;
                            row.querySelector('.price').textContent = `₦${price.toFixed(2)}`;
                        } else {
                            // Reset size select if no product selected
                            sizeSelect.innerHTML = '<option value="">Select Size</option>';
                            row.querySelector('.price').textContent = '₦0.00';
                        }

                        updateRowTotal(row);
                        updateTotals();
                    });

                    // Quantity change handler
                    quantityInput.addEventListener('change', function() {
                        updateRowTotal(row);
                        updateTotals();
                    });

                    // Remove row handler
                    removeButton.addEventListener('click', function() {
                        if (document.querySelectorAll('.product-row').length > 1) {
                            row.remove();
                            updateTotals();
                        }
                    });
                });
            }

            // Function to update a single row's total
            function updateRowTotal(row) {
                const price = parseFloat(row.querySelector('.price').textContent.replace('₦', '')) || 0;
                const quantity = parseInt(row.querySelector('.quantity').value) || 0;
                const total = price * quantity;
                row.querySelector('.total').textContent = `₦${total.toFixed(2)}`;
            }

            // Function to update all totals
            function updateTotals() {
                let subtotal = 0;
                let totalItems = 0;

                document.querySelectorAll('.product-row').forEach(row => {
                    const quantity = parseInt(row.querySelector('.quantity').value) || 0;
                    const price = parseFloat(row.querySelector('.price').textContent.replace('₦', '')) || 0;
                    subtotal += price * quantity;
                    totalItems += quantity;
                });

                const tax = subtotal * 0.075;
                const total = subtotal + tax;

                // Update totals in the table
                document.querySelector('.subtotal').textContent = `₦${subtotal.toFixed(2)}`;
                document.querySelector('.tax').textContent = `₦${tax.toFixed(2)}`;
                document.querySelector('.grand-total').textContent = `₦${total.toFixed(2)}`;

                // Update summary card
                document.getElementById('totalItems').textContent = totalItems;
                document.getElementById('displayTotal').textContent = `₦${total.toFixed(2)}`;
            }
        });
    </script>

    <style>
        /* Custom styles for create order page */
        .product-row .form-control {
            margin: 0;
        }

        .price,
        .total,
        .subtotal,
        .tax,
        .grand-total {
            font-family: monospace;
        }

        .table> :not(caption)>*>* {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .remove-row {
            padding: 0.25rem 0.5rem;
        }

        .card {
            margin-bottom: 1.5rem;
        }

        .alert {
            margin-bottom: 1.5rem;
        }

        /* Style for order summary card */
        #displayOrderId,
        #totalItems,
        #displayTotal {
            font-weight: 500;
            color: var(--bs-primary);
        }

        .bg-light-subtle {
            background-color: var(--bs-light-bg-subtle);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                margin-bottom: 1rem;
            }

            .card-footer {
                padding: 1rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endsection
