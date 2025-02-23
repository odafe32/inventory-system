@extends('layout.application_layout')
@section('content')
    <!-- ==================================================== -->
    <!-- Start right Content here -->
    <!-- ==================================================== -->


    <!-- Start Container Fluid -->
    <div class="container-fluid">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">All Product List</h4>

                        <a href="{{ url('/create-product') }}" class="btn btn-sm btn-primary">
                            Add Product
                        </a>

                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                This Month
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="#!" class="dropdown-item">Download</a>
                                <!-- item-->
                                <a href="#!" class="dropdown-item">Export</a>
                                <!-- item-->
                                <a href="#!" class="dropdown-item">Import</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">

                                        </th>
                                        <th>Product Name & Size</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Category</th>
                                        <th>Size</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td>
                                                <div class="form-check ms-1">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="product-{{ $product->id }}">
                                                    <label class="form-check-label"
                                                        for="product-{{ $product->id }}">&nbsp;</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                        @if ($product->image)
                                                            <img src="{{ Storage::url($product->image) }}"
                                                                alt="{{ $product->name }}" class="avatar-md">
                                                        @else
                                                            <img src="assets/images/product/p-1.png" alt="Default"
                                                                class="avatar-md">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('product-details', ['id' => $product->id]) }}"
                                                            class="text-dark fw-medium fs-15">
                                                            {{ $product->name }}
                                                        </a>
                                                        <p class="text-muted mb-0 mt-1 fs-13">Tag:
                                                            {{ $product->tag_number }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>₦{{ number_format($product->price, 2) }}</td>
                                            <td>
                                                <p class="mb-1 text-muted">
                                                    <span class="text-dark fw-medium">{{ $product->stock }} Item</span>
                                                    Left
                                                </p>
                                                @if ($product->discount > 0)
                                                    <p class="mb-0 text-muted">{{ $product->discount }}% Off</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->category)
                                                    {{ $product->category->title }}
                                                @else
                                                    <span class="text-muted">No category</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $sizes = explode(',', $product->size);
                                                @endphp
                                                @foreach ($sizes as $size)
                                                    <span
                                                        class="badge p-1 bg-light text-dark fs-12 me-1">{{ $size }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('product-details', ['id' => $product->id]) }}"
                                                        class="btn btn-light btn-sm">
                                                        <iconify-icon icon="solar:eye-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <a href="{{ route('edit-product', ['id' => $product->id]) }}"
                                                        class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <button type="button" class="btn btn-soft-danger btn-sm delete-product"
                                                        data-id="{{ $product->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <iconify-icon icon="solar:box-broken"
                                                        style="font-size: 48px;"></iconify-icon>
                                                    <p class="mt-2">No products found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                        <!-- Add Delete Modal -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this product? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <form id="deleteForm" action="" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Update the pagination section -->
                    <div class="card-footer border-top">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>


                </div>


                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Handle delete button click
                        document.querySelectorAll('.delete-product').forEach(button => {
                            button.addEventListener('click', function() {
                                const productId = this.dataset.id;
                                const deleteForm = document.getElementById('deleteForm');
                                deleteForm.action = `/products/${productId}`;
                            });
                        });
                    });
                </script>
            @endsection
