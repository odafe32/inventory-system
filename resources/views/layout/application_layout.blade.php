<!DOCTYPE html>
<html lang="en" data-wf-page="{{ $data_wf_page ?? '' }}" data-wf-site="63b261b98057c80332966627">


<!-- Mirrored from techzaa.in/larkon/admin/auth-signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Jan 2025 21:03:42 GMT -->

<head>
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BFB4N0D1JW"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-BFB4N0D1JW');
    </script>
    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>{{ $meta_title ?? 'Larkon -  Management System' }} </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content=" {{ $meta_desc ?? ' A Management system that helps businees keep track of their products...' }}" />
    <meta name="author" content="Techzaa " />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href=" {{ url('assets/images/favicon.ico?v=' . env('CACHE_VERSION')) }}">

    <!-- Vendor css (Require in all Page) -->
    <link href="{{ url('assets/css/vendor.min.css?v=' . env('CACHE_VERSION')) }}" rel="stylesheet" type="text/css" />

    <!-- Icons css (Require in all Page) -->
    <link href="{{ url('assets/css/icons.min.css?v=' . env('CACHE_VERSION')) }}" rel="stylesheet" type="text/css" />

    <!-- App css (Require in all Page) -->
    <link href="{{ url('assets/css/app.min.css?v=' . env('CACHE_VERSION')) }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config js (Require in all Page) -->
    <script src="{{ url('assets/js/config.js?v' . env('CACHE_VERSION')) }}"></script>
    <!-- In your layout file -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="h-100">
    {{ csrf_field() }}
    <!-- START Wrapper -->
    <div class="wrapper">
        <!-- ========== Topbar Start ========== -->
        <header class="topbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="d-flex align-items-center">
                        <!-- Menu Toggle Button -->
                        <div class="topbar-item">
                            <button type="button" class="button-toggle-menu me-2">
                                <iconify-icon icon="solar:hamburger-menu-broken"
                                    class="fs-24 align-middle"></iconify-icon>
                            </button>
                        </div>

                        <!-- Menu Toggle Button -->
                        <div class="topbar-item">
                            <h4 class="fw-bold topbar-button pe-none text-uppercase mb-0">
                                {{ $page_title ?? 'Welcome!' }}</h4>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1">

                        <!-- Theme Color (Light/Dark) -->
                        <div class="topbar-item">
                            <button type="button" class="topbar-button" id="light-dark-mode">
                                <iconify-icon icon="solar:moon-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                            </button>
                        </div>



                        <!-- User -->
                        <div class="dropdown topbar-item">
                            <a type="button" class="topbar-button" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    @if (Auth::user()->profile_image)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile"
                                            class="rounded-circle" width="32" id="profile-image-preview">
                                    @else
                                        <img src="{{ asset('images.png') }}" alt="Profile" class="rounded-circle"
                                            width="32" id="profile-image-preview">
                                    @endif

                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                @if (Auth::user()->first_name)
                                    <h6 class="dropdown-header">Welcome {{ Auth::user()->first_name }}!</h6>
                                @else
                                    <h6 class="dropdown-header">Welcome </h6>
                                @endif

                                <a class="dropdown-item" href="{{ url('/profile') }}">
                                    <i class="bx bx-user-circle text-muted fs-18 align-middle me-1"></i><span
                                        class="align-middle">Profile</span>
                                </a>





                                <div class="dropdown-divider my-1"></div>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        <i class="bx bx-log-out fs-18 align-middle me-1"></i><span
                                            class="align-middle">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </header>


        <!-- ========== App Menu Start ========== -->
        <div class="main-nav">
            <!-- Sidebar Logo -->
            <div class="logo-box">
                <a href="{{ url('/dashboard') }}" class="logo-dark">
                    <img src="{{ url('assets/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
                    <img src="{{ url('assets/images/logo-dark.png') }}" class="logo-lg" alt="logo dark">
                </a>

                <a href="{{ url('/dashboard') }}" class="logo-light">
                    <img src="{{ url('assets/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
                    <img src="{{ url('assets/images/logo-light.png') }}" class="logo-lg" alt="logo light">
                </a>
            </div>

            <!-- Menu Toggle Button (sm-hover) -->
            <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
                <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone"
                    class="button-sm-hover-icon"></iconify-icon>
            </button>

            <div class="scrollbar" data-simplebar>
                <ul class="navbar-nav" id="navbar-nav">



                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/dashboard') }}">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Dashboard </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarProducts">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Products </span>
                        </a>
                        <div class="collapse" id="sidebarProducts">
                            <ul class="nav sub-navbar-nav">
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ url('/product-list') }}">Product
                                        List</a>
                                </li>
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ url('/edit-product') }}">Edit Product</a>
                                </li>
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ url('/create-product') }}">Create</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarCategory" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarCategory">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Category </span>
                        </a>
                        <div class="collapse" id="sidebarCategory">
                            <ul class="nav sub-navbar-nav">
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ url('/category') }}">Category List</a>
                                </li>

                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ url('/create-category') }}">Create</a>
                                </li>
                            </ul>
                        </div>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarOrders" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarOrders">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Orders </span>
                        </a>
                        <div class="collapse" id="sidebarOrders">
                            <ul class="nav sub-navbar-nav">

                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ url('/orders') }}">orders</a>
                                </li>
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ url('/create-order') }}">Create orders</a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-arrow" href="#sidebarInvoice" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarInvoice">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Invoices </span>
                        </a>
                        <div class="collapse" id="sidebarInvoice">
                            <ul class="nav sub-navbar-nav">
                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ url('/invoices') }}">Invoices</a>
                                </li>

                                <li class="sub-nav-item">
                                    <a class="sub-nav-link" href="{{ url('/create-invoices') }}">Create</a>
                                </li>
                            </ul>
                        </div>
                    </li>



                    <li class="menu-title mt-2">Users</li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/profile') }}">
                            <span class="nav-icon">
                                <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                            </span>
                            <span class="nav-text"> Profile </span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        <!-- ========== App Menu End ========== -->

        <div class="main-content">
            <div class="page-content">

                <!-- Content Section -->
                <div class="row">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
                <!-- End Content Section -->

            </div>


            <!-- ========== Footer Start ========== -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> &copy; Larkon. Designed & Developed by <iconify-icon
                                icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon>
                            <a href="https://1.envato.market/techzaa" class="fw-bold footer-text"
                                target="_blank">Divine & Odafe Godfrey</a>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- ========== Footer End ========== -->

        </div>
        <!-- ==================================================== -->
        <!-- End Page Content -->
        <!-- ==================================================== -->

    </div>



    <!-- End page-content -->
    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ url('assets/js/vendor.js?v=' . env('CACHE_VERSION')) }}"></script>

    <!-- App Javascript (Require in all Page) -->
    <script src="{{ url('assets/js/app.js?v=' . env('CACHE_VERSION')) }}"></script>

    <!-- Vector Map Js -->
    <script src="{{ url('assets/vendor/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ url('assets/vendor/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ url('assets/vendor/jsvectormap/maps/world.js') }}"></script>

    <!-- Dashboard Js -->
    <script src="{{ url('assets/js/pages/dashboard.js') }}"></script>

</body>


<!-- Mirrored from techzaa.in/larkon/admin/auth-signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Jan 2025 21:03:42 GMT -->

</html>
