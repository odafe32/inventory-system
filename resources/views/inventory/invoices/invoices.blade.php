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
                        <h4 class="card-title mb-0">Invoices</h4>
                        <a href="{{ route('create-invoices') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Create New Invoice
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Client</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ route('invoices-details', $invoice->id) }}"
                                                    class="text-dark fw-bold">
                                                    {{ $invoice->invoice_number }}
                                                </a>
                                            </td>
                                            <td>{{ $invoice->issue_for }}</td>
                                            <td>{{ $invoice->issue_date->format('M d, Y') }}</td>
                                            <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                            <td>₦{{ number_format($invoice->grand_total, 2) }}</td>
                                            <td>
                                                @if ($invoice->status == 'Paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif($invoice->status == 'Pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('invoices-details', $invoice->id) }}">
                                                                <i class="bx bx-show text-primary"></i> View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('edit-invoices', $invoice->id) }}">
                                                                <i class="bx bx-edit text-info"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                                onclick="printInvoice({{ $invoice->id }})">
                                                                <i class="bx bx-printer text-success"></i> Print
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('invoices.delete', $invoice->id) }}"
                                                                method="POST" id="delete-form-{{ $invoice->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="dropdown-item text-danger"
                                                                    onclick="confirmDelete({{ $invoice->id }})">
                                                                    <i class="bx bx-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="bx bx-file text-secondary" style="font-size: 3rem;"></i>
                                                    <h5 class="mt-3">No Invoices Found</h5>
                                                    <p class="text-muted">Create your first invoice to get started.</p>
                                                    <a href="{{ route('create-invoices') }}"
                                                        class="btn btn-primary btn-sm mt-2">
                                                        <i class="bx bx-plus"></i> Create Invoice
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $invoices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(invoiceId) {
                if (confirm('Are you sure you want to delete this invoice? This action cannot be undone.')) {
                    document.getElementById('delete-form-' + invoiceId).submit();
                }
            }

            function printInvoice(invoiceId) {
                window.open('{{ url('invoices-details') }}/' + invoiceId + '?print=true', '_blank');
            }
        </script>
    @endpush
@endsection
