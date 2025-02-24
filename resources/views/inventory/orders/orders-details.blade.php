{{-- resources/views/inventory/orders/orders-details.blade.php --}}
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
            <div class="col-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Order Details</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('edit-order', $order->id) }}" class="btn btn-soft-primary btn-sm">
                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                Edit
                            </a>
                            <a href="{{ route('orders-list') }}" class="btn btn-light btn-sm">
                                <iconify-icon icon="solar:arrow-left-broken" class="align-middle fs-18"></iconify-icon>
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Order Status and Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <h5 class="mb-0 me-2">Order ID:</h5>
                                    <span class="badge bg-light text-dark fs-5">{{ $order->order_id }}</span>
                                </div>
                                <p class="mb-2"><strong>Date:</strong> {{ $order->formatted_date }}</p>
                                <p class="mb-2">
                                    <strong>Payment Status:</strong>
                                    <span class="badge {{ $order->payment_status_class }} px-2">
                                        {{ $order->payment_status }}
                                    </span>
                                </p>
                                <p class="mb-2">
                                    <strong>Order Status:</strong>
                                    <span class="badge {{ $order->order_status_class }} px-2">
                                        {{ $order->order_status }}
                                    </span>
                                </p>
                                <p class="mb-0"><strong>Priority:</strong> {{ $order->priority }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Customer Information</h5>
                                <p class="mb-2"><strong>Name:</strong> {{ $order->customer_name }}</p>
                                <p class="mb-2"><strong>Email:</strong> {{ $order->email }}</p>
                                <p class="mb-2"><strong>Phone:</strong> {{ $order->phone }}</p>
                                <p class="mb-0"><strong>Address:</strong> {{ $order->address }}</p>
                            </div>
                        </div>

                        <!-- Order Items Table -->
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->product->image)
                                                        <img src="{{ Storage::url($item->product->image) }}"
                                                            alt="{{ $item->product->name }}" class="rounded me-2"
                                                            style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">{{ $item->product->brand }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->size }}</td>
                                            <td>₦{{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end">₦{{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light-subtle">
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end">{{ $order->formatted_subtotal }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Tax (7.5%):</strong></td>
                                        <td class="text-end">{{ $order->formatted_tax }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end"><strong>{{ $order->formatted_total }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
