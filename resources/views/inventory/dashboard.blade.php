@extends('layout.application_layout')
@section('content')
    {{-- //always add this and the to the top --}}

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Start Container Fluid -->

        <!-- Start here.... -->
        <div class="row">
            <div class="col-xxl-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                            <iconify-icon icon="solar:cart-5-bold-duotone"
                                                class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Total Orders</p>
                                        <h3 class="text-dark mt-1 mb-0">{{ number_format($total_orders) }}</h3>
                                    </div> <!-- end col -->
                                </div> <!-- end row-->
                            </div> <!-- end card body -->
                            <div class="card-footer py-2 bg-light bg-opacity-50">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="{{ $revenue_growth >= 0 ? 'text-success' : 'text-danger' }}">
                                            <i
                                                class="bx {{ $revenue_growth >= 0 ? 'bxs-up-arrow' : 'bxs-down-arrow' }} fs-12"></i>
                                            {{ number_format(abs($revenue_growth), 1) }}%
                                        </span>
                                        <span class="text-muted ms-1 fs-12">Last Month</span>
                                    </div>
                                    <a href="{{ route('orders-list') }}" class="text-reset fw-semibold fs-12">View More</a>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col-md-6">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                            <i class="bx bx-award avatar-title fs-24 text-primary"></i>
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Total Products</p>
                                        <h3 class="text-dark mt-1 mb-0">{{ number_format($total_products) }}</h3>
                                    </div> <!-- end col -->
                                </div> <!-- end row-->
                            </div> <!-- end card body -->
                            <div class="card-footer py-2 bg-light bg-opacity-50">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted ms-1 fs-12">Total Count</span>
                                    </div>
                                    <a href="{{ route('product-list') }}" class="text-reset fw-semibold fs-12">View More</a>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col-md-6">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                            <i class="bx bxs-category avatar-title fs-24 text-primary"></i>
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Categories</p>
                                        <h3 class="text-dark mt-1 mb-0">{{ number_format($total_categories) }}</h3>
                                    </div> <!-- end col -->
                                </div> <!-- end row-->
                            </div> <!-- end card body -->
                            <div class="card-footer py-2 bg-light bg-opacity-50">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted ms-1 fs-12">Total Count</span>
                                    </div>
                                    <a href="{{ route('category') }}" class="text-reset fw-semibold fs-12">View More</a>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col-md-6">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                            <i class="bx bx-dollar-circle avatar-title text-primary fs-24"></i>
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Total Revenue</p>
                                        <h3 class="text-dark mt-1 mb-0">₦{{ number_format($total_revenue, 2) }}</h3>
                                    </div> <!-- end col -->
                                </div> <!-- end row-->
                            </div> <!-- end card body -->
                            <div class="card-footer py-2 bg-light bg-opacity-50">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="{{ $revenue_growth >= 0 ? 'text-success' : 'text-danger' }}">
                                            <i
                                                class="bx {{ $revenue_growth >= 0 ? 'bxs-up-arrow' : 'bxs-down-arrow' }} fs-12"></i>
                                            {{ number_format(abs($revenue_growth), 1) }}%
                                        </span>
                                        <span class="text-muted ms-1 fs-12">Last Month</span>
                                    </div>
                                    <a href="{{ route('invoices') }}" class="text-reset fw-semibold fs-12">View More</a>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- end col -->

            <div class="col-xxl-7">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Performance</h4>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-light">ALL</button>
                                <button type="button" class="btn btn-sm btn-outline-light">1M</button>
                                <button type="button" class="btn btn-sm btn-outline-light">6M</button>
                                <button type="button" class="btn btn-sm btn-outline-light active">1Y</button>
                            </div>
                        </div> <!-- end card-title-->

                        <div dir="ltr">
                            <div id="dash-performance-chart" class="apex-charts"></div>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title">
                                Recent Orders
                            </h4>

                            <a href="{{ route('orders-list') }}" class="btn btn-sm btn-soft-primary">
                                <i class="bx bx-plus me-1"></i>View Orders
                            </a>
                        </div>
                    </div>
                    <!-- end card body -->
                    <div class="table-responsive table-centered">
                        <table class="table mb-0">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="ps-3">
                                        Order ID
                                    </th>
                                    <th>
                                        Date
                                    </th>

                                    <th>
                                        Customer Name
                                    </th>
                                    <th>
                                        Email ID
                                    </th>
                                    <th>
                                        Phone No.
                                    </th>
                                    <th>
                                        Address
                                    </th>
                                    <th>
                                        Payment Type
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <!-- end thead-->
                            <tbody>
                                @forelse($recent_orders as $order)
                                    <tr>
                                        <td class="ps-3">
                                            <a
                                                href="{{ route('orders-details', $order->id) }}">{{ $order->order_id }}</a>
                                        </td>
                                        <td>{{ $order->formatted_date }}</td>

                                        <td>
                                            <a
                                                href="{{ route('orders-details', $order->id) }}">{{ $order->customer_name }}</a>
                                        </td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->phone }}</td>
                                        <td>{{ Str::limit($order->address, 20) }}</td>
                                        <td>{{ $order->payment_type }}</td>
                                        <td>
                                            <i
                                                class="bx bxs-circle {{ $order->order_status == 'Completed' ? 'text-success' : 'text-primary' }} me-1"></i>
                                            {{ $order->order_status }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No recent orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <!-- end tbody -->
                        </table>
                        <!-- end table -->
                    </div>
                    <!-- table responsive -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div> <!-- end row -->

    </div>
    <!-- End Container Fluid -->

    @push('scripts')
        <script>
            // Auto-dismiss alerts after 5 seconds
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        // Using Bootstrap's alert dismiss functionality
                        const closeBtn = new bootstrap.Alert(alert);
                        closeBtn.close();
                    });
                }, 5000);

                // Performance chart
                var options = {
                    chart: {
                        height: 350,
                        type: 'area',
                        toolbar: {
                            show: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    series: [{
                        name: 'Orders',
                        data: [
                            @foreach ($orders_by_month as $month => $count)
                                {{ $count }},
                            @endforeach
                        ]
                    }, {
                        name: 'Revenue (₦)',
                        data: [
                            @foreach ($revenue_by_month as $month => $amount)
                                {{ $amount }},
                            @endforeach
                        ]
                    }],
                    xaxis: {
                        categories: [
                            @foreach ($orders_by_month as $month => $count)
                                "{{ $month }}",
                            @endforeach
                        ],
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        tickAmount: 4,
                        floating: false,
                        labels: {
                            formatter: function(val) {
                                return val.toFixed(0);
                            }
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    fill: {
                        opacity: 0.5,
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 0.5,
                            opacityFrom: 0.8,
                            opacityTo: 0.4,
                        }
                    },
                    colors: ['#3b82f6', '#10b981'],
                    grid: {
                        yaxis: {
                            lines: {
                                offsetX: -30
                            }
                        },
                        padding: {
                            left: 20
                        }
                    },
                    tooltip: {
                        x: {
                            format: 'MMM yyyy'
                        },
                    }
                };

                var chart = new ApexCharts(document.querySelector("#dash-performance-chart"), options);
                chart.render();
            });
        </script>
    @endpush
@endsection
