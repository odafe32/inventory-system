@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">
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

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            id="productForm">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Product Preview Card --}}
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <img id="product-preview-image"
                                src="{{ $product->image ? Storage::url($product->image) : '/assets/images/img.webp' }}"
                                alt="Product Preview" class="img-fluid rounded bg-light">
                            <div class="mt-3">
                                <h4 id="preview-name">{{ $product->name }}
                                    <span class="fs-14 text-muted ms-1">
                                        ({{ $product->category ? $product->category->title : 'No Category' }})
                                    </span>
                                </h4>
                                <h5 class="text-dark fw-medium mt-3">Price:</h5>
                                <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                    <span id="preview-original-price" class="text-muted text-decoration-line-through">
                                        ₦{{ number_format($product->price, 2) }}
                                    </span>
                                    <span id="preview-final-price">
                                        ₦{{ number_format($product->price * (1 - $product->discount / 100), 2) }}
                                    </span>
                                    <small id="preview-discount" class="text-muted">
                                        ({{ $product->discount }}% Off)
                                    </small>
                                </h4>
                                <div class="mt-3">
                                    <h5 class="text-dark fw-medium">Size:</h5>
                                    <div id="preview-sizes" class="d-flex gap-2 mt-2">
                                        @foreach (explode(',', $product->size) as $size)
                                            <button type="button"
                                                class="btn btn-sm btn-outline-dark">{{ $size }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light-subtle">
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary w-100">Update Product</button>
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
                            <h4 class="card-title">Update Product Photo</h4>
                        </div>
                        <div class="card-body">
                            <div class="dropzone" id="productImageUpload">
                                <div class="dz-message needsclick">
                                    <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                    <h3 class="mt-4">Drop new image here, or <span class="text-primary">click to
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
                                            placeholder="Product Name" value="{{ old('name', $product->name) }}" required>
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
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                            placeholder="Brand Name" value="{{ old('brand', $product->brand) }}"
                                            required>
                                        @error('brand')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="product-weight" class="form-label">Weight</label>
                                        <input type="text" name="weight" id="product-weight" class="form-control"
                                            placeholder="In grams" value="{{ old('weight', $product->weight) }}">
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
                                            <option value="Men"
                                                {{ old('gender', $product->gender) == 'Men' ? 'selected' : '' }}>Men
                                            </option>
                                            <option value="Women"
                                                {{ old('gender', $product->gender) == 'Women' ? 'selected' : '' }}>Women
                                            </option>
                                            <option value="Unisex"
                                                {{ old('gender', $product->gender) == 'Unisex' ? 'selected' : '' }}>Unisex
                                            </option>
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
                                        @php
                                            $currentSizes = explode(',', $product->size);
                                        @endphp
                                        @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="sizes[]"
                                                    value="{{ $size }}" id="size-{{ $size }}"
                                                    {{ in_array($size, old('sizes', $currentSizes)) ? 'checked' : '' }}>
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
                                            required>{{ old('description', $product->description) }}</textarea>
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
                                            value="{{ $product->tag_number }}" readonly disabled>
                                        <small class="text-muted">Auto-generated product tag</small>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="product-stock" class="form-label">Stock</label>
                                        <input type="number" name="stock" id="product-stock" class="form-control"
                                            placeholder="Quantity" value="{{ old('stock', $product->stock) }}" required
                                            min="0">
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
                                                placeholder="0.00" value="{{ old('price', $product->price) }}" required
                                                step="0.01" min="0">
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
                                                class="form-control" placeholder="0"
                                                value="{{ old('discount', $product->discount) }}" min="0"
                                                max="100">
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
                                                placeholder="0" value="{{ old('tax', $product->tax) }}" min="0">
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
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize form elements
            const form = document.getElementById('productForm');
            const previewImage = document.getElementById('product-preview-image');
            const previewName = document.getElementById('preview-name');
            const previewOriginalPrice = document.getElementById('preview-original-price');
            const previewFinalPrice = document.getElementById('preview-final-price');
            const previewDiscount = document.getElementById('preview-discount');
            const previewSizes = document.getElementById('preview-sizes');

            // Initialize dropzone
            let myDropzone = new Dropzone("#productImageUpload", {
                url: form.action,
                autoProcessQueue: false,
                uploadMultiple: false,
                maxFiles: 1,
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                init: function() {
                    this.on("addedfile", function(file) {
                        // Preview the image
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImage.src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }
            });

            // Live preview updates
            document.getElementById('product-name').addEventListener('input', function(e) {
                const category = document.querySelector('#product-categories option:checked').text;
                previewName.innerHTML =
                    `${e.target.value} <span class="fs-14 text-muted ms-1">(${category})</span>`;
            });

            document.getElementById('product-categories').addEventListener('change', function(e) {
                const productName = document.getElementById('product-name').value;
                const category = e.target.options[e.target.selectedIndex].text;
                previewName.innerHTML =
                    `${productName} <span class="fs-14 text-muted ms-1">(${category})</span>`;
            });

            // Price and discount calculations
            function updatePricePreview() {
                const price = parseFloat(document.getElementById('product-price').value) || 0;
                const discount = parseFloat(document.getElementById('product-discount').value) || 0;

                const discountedPrice = price * (1 - discount / 100);

                previewOriginalPrice.textContent = `₦${price.toFixed(2)}`;
                previewFinalPrice.textContent = `₦${discountedPrice.toFixed(2)}`;
                previewDiscount.textContent = `(${discount}% Off)`;

                // Hide original price if no discount
                previewOriginalPrice.style.display = discount > 0 ? 'inline' : 'none';
                previewDiscount.style.display = discount > 0 ? 'inline' : 'none';
            }

            document.getElementById('product-price').addEventListener('input', updatePricePreview);
            document.getElementById('product-discount').addEventListener('input', updatePricePreview);

            // Size preview
            function updateSizePreview() {
                previewSizes.innerHTML = '';
                const checkedSizes = document.querySelectorAll('input[name="sizes[]"]:checked');
                checkedSizes.forEach(size => {
                    const sizeBtn = document.createElement('button');
                    sizeBtn.type = 'button';
                    sizeBtn.className = 'btn btn-sm btn-outline-dark';
                    sizeBtn.textContent = size.value;
                    previewSizes.appendChild(sizeBtn);
                });
            }

            document.querySelectorAll('input[name="sizes[]"]').forEach(checkbox => {
                checkbox.addEventListener('change', updateSizePreview);
            });

            // Form validation and submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Basic validation
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Size validation
                const checkedSizes = document.querySelectorAll('input[name="sizes[]"]:checked');
                if (checkedSizes.length === 0) {
                    isValid = false;
                    document.querySelector('.size-error')?.remove();
                    const sizeError = document.createElement('div');
                    sizeError.className = 'text-danger mt-1 size-error';
                    sizeError.textContent = 'Please select at least one size';
                    document.querySelector('.size-section')?.appendChild(sizeError);
                }

                // Price validation
                const price = parseFloat(document.getElementById('product-price').value);
                if (price <= 0) {
                    isValid = false;
                    document.getElementById('product-price').classList.add('is-invalid');
                }

                if (isValid) {
                    // Show loading state
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
                    submitButton.disabled = true;

                    // Submit the form
                    const formData = new FormData(form);
                    if (myDropzone.files[0]) {
                        formData.append('image', myDropzone.files[0]);
                    }

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                const successAlert = document.getElementById('success-alert');
                                const successMessage = document.getElementById('success-message');
                                successMessage.textContent = data.message;
                                successAlert.style.display = 'block';
                                successAlert.classList.add('show');

                                // Redirect after a short delay
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 1500);
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            alert('Error: ' + error.message);
                            // Reset button state
                            submitButton.innerHTML = originalText;
                            submitButton.disabled = false;
                        });
                }
            });

            // Initialize the preview
            updatePricePreview();
            updateSizePreview();
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

        .alert {
            margin-bottom: 20px;
        }

        .fade {
            transition: opacity 0.15s linear;
        }

        .fade.show {
            opacity: 1;
        }
    </style>

@endsection
