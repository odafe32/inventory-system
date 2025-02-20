@extends('layout.auth_layout')
@section('content')
    <style>
        .form-control.error {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .form-control.success {
            border-color: #198754;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .error-message {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .error-message.show {
            display: block;
        }

        .form-label.required::after {
            content: "*";
            color: #dc3545;
            margin-left: 4px;
        }

        .alert {
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
    <div class="d-flex flex-column h-100 p-3">
        <div class="d-flex flex-column flex-grow-1">
            <div class="row h-100">
                <div class="col-xxl-7">
                    <div class="row justify-content-center h-100">
                        <div class="col-lg-6 py-lg-5">
                            <div class="d-flex flex-column h-100 justify-content-center">
                                <div class="auth-logo mb-4">
                                    {{-- this is how you link, either to  image or link --}}
                                    <a href="{{ url('/login') }}" class="logo-dark">
                                        <img src="{{ url('assets/images/logo-dark.png') }}" height="24" alt="logo dark">
                                    </a>

                                    <a href="{{ url('/login') }}" class="logo-light">
                                        <img src="{{ url('assets/images/logo-light.png') }}" height="24"
                                            alt="logo light">
                                    </a>
                                </div>

                                <h2 class="fw-bold fs-24">Login</h2>

                                <p class="text-muted mt-1 mb-4">Enter your email address and password to access Portal.
                                </p>

                                {{-- Display success messages --}}
                                @if (session('success'))
                                    <div class="alert alert-success mb-3">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                {{-- Display error messages --}}
                                @if (session('error'))
                                    <div class="alert alert-danger mb-3">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="mb-5">
                                    <!-- Login form - update this in /resources/views/auth/login.blade.php -->
                                    <form action="{{ route('login.submit') }}" method="POST" class="authentication-form">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label required" for="example-email">Email</label>
                                            <input type="email" id="example-email" name="email"
                                                class="form-control @error('email') error @enderror"
                                                value="{{ old('email') }}" placeholder="Enter your email">
                                            @error('email')
                                                <div class="error-message show">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label required" for="example-password">Password</label>
                                            <input type="password" id="example-password" name="password"
                                                class="form-control @error('password') error @enderror"
                                                placeholder="Enter your password">
                                            @error('password')
                                                <div class="error-message show">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>

                                        <div class="mb-1 text-center d-grid">
                                            <button class="btn btn-soft-primary" type="submit">Sign In</button>
                                        </div>
                                    </form>

                                </div>

                                <p class="text-danger text-center">Don't have an account? <a href="{{ url('/register') }}"
                                        class="text-dark fw-bold ms-1 underline" style="text-decoration: underline">Create
                                        an Account</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-5 d-none d-xxl-flex">
                    <div class="card h-100 mb-0 overflow-hidden">
                        <div class="d-flex flex-column h-100">
                            <img src="{{ url('assets/images/small/img-10.jpg') }}" alt="" class="w-100 h-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- // inline script --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.authentication-form');
            const email = document.getElementById('example-email');
            const password = document.getElementById('example-password');

            // Add required class to labels
            document.querySelector('label[for="example-email"]').classList.add('required');
            document.querySelector('label[for="example-password"]').classList.add('required');

            // Add error message elements
            addErrorContainer(email, 'email-error');
            addErrorContainer(password, 'password-error');

            // Real-time validation
            email.addEventListener('input', () => validateEmail(email));
            password.addEventListener('input', () => validatePassword(password));

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Reset previous validations
                resetValidation();

                // Validate all fields
                const isEmailValid = validateEmail(email);
                const isPasswordValid = validatePassword(password);

                if (isEmailValid && isPasswordValid) {
                    // Proceed with form submission
                    form.submit();
                }
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.opacity = '1';
                    alert.style.transition = 'opacity 1s';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 1000);
                });
            }, 5000);
        });

        function addErrorContainer(element, id) {
            const errorDiv = document.createElement('div');
            errorDiv.id = id;
            errorDiv.className = 'error-message';
            element.parentNode.appendChild(errorDiv);
        }

        function validateEmail(email) {
            const errorElement = document.getElementById('email-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!email.value.trim()) {
                showError(email, errorElement, 'Email is required');
                return false;
            } else if (!emailRegex.test(email.value)) {
                showError(email, errorElement, 'Please enter a valid email address');
                return false;
            } else {
                showSuccess(email, errorElement);
                return true;
            }
        }

        function validatePassword(password) {
            const errorElement = document.getElementById('password-error');

            if (!password.value.trim()) {
                showError(password, errorElement, 'Password is required');
                return false;
            } else if (password.value.length < 6) {
                showError(password, errorElement, 'Password must be at least 6 characters');
                return false;
            } else {
                showSuccess(password, errorElement);
                return true;
            }
        }

        function showError(element, errorElement, message) {
            element.classList.remove('success');
            element.classList.add('error');
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }

        function showSuccess(element, errorElement) {
            element.classList.remove('error');
            element.classList.add('success');
            errorElement.classList.remove('show');
        }

        function resetValidation() {
            const inputs = document.querySelectorAll('.form-control');
            const errorMessages = document.querySelectorAll('.error-message');

            inputs.forEach(input => {
                input.classList.remove('error', 'success');
            });

            errorMessages.forEach(error => {
                error.classList.remove('show');
            });
        }
    </script>
@endsection
