@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">
        <!-- Success and Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Invoice #{{ $invoice->invoice_number }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Top Section -->
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Company Logo</label>
                                        <div class="mb-2">
                                            @if ($invoice->logo)
                                                <img src="{{ asset('storage/' . $invoice->logo) }}" class="img-thumbnail"
                                                    style="max-height: 80px;">
                                            @endif
                                        </div>
                                        <input type="file" name="logo" id="logo"
                                            class="form-control @error('logo') is-invalid @enderror">
                                        <small class="text-muted">Leave empty to keep the current logo</small>
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="sender_name" class="form-label">Sender Name</label>
                                        <input type="text" name="sender_name" id="sender_name"
                                            class="form-control @error('sender_name') is-invalid @enderror"
                                            value="{{ old('sender_name', $invoice->sender_name) }}">
                                        @error('sender_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="sender_address" class="form-label">Sender Address</label>
                                        <textarea name="sender_address" id="sender_address" class="form-control @error('sender_address') is-invalid @enderror"
                                            rows="3">{{ old('sender_address', $invoice->sender_address) }}</textarea>
                                        @error('sender_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="sender_phone" class="form-label">Sender Phone</label>
                                        <input type="text" name="sender_phone" id="sender_phone"
                                            class="form-control @error('sender_phone') is-invalid @enderror"
                                            value="{{ old('sender_phone', $invoice->sender_phone) }}">
                                        @error('sender_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="invoice_number" class="form-label">Invoice Number</label>
                                        <input type="text" name="invoice_number" id="invoice_number"
                                            class="form-control bg-light" value="{{ $invoice->invoice_number }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="issue_date" class="form-label">Issue Date</label>
                                        <input type="date" name="issue_date" id="issue_date"
                                            class="form-control @error('issue_date') is-invalid @enderror"
                                            value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}">
                                        @error('issue_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">Due Date</label>
                                        <input type="date" name="due_date" id="due_date"
                                            class="form-control @error('due_date') is-invalid @enderror"
                                            value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status"
                                            class="form-select @error('status') is-invalid @enderror">
                                            <option value="Paid" {{ $invoice->status == 'Paid' ? 'selected' : '' }}>Paid
                                            </option>
                                            <option value="Pending" {{ $invoice->status == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="Cancel" {{ $invoice->status == 'Cancel' ? 'selected' : '' }}>
                                                Cancel</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Client and Business Information -->
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <h5 class="mb-3">Your Business Information</h5>
                                    <div class="mb-3">
                                        <label for="issue_from" class="form-label">Business Name</label>
                                        <input type="text" name="issue_from" id="issue_from"
                                            class="form-control @error('issue_from') is-invalid @enderror"
                                            value="{{ old('issue_from', $invoice->issue_from) }}">
                                        @error('issue_from')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="issue_from_address" class="form-label">Business Address</label>
                                        <textarea name="issue_from_address" id="issue_from_address"
                                            class="form-control @error('issue_from_address') is-invalid @enderror" rows="3">{{ old('issue_from_address', $invoice->issue_from_address) }}</textarea>
                                        @error('issue_from_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="issue_from_phone" class="form-label">Business Phone</label>
                                        <input type="text" name="issue_from_phone" id="issue_from_phone"
                                            class="form-control @error('issue_from_phone') is-invalid @enderror"
                                            value="{{ old('issue_from_phone', $invoice->issue_from_phone) }}">
                                        @error('issue_from_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="issue_from_email" class="form-label">Business Email</label>
                                        <input type="email" name="issue_from_email" id="issue_from_email"
                                            class="form-control @error('issue_from_email') is-invalid @enderror"
                                            value="{{ old('issue_from_email', $invoice->issue_from_email) }}">
                                        @error('issue_from_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h5 class="mb-3">Client Information</h5>
                                    <div class="mb-3">
                                        <label for="issue_for" class="form-label">Client Name</label>
                                        <input type="text" name="issue_for" id="issue_for"
                                            class="form-control @error('issue_for') is-invalid @enderror"
                                            value="{{ old('issue_for', $invoice->issue_for) }}">
                                        @error('issue_for')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="issue_for_address" class="form-label">Client Address</label>
                                        <textarea name="issue_for_address" id="issue_for_address"
                                            class="form-control @error('issue_for_address') is-invalid @enderror" rows="3">{{ old('issue_for_address', $invoice->issue_for_address) }}</textarea>
                                        @error('issue_for_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="issue_for_phone" class="form-label">Client Phone</label>
                                        <input type="text" name="issue_for_phone" id="issue_for_phone"
                                            class="form-control @error('issue_for_phone') is-invalid @enderror"
                                            value="{{ old('issue_for_phone', $invoice->issue_for_phone) }}">
                                        @error('issue_for_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="issue_for_email" class="form-label">Client Email</label>
                                        <input type="email" name="issue_for_email" id="issue_for_email"
                                            class="form-control @error('issue_for_email') is-invalid @enderror"
                                            value="{{ old('issue_for_email', $invoice->issue_for_email) }}">
                                        @error('issue_for_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Products Section -->
                            <h5 class="mb-3">Products</h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered" id="productTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th width="120">Quantity</th>
                                            <th width="150">Price (₦)</th>
                                            <th width="150">Tax (₦)</th>
                                            <th width="150">Total (₦)</th>
                                            <th width="80">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productTableBody">
                                        @foreach ($invoice->items as $index => $item)
                                            <tr class="product-row">
                                                <td>
                                                    <input type="hidden" name="products[{{ $index }}][id]"
                                                        value="{{ $item->id }}">
                                                    <input type="text" name="products[{{ $index }}][name]"
                                                        class="form-control mb-2 product-name"
                                                        value="{{ $item->product_name }}" required>
                                                    <input type="text" name="products[{{ $index }}][size]"
                                                        class="form-control product-size"
                                                        value="{{ $item->product_size }}" placeholder="Size (optional)">
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][quantity]"
                                                        class="form-control product-quantity"
                                                        value="{{ $item->quantity }}" min="1">
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][price]"
                                                        class="form-control product-price" value="{{ $item->price }}"
                                                        min="0" step="0.01">
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][tax]"
                                                        class="form-control product-tax" value="{{ $item->tax }}"
                                                        min="0" step="0.01">
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][total]"
                                                        class="form-control product-total" value="{{ $item->total }}"
                                                        readonly>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger remove-product"
                                                        {{ count($invoice->items) <= 1 ? 'style=display:none;' : '' }}>
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-4 text-end">
                                <button type="button" class="btn btn-secondary" id="clearProducts">Clear All</button>
                                <button type="button" class="btn btn-info" id="addProduct">Add Product</button>
                            </div>

                            <!-- Totals Section -->
                            <div class="row">
                                <div class="col-md-6 ms-auto">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="mb-3 row">
                                                <label for="subtotal" class="col-sm-5 col-form-label">Subtotal:</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">₦</span>
                                                        <input type="number" name="subtotal" id="subtotal"
                                                            class="form-control" value="{{ $invoice->subtotal }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="discount" class="col-sm-5 col-form-label">Discount:</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">₦</span>
                                                        <input type="number" name="discount" id="discount"
                                                            class="form-control" value="{{ $invoice->discount }}"
                                                            min="0" step="0.01">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="tax" class="col-sm-5 col-form-label">Tax (7.5%):</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">₦</span>
                                                        <input type="number" name="tax" id="tax"
                                                            class="form-control" value="{{ $invoice->tax }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="grand_total" class="col-sm-5 col-form-label fw-bold">Grand
                                                    Total:</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-text">₦</span>
                                                        <input type="number" name="grand_total" id="grand_total"
                                                            class="form-control fw-bold"
                                                            value="{{ $invoice->grand_total }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="amount" id="amount"
                                                value="{{ $invoice->amount }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Update Invoice</button>
                                <a href="{{ route('invoices-details', $invoice->id) }}"
                                    class="btn btn-light btn-lg px-5 ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let productCounter = {{ count($invoice->items) }};

                // Calculate product total
                function calculateProductTotal(row) {
                    const quantity = parseFloat(row.querySelector('.product-quantity').value) || 0;
                    const price = parseFloat(row.querySelector('.product-price').value) || 0;
                    const tax = parseFloat(row.querySelector('.product-tax').value) || 0;

                    const total = (quantity * price) + tax;
                    row.querySelector('.product-total').value = total.toFixed(2);

                    updateTotals();
                }

                // Update all totals
                function updateTotals() {
                    let subtotal = 0;
                    const rows = document.querySelectorAll('.product-row');

                    rows.forEach(row => {
                        subtotal += parseFloat(row.querySelector('.product-total').value) || 0;
                    });

                    document.getElementById('subtotal').value = subtotal.toFixed(2);

                    const discount = parseFloat(document.getElementById('discount').value) || 0;
                    const taxRate = 0.075; // 7.5%
                    const taxAmount = (subtotal - discount) * taxRate;

                    document.getElementById('tax').value = taxAmount.toFixed(2);
                    const grandTotal = (subtotal - discount + taxAmount).toFixed(2);
                    document.getElementById('grand_total').value = grandTotal;
                    document.getElementById('amount').value = grandTotal;
                }

                // Add event listeners for quantity, price, and tax changes
                document.addEventListener('input', function(e) {
                    if (e.target.classList.contains('product-quantity') ||
                        e.target.classList.contains('product-price') ||
                        e.target.classList.contains('product-tax')) {
                        calculateProductTotal(e.target.closest('.product-row'));
                    }

                    if (e.target.id === 'discount') {
                        updateTotals();
                    }
                });

                // Add product button
                document.getElementById('addProduct').addEventListener('click', function() {
                    const newRow = `
                <tr class="product-row">
                    <td>
                        <input type="text" name="products[${productCounter}][name]" class="form-control mb-2 product-name" placeholder="Product Name" required>
                        <input type="text" name="products[${productCounter}][size]" class="form-control product-size" placeholder="Size (optional)">
                    </td>
                    <td>
                        <input type="number" name="products[${productCounter}][quantity]" class="form-control product-quantity" value="1" min="1">
                    </td>
                    <td>
                        <input type="number" name="products[${productCounter}][price]" class="form-control product-price" value="0" min="0" step="0.01">
                    </td>
                    <td>
                        <input type="number" name="products[${productCounter}][tax]" class="form-control product-tax" value="0" min="0" step="0.01">
                    </td>
                    <td>
                        <input type="number" name="products[${productCounter}][total]" class="form-control product-total" value="0" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-product">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>
                `;

                    document.getElementById('productTableBody').insertAdjacentHTML('beforeend', newRow);
                    productCounter++;

                    // Show all remove buttons if more than one product
                    if (document.querySelectorAll('.product-row').length > 1) {
                        document.querySelectorAll('.remove-product').forEach(btn => {
                            btn.style.display = 'inline-block';
                        });
                    }
                });

                // Remove product
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-product')) {
                        const button = e.target.closest('.remove-product');
                        const row = button.closest('.product-row');
                        row.remove();

                        // Hide remove buttons if only one product remains
                        if (document.querySelectorAll('.product-row').length <= 1) {
                            document.querySelectorAll('.remove-product').forEach(btn => {
                                btn.style.display = 'none';
                            });
                        }

                        updateTotals();
                    }
                });

                // Clear products
                document.getElementById('clearProducts').addEventListener('click', function() {
                    if (confirm('Are you sure you want to clear all products? This action cannot be undone.')) {
                        const firstRow = document.querySelector('.product-row');

                        // Clear inputs in first row
                        firstRow.querySelector('.product-name').value = '';
                        firstRow.querySelector('.product-size').value = '';
                        firstRow.querySelector('.product-quantity').value = '1';
                        firstRow.querySelector('.product-price').value = '0';
                        firstRow.querySelector('.product-tax').value = '0';
                        firstRow.querySelector('.product-total').value = '0';

                        // Remove all rows except first
                        const rows = document.querySelectorAll('.product-row');
                        for (let i = 1; i < rows.length; i++) {
                            rows[i].remove();
                        }

                        // Hide remove button on first row
                        firstRow.querySelector('.remove-product').style.display = 'none';

                        updateTotals();
                    }
                });

                // Initialize calculations
                updateTotals();
            });
        </script>
    @endpush
@endsection
