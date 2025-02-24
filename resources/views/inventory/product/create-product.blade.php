@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">
        {{-- Show any validation errors --}}
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
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            <div class="row">
                {{-- Product Preview Card --}}
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <img id="product-preview-image" src="/assets/images/img.webp" alt="Product Preview"
                                class="img-fluid rounded bg-light">
                            <div class="mt-3">
                                <h4 id="preview-name">Product Name Preview <span
                                        class="fs-14 text-muted ms-1">(Category)</span></h4>
                                <h5 class="text-dark fw-medium mt-3">Price:</h5>
                                <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                    <span id="preview-original-price"
                                        class="text-muted text-decoration-line-through">NGN0.00</span>
                                    <span id="preview-final-price">$0.00</span>
                                    <small id="preview-discount" class="text-muted">(0% Off)</small>
                                </h4>
                                <div class="mt-3">
                                    <h5 class="text-dark fw-medium">Size:</h5>
                                    <div id="preview-sizes" class="d-flex gap-2 mt-2">
                                        <!-- Sizes will be populated via JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light-subtle">
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary w-100">Create Product</button>
                                </div>
                                <div class="col-lg-6">
                                    <a href="{{ route('product-list') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8">
                    {{-- Product Image Upload --}}
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Product Photo</h4>
                        </div>
                        <div class="card-body">
                            <div class="dropzone" id="productImageUpload">
                                <div class="dz-message needsclick">
                                    <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                    <h3 class="mt-4">Drop your images here, or <span class="text-primary">click to
                                            browse</span></h3>
                                    <span class="text-muted fs-13">
                                        1600 x 1200 (4:3) recommended. PNG, JPG and GIF files are allowed
                                    </span>
                                </div>
                            </div>
                            @error('image')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Product Information --}}
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Product Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="product-name" class="form-label">Product Name</label>
                                        <input type="text" name="name" id="product-name" class="form-control"
                                            placeholder="Product Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="product-categories" class="form-label">Product Categories</label>
                                        <select class="form-control" name="category_id" id="product-categories" required>
                                            <option value="">Choose a category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="product-brand" class="form-label">Brand</label>
                                        <input type="text" name="brand" id="product-brand" class="form-control"
                                            placeholder="Brand Name" value="{{ old('brand') }}" required>
                                        @error('brand')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="product-weight" class="form-label">Weight</label>
                                        <input type="text" name="weight" id="product-weight" class="form-control"
                                            placeholder="In grams" value="{{ old('weight') }}">
                                        @error('weight')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-control" name="gender" id="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Men" {{ old('gender') == 'Men' ? 'selected' : '' }}>Men
                                            </option>
                                            <option value="Women" {{ old('gender') == 'Women' ? 'selected' : '' }}>Women
                                            </option>
                                            <option value="Unisex" {{ old('gender') == 'Unisex' ? 'selected' : '' }}>
                                                Unisex</option>
                                        </select>
                                        @error('gender')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Size Selection --}}
                            <div class="row mb-4">
                                <div class="col-lg-12">
                                    <h5 class="text-dark fw-medium mb-3">Size:</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="sizes[]"
                                                    value="{{ $size }}" id="size-{{ $size }}"
                                                    {{ is_array(old('sizes')) && in_array($size, old('sizes')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="size-{{ $size }}">
                                                    {{ $size }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('sizes')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" name="description" id="description" rows="7" placeholder="Product description"
                                            required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="product-id" class="form-label">Tag Number</label>
                                        <input type="text" id="product-id" class="form-control"
                                            value="{{ $generated_tag }}" readonly disabled>
                                        <small class="text-muted">Auto-generated product tag</small>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="product-stock" class="form-label">Stock</label>
                                        <input type="number" name="stock" id="product-stock" class="form-control"
                                            placeholder="Quantity" value="{{ old('stock') }}" required min="0">
                                        @error('stock')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Pricing Details --}}
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Pricing Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="product-price" class="form-label">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₦</span>
                                            <input type="number" name="price" id="product-price" class="form-control"
                                                placeholder="0.00" value="{{ old('price') }}" required step="0.01"
                                                min="0">
                                        </div>
                                        @error('price')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="product-discount" class="form-label">Discount (%)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bxs-discount"></i></span>
                                            <input type="number" name="discount" id="product-discount"
                                                class="form-control" placeholder="0" value="{{ old('discount') }}"
                                                min="0" max="100">
                                        </div>
                                        @error('discount')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="product-tax" class="form-label">Tax (%)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bxs-file-txt"></i></span>
                                            <input type="number" name="tax" id="product-tax" class="form-control"
                                                placeholder="0" value="{{ old('tax') }}" min="0">
                                        </div>
                                        @error('tax')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <script>
        // Update the form submit handler in your create-orders.blade.php
        document.addEventListener('DOMContentLoaded', function() {
            const orderForm = document.getElementById('orderForm');
            const successAlert = document.getElementById('success-alert');
            const successMessage = document.getElementById('success-message');

            if (orderForm) {
                orderForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Basic validation
                    const products = document.querySelectorAll('.product-select');
                    let hasSelectedProduct = false;

                    products.forEach(select => {
                        if (select.value) {
                            hasSelectedProduct = true;
                        }
                    });

                    if (!hasSelectedProduct) {
                        alert('Please select at least one product.');
                        return;
                    }

                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

                    // Submit form
                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw response;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                if (successAlert && successMessage) {
                                    successMessage.textContent = data.message;
                                    successAlert.style.display = 'block';
                                    successAlert.classList.add('show');
                                }

                                // Redirect after delay
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 1500);
                            } else {
                                throw new Error(data.message || 'An error occurred');
                            }
                        })
                        .catch(async error => {
                            let errorMessage = 'An error occurred while processing your request.';

                            if (error instanceof Response) {
                                try {
                                    const errorData = await error.json();
                                    errorMessage = errorData.message || errorMessage;
                                } catch (e) {
                                    console.error('Error parsing error response:', e);
                                }
                            } else if (error instanceof Error) {
                                errorMessage = error.message;
                            }

                            alert('Error: ' + errorMessage);

                            // Reset button state
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                });
            }
        });
    </script>

    <style>
        .dropzone {
            border: 2px dashed #ccc;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropzone:hover {
            border-color: #666;
        }

        .dropzone.border-danger {
            border-color: #dc3545;
        }

        .preview-image {
            max-width: 200px;
            margin-top: 10px;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .size-button {
            min-width: 40px;
        }

        /* Add this to your existing styles */
        .alert {
            margin-bottom: 20px;
        }

        .fade {
            transition: opacity 0.15s linear;
        }

        .fade.show {
            opacity: 1;
        }

        /* Add to your existing styles */
        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 0.2em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border .75s linear infinite;
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        .btn:disabled {
            cursor: not-allowed;
            opacity: 0.65;
        }

        /* Alert styles */
        .alert {
            transition: opacity 0.15s linear;
        }

        .alert.fade {
            opacity: 0;
        }

        .alert.fade.show {
            opacity: 1;
        }
    </style>

@endsection
