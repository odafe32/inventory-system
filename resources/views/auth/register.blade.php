@extends('layout.auth_layout')
@section('content')
    <style>
        /* Keep your existing styles and add these new ones */
        .form-section {
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
            padding-bottom: 20px;
        }

        .section-title {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 15px;
        }

        .password-requirements {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }

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


        /* Keep your existing error/success styles */
    </style>

    <div class="d-flex flex-column h-100 p-3">
        <div class="d-flex flex-column flex-grow-1">
            <div class="row h-100">
                <div class="col-xxl-7">
                    <div class="row justify-content-center h-100">
                        <div class="col-lg-8 py-lg-5">
                            <div class="d-flex flex-column h-100">
                                <div class="auth-logo mb-4">
                                    <a href="{{ url('#') }}" class="logo-dark">
                                        <img src="{{ url('assets/images/logo-dark.png') }}" height="24" alt="logo dark">
                                    </a>
                                </div>

                                <h2 class="fw-bold fs-24">Create Your Inventory Account</h2>
                                <p class="text-muted mt-1 mb-4">Enter your details to create your inventory management
                                    account.</p>

                                <div class="mb-5">
                                    <!-- Register form - update this in /resources/views/auth/register.blade.php -->
                                    <form action="{{ route('register.submit') }}" method="POST"
                                        class="authentication-form">
                                        @csrf
                                        <!-- Personal Information Section -->
                                        <div class="form-section">
                                            <h3 class="section-title">Personal Information</h3>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required" for="first-name">First Name</label>
                                                    <input type="text" id="first-name" name="first_name"
                                                        class="form-control @error('first_name') error @enderror"
                                                        value="{{ old('first_name') }}" placeholder="Enter first name">
                                                    @error('first_name')
                                                        <div class="error-message show">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required" for="last-name">Last Name</label>
                                                    <input type="text" id="last-name" name="last_name"
                                                        class="form-control @error('last_name') error @enderror"
                                                        value="{{ old('last_name') }}" placeholder="Enter last name">
                                                    @error('last_name')
                                                        <div class="error-message show">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required" for="email">Email Address</label>
                                                <input type="email" id="email" name="email"
                                                    class="form-control @error('email') error @enderror"
                                                    value="{{ old('email') }}" placeholder="Enter your email">
                                                @error('email')
                                                    <div class="error-message show">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required" for="phone">Phone Number</label>
                                                <input type="tel" id="phone" name="phone"
                                                    class="form-control @error('phone') error @enderror"
                                                    value="{{ old('phone') }}" placeholder="Enter phone number">
                                                @error('phone')
                                                    <div class="error-message show">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Business Information Section -->
                                        <div class="form-section">
                                            <h3 class="section-title">Business Information</h3>
                                            <div class="mb-3">
                                                <label class="form-label required" for="business-name">Business Name</label>
                                                <input type="text" id="business-name" name="business_name"
                                                    class="form-control @error('business_name') error @enderror"
                                                    value="{{ old('business_name') }}" placeholder="Enter business name">
                                                @error('business_name')
                                                    <div class="error-message show">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required" for="business-type">Business Type</label>
                                                <select id="business-type" name="business_type"
                                                    class="form-select @error('business_type') error @enderror">
                                                    <option value="">Select Business Type</option>
                                                    <option value="retail"
                                                        {{ old('business_type') == 'retail' ? 'selected' : '' }}>Retail
                                                    </option>
                                                    <option value="wholesale"
                                                        {{ old('business_type') == 'wholesale' ? 'selected' : '' }}>
                                                        Wholesale</option>
                                                    <option value="manufacturing"
                                                        {{ old('business_type') == 'manufacturing' ? 'selected' : '' }}>
                                                        Manufacturing</option>
                                                    <option value="distribution"
                                                        {{ old('business_type') == 'distribution' ? 'selected' : '' }}>
                                                        Distribution</option>
                                                    <option value="other"
                                                        {{ old('business_type') == 'other' ? 'selected' : '' }}>Other
                                                    </option>
                                                </select>
                                                @error('business_type')
                                                    <div class="error-message show">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required" for="business-address">Business
                                                    Address</label>
                                                <textarea id="business-address" name="business_address"
                                                    class="form-control @error('business_address') error @enderror" rows="2"
                                                    placeholder="Enter business address">{{ old('business_address') }}</textarea>
                                                @error('business_address')
                                                    <div class="error-message show">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Account Security Section -->
                                        <div class="form-section">
                                            <h3 class="section-title">Account Security</h3>
                                            <div class="mb-3">
                                                <label class="form-label required" for="username">Username</label>
                                                <input type="text" id="username" name="username"
                                                    class="form-control @error('username') error @enderror"
                                                    value="{{ old('username') }}" placeholder="Choose a username">
                                                @error('username')
                                                    <div class="error-message show">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required" for="password">Password</label>
                                                <input type="password" id="password" name="password"
                                                    class="form-control @error('password') error @enderror"
                                                    placeholder="Create a strong password">
                                                <div class="password-requirements">
                                                    Password must contain at least 8 characters, using only letters and
                                                    numbers
                                                </div>
                                                @error('password')
                                                    <div class="error-message show">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required" for="confirm-password">Confirm
                                                    Password</label>
                                                <input type="password" id="confirm-password" name="password_confirmation"
                                                    class="form-control" placeholder="Confirm your password">
                                            </div>
                                        </div>

                                        <!-- Terms and Conditions -->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    class="form-check-input @error('terms_accepted') error @enderror"
                                                    id="terms-checkbox" name="terms_accepted">
                                                <label class="form-check-label" for="terms-checkbox">
                                                    I agree to the <a href="#" class="text-primary">Terms of
                                                        Service</a> and
                                                    <a href="#" class="text-primary">Privacy Policy</a>
                                                </label>
                                            </div>
                                            @error('terms_accepted')
                                                <div class="error-message show">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 text-center d-grid">
                                            <button class="btn btn-soft-primary" type="submit">Create Account</button>
                                        </div>
                                    </form>
                                </div>

                                <p class="text-center">Already have an account?
                                    <a href="{{ url('/login') }}" class="text-dark fw-bold ms-1"
                                        style="text-decoration: underline">
                                        Sign In
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keep your existing sidebar image section -->
                <div class="col-xxl-5 d-none d-xxl-flex">
                    <div class="card h-100 mb-0 overflow-hidden">
                        <div class="d-flex flex-column h-100 gap-2">
                            <img src="{{ url('imgg.jpeg') }}" alt="" style="background-size:contain;">
                            <img src="{{ url('clohes.webp') }}" alt="" style="background-size:contain;">
                            <img src="{{ url('cap.webp') }}" alt="" style="background-size:contain;">
                            <img src="{{ url('shoes.jpg') }}" alt="" style="background-size:contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.authentication-form');
            if (!form) return;

            // Define validation rules for each field
            const validationRules = {
                'first-name': {
                    required: true,
                    minLength: 2,
                    errorMessages: {
                        required: 'First name is required',
                        minLength: 'First name must be at least 2 characters'
                    }
                },
                'last-name': {
                    required: true,
                    minLength: 2,
                    errorMessages: {
                        required: 'Last name is required',
                        minLength: 'Last name must be at least 2 characters'
                    }
                },
                'email': {
                    required: true,
                    pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                    errorMessages: {
                        required: 'Email address is required',
                        pattern: 'Please enter a valid email address'
                    }
                },
                'phone': {
                    required: true,
                    minLength: 10,
                    errorMessages: {
                        required: 'Phone number is required',
                        minLength: 'Phone number must be at least 10 digits'
                    }
                },
                'business-name': {
                    required: true,
                    minLength: 3,
                    errorMessages: {
                        required: 'Business name is required',
                        minLength: 'Business name must be at least 3 characters'
                    }
                },
                'business-type': {
                    required: true,
                    errorMessages: {
                        required: 'Please select a business type'
                    }
                },
                'business-address': {
                    required: true,
                    minLength: 10,
                    errorMessages: {
                        required: 'Business address is required',
                        minLength: 'Business address must be at least 10 characters'
                    }
                },
                'username': {
                    required: true,
                    minLength: 4,
                    errorMessages: {
                        required: 'Username is required',
                        minLength: 'Username must be at least 4 characters'
                    }
                },
                'password': {
                    required: true,
                    minLength: 8,
                    pattern: /^[A-Za-z0-9]{8,}$/,
                    errorMessages: {
                        required: 'Password is required',
                        minLength: 'Password must be at least 8 characters',
                        pattern: 'Password must contain only letters and numbers'
                    }
                },
                'confirm-password': {
                    required: true,
                    match: 'password',
                    errorMessages: {
                        required: 'Please confirm your password',
                        match: 'Passwords do not match'
                    }
                }
            };

            // Add error containers dynamically if they don't exist
            Object.keys(validationRules).forEach(fieldId => {
                const element = document.getElementById(fieldId);
                if (element) {
                    const errorId = `${fieldId}-error`;
                    if (!document.getElementById(errorId)) {
                        const errorDiv = document.createElement('div');
                        errorDiv.id = errorId;
                        errorDiv.className = 'error-message';
                        element.parentNode.appendChild(errorDiv);
                    }
                }
            });

            // Real-time validation
            Object.keys(validationRules).forEach(fieldId => {
                const element = document.getElementById(fieldId);
                if (element) {
                    element.addEventListener('input', () => {
                        validateField(element, validationRules[fieldId]);
                    });
                    element.addEventListener('blur', () => {
                        validateField(element, validationRules[fieldId]);
                    });
                }
            });

            // Special handler for phone
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^\d]/g, '');
                });
            }

            // Form submission handler
            form.addEventListener('submit', function(e) {
                let isValid = true;

                // Validate all fields
                Object.keys(validationRules).forEach(fieldId => {
                    const element = document.getElementById(fieldId);
                    if (element && !validateField(element, validationRules[fieldId])) {
                        isValid = false;
                    }
                });

                // Check terms and conditions
                const termsCheckbox = document.getElementById('terms-checkbox');
                if (!termsCheckbox.checked) {
                    const termsError = document.querySelector('.form-check').nextElementSibling ||
                        document.createElement('div');

                    if (!termsError.classList.contains('error-message')) {
                        termsError.className = 'error-message';
                        document.querySelector('.form-check').parentNode.appendChild(termsError);
                    }

                    termsError.textContent = 'You must accept the Terms of Service and Privacy Policy';
                    termsError.classList.add('show');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    // Scroll to the first error
                    const firstError = document.querySelector('.error-message.show');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
            });

            // Validation helper functions
            function validateField(element, rules) {
                if (!element) return false;

                const errorElement = document.getElementById(`${element.id}-error`);
                if (!errorElement) return false;

                const value = element.value.trim();

                // Required validation
                if (rules.required && !value) {
                    showError(element, errorElement, rules.errorMessages.required);
                    return false;
                }

                // Minimum length validation
                if (rules.minLength && value.length < rules.minLength) {
                    showError(element, errorElement, rules.errorMessages.minLength);
                    return false;
                }

                // Pattern validation
                if (rules.pattern && !rules.pattern.test(value)) {
                    showError(element, errorElement, rules.errorMessages.pattern);
                    return false;
                }

                // Password match validation
                if (rules.match) {
                    const matchElement = document.getElementById(rules.match);
                    if (matchElement && value !== matchElement.value) {
                        showError(element, errorElement, rules.errorMessages.match);
                        return false;
                    }
                }

                showSuccess(element, errorElement);
                return true;
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
                errorElement.textContent = '';
            }
        });
    </script>
@endsection
