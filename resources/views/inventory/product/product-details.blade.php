{{-- resources/views/inventory/product/product-details.blade.php --}}
@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Product Image -->
                        <div id="carouselExampleFade" class="carousel slide carousel-fade pointer-event">
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    @if ($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                            class="img-fluid bg-light rounded">
                                    @else
                                        <img src="/assets/images/product/p-1.png" alt="Default Image"
                                            class="img-fluid bg-light rounded">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <div class="row g-2">
                            <div class="col-lg-2">
                                <button type="button"
                                    class="btn btn-soft-danger d-inline-flex align-items-center justify-content-center fs-20 rounded w-100">
                                    <iconify-icon icon="solar:heart-broken"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="badge bg-success text-light fs-14 py-1 px-2">
                            {{ $product->category ? $product->category->title : 'Uncategorized' }}
                        </h4>
                        <p class="mb-1">
                            <span class="fs-24 text-dark fw-medium">{{ $product->name }}</span>
                        </p>

                        <!-- Rating section if you implement it later -->
                        <div class="d-flex gap-2 align-items-center">
                            <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                @for ($i = 1; $i <= 5; $i++)
                                    <li><i class="bx bxs-star"></i></li>
                                @endfor
                            </ul>


                        </div>

                        <!-- Price -->
                        <h2 class="fw-medium my-3">
                            ₦{{ number_format($product->price * (1 - $product->discount / 100), 2) }}
                            @if ($product->discount > 0)
                                <span
                                    class="fs-16 text-decoration-line-through">₦{{ number_format($product->price, 2) }}</span>
                                <small class="text-danger ms-2">({{ $product->discount }}% Off)</small>
                            @endif
                        </h2>

                        <!-- Size -->
                        <div class="row align-items-center g-2 mt-3">
                            <div class="col-lg-3">
                                <div class="">
                                    <h5 class="text-dark fw-medium">Available Sizes</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach (explode(',', $product->size) as $size)
                                            <span
                                                class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center">
                                                {{ $size }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stock -->
                        <div class="quantity mt-4">
                            <h4 class="text-dark fw-medium mt-3">Stock Available:</h4>
                            <div class="mt-2">
                                <span class="badge bg-primary">{{ $product->stock }} Items</span>
                            </div>
                        </div>

                        <!-- Status and Info -->
                        <ul class="d-flex flex-column gap-2 list-unstyled fs-15 my-3">
                            <li>
                                <i class="bx bx-check text-success"></i>
                                {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                            </li>
                            @if ($product->discount > 0)
                                <li>
                                    <i class="bx bx-check text-success"></i>
                                    Sales {{ $product->discount }}% Off
                                </li>
                            @endif
                        </ul>

                        <!-- Description -->
                        <h4 class="text-dark fw-medium">Description:</h4>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Product Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <ul class="d-flex flex-column gap-2 list-unstyled fs-14 text-muted mb-0">
                                <li><span class="fw-medium text-dark">Product Tag</span><span
                                        class="mx-2">:</span>{{ $product->tag_number }}</li>
                                <li><span class="fw-medium text-dark">Brand</span><span
                                        class="mx-2">:</span>{{ $product->brand }}</li>
                                @if ($product->weight)
                                    <li><span class="fw-medium text-dark">Weight</span><span
                                            class="mx-2">:</span>{{ $product->weight }} grams</li>
                                @endif
                                <li><span class="fw-medium text-dark">Gender</span><span
                                        class="mx-2">:</span>{{ $product->gender }}</li>
                                <li><span class="fw-medium text-dark">Category</span><span
                                        class="mx-2">:</span>{{ $product->category ? $product->category->title : 'Uncategorized' }}
                                </li>
                                <li><span class="fw-medium text-dark">Created Date</span><span
                                        class="mx-2">:</span>{{ $product->created_at->format('d M Y') }}</li>
                                <li><span class="fw-medium text-dark">Last Updated</span><span
                                        class="mx-2">:</span>{{ $product->updated_at->format('d M Y') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-light-subtle">
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                        <iconify-icon icon="solar:ticket-bold-duotone"
                                            class="fs-35 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="text-dark fw-medium fs-16 mb-1">Special discounts</p>
                                        <p class="mb-0">For bulk orders</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                        <iconify-icon icon="solar:gift-bold-duotone"
                                            class="fs-35 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="text-dark fw-medium fs-16 mb-1">Free gift wrapping</p>
                                        <p class="mb-0">On request</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar bg-light d-flex align-items-center justify-content-center rounded">
                                        <iconify-icon icon="solar:headphones-round-sound-bold-duotone"
                                            class="fs-35 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="text-dark fw-medium fs-16 mb-1">Customer Service</p>
                                        <p class="mb-0">24/7 support</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
