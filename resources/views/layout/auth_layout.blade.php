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
    <title>{{ $meta_title ?? 'Larkon - Inventory Management System' }} </title>
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
</head>

<body class="h-100">

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
        <!-- End page-content -->
        <!-- Vendor Javascript (Require in all Page) -->
        <script src="{{ url('assets/js/vendor.js?v=' . env('CACHE_VERSION')) }}"></script>

        <!-- App Javascript (Require in all Page) -->
        <script src="{{ url('assets/js/app.js?v=' . env('CACHE_VERSION')) }}"></script>

</body>


<!-- Mirrored from techzaa.in/larkon/admin/auth-signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Jan 2025 21:03:42 GMT -->

</html>
