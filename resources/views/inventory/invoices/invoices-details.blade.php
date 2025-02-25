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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Invoice #{{ $invoice->invoice_number }}</h4>
                        <div>
                            <a href="{{ route('edit-invoices', $invoice->id) }}" class="btn btn-primary">
                                <i class="bx bx-edit"></i> Edit
                            </a>
                            <a href="{{ route('invoices') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Back to List
                            </a>
                            <button onclick="window.print()" class="btn btn-info">
                                <i class="bx bx-printer"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="printableArea">
                        <!-- Invoice Header -->
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                @if ($invoice->logo)
                                    <img src="{{ asset('storage/' . $invoice->logo) }}" alt="Company Logo"
                                        style="max-height: 80px;">
                                @endif
                                <h3 class="mt-2">{{ $invoice->sender_name }}</h3>
                                <p>{!! nl2br(e($invoice->sender_address)) !!}</p>
                                <p>{{ $invoice->sender_phone }}</p>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <h1 class="text-primary">INVOICE</h1>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-sm-end mb-2">
                                        <div class="me-3 fw-bold">Invoice Number:</div>
                                        <div>{{ $invoice->invoice_number }}</div>
                                    </div>
                                    <div class="d-flex justify-content-sm-end mb-2">
                                        <div class="me-3 fw-bold">Issue Date:</div>
                                        <div>{{ $invoice->issue_date->format('M d, Y') }}</div>
                                    </div>
                                    <div class="d-flex justify-content-sm-end mb-2">
                                        <div class="me-3 fw-bold">Due Date:</div>
                                        <div>{{ $invoice->due_date->format('M d, Y') }}</div>
                                    </div>
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="me-3 fw-bold">Status:</div>
                                        <div>
                                            @if ($invoice->status == 'Paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($invoice->status == 'Pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Information -->
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <h5 class="mb-3">From:</h5>
                                <div class="border p-3 rounded bg-light">
                                    <h6>{{ $invoice->issue_from }}</h6>
                                    <p class="mb-1">{!! nl2br(e($invoice->issue_from_address)) !!}</p>
                                    <p class="mb-1">{{ $invoice->issue_from_phone }}</p>
                                    <p class="mb-0">{{ $invoice->issue_from_email }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h5 class="mb-3">To:</h5>
                                <div class="border p-3 rounded bg-light">
                                    <h6>{{ $invoice->issue_for }}</h6>
                                    <p class="mb-1">{!! nl2br(e($invoice->issue_for_address)) !!}</p>
                                    <p class="mb-1">{{ $invoice->issue_for_phone }}</p>
                                    <p class="mb-0">{{ $invoice->issue_for_email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Price (₦)</th>
                                        <th class="text-end">Tax (₦)</th>
                                        <th class="text-end">Total (₦)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if ($item->product_size)
                                                    <small class="d-block text-muted">Size:
                                                        {{ $item->product_size }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ number_format($item->price, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->tax, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Invoice Summary -->
                        <div class="row mb-4">
                            <div class="col-md-6 col-lg-7">
                                <div class="border p-3 rounded">
                                    <h5 class="mb-3">Notes</h5>
                                    <p class="text-muted mb-0">Thank you for your business. We appreciate your prompt
                                        payment.</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-5">
                                <div class="border p-3 rounded">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>Subtotal:</div>
                                        <div>₦{{ number_format($invoice->subtotal, 2) }}</div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>Discount:</div>
                                        <div>₦{{ number_format($invoice->discount, 2) }}</div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>Tax (7.5%):</div>
                                        <div>₦{{ number_format($invoice->tax, 2) }}</div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <div class="h5 mb-0">Grand Total:</div>
                                        <div class="h5 mb-0">₦{{ number_format($invoice->grand_total, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="border-top pt-4 text-center d-print-none">
                            <p class="text-muted">This invoice was created on
                                {{ $invoice->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css" media="print">
        @media print {
            body * {
                visibility: hidden;
            }

            #printableArea,
            #printableArea * {
                visibility: visible;
            }

            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .d-print-none {
                display: none !important;
            }

            .card {
                border: none !important;
            }

            .card-header {
                display: none !important;
            }
        }
    </style>
@endsection
