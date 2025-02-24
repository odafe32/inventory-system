@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">
        <div class="row">
            <!-- Profile Information Card -->
            <div class="col-xl-8 col-lg-7">
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

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Profile Information</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editProfileModal">
                            <i class="bx bx-edit"></i> Edit Profile
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="bg-primary profile-bg rounded-top position-relative mx-n3 mt-n3 p-5">
                            @if (Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                                    alt="{{ Auth::user()->business_name }}"
                                    class="avatar-xl border border-light border-3 rounded-circle position-absolute top-100 start-0 translate-middle ms-5">
                            @else
                                <img src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                    alt="{{ Auth::user()->business_name }}"
                                    class="avatar-xl border border-light border-3 rounded-circle position-absolute top-100 start-0 translate-middle ms-5">
                            @endif
                        </div>
                        <div class="mt-5 pt-3 d-flex flex-wrap align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1">{{ Auth::user()->business_name }} <i
                                        class="bx bxs-badge-check text-success align-middle"></i></h4>
                                <p class="mb-0">{{ Auth::user()->business_type }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-2 my-2 my-lg-0">
                                <a href="mailto:{{ Auth::user()->email }}" class="btn btn-info"><i
                                        class="bx bx-message-dots"></i> Mail</a>
                                <a href="tel:{{ Auth::user()->phone }}" class="btn btn-outline-info"><i
                                        class="bx bx-phone"></i> Phone</a>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h5 class="text-muted fw-normal mb-2">Username</h5>
                                <p class="fs-16">{{ Auth::user()->username }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5 class="text-muted fw-normal mb-2">Email</h5>
                                <p class="fs-16">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5 class="text-muted fw-normal mb-2">Full Name</h5>
                                <p class="fs-16">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5 class="text-muted fw-normal mb-2">Phone</h5>
                                <p class="fs-16">{{ Auth::user()->phone }}</p>
                            </div>
                            <div class="col-12 mb-3">
                                <h5 class="text-muted fw-normal mb-2">Business Address</h5>
                                <p class="fs-16">{{ Auth::user()->business_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About The Business Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">About The Business</h4>
                    </div>
                    <div class="card-body">
                        <p>
                            @if (isset(Auth::user()->business_description))
                                {{ Auth::user()->business_description }}
                            @else
                                <em>No business description available. Update your profile to add information about your
                                    business.</em>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Cards -->
            <div class="col-xl-4 col-lg-5">
                <!-- Personal Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Personal Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                    <iconify-icon icon="solar:tag-line-duotone" class="fs-20 text-secondary"></iconify-icon>
                                </div>
                                <p class="mb-0 fs-14">{{ Auth::user()->username }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                    <iconify-icon icon="solar:pen-2-linear" class="fs-20 text-secondary"></iconify-icon>
                                </div>
                                <p class="mb-0 fs-14">{{ Auth::user()->first_name }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                    <iconify-icon icon="solar:pen-2-linear" class="fs-20 text-secondary"></iconify-icon>
                                </div>
                                <p class="mb-0 fs-14">{{ Auth::user()->last_name }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                    <iconify-icon icon="solar:phone-calling-broken"
                                        class="fs-20 text-secondary"></iconify-icon>
                                </div>
                                <p class="mb-0 fs-14">Phone<br><a href="tel:{{ Auth::user()->phone }}"
                                        class="text-primary fw-semibold">{{ Auth::user()->phone }}</a></p>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                    <iconify-icon icon="solar:letter-bold-duotone"
                                        class="fs-20 text-secondary"></iconify-icon>
                                </div>
                                <p class="mb-0 fs-14">Email<br><a href="mailto:{{ Auth::user()->email }}"
                                        class="text-primary fw-semibold">{{ Auth::user()->email }}</a></p>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                    <iconify-icon icon="solar:global-bold-duotone"
                                        class="fs-20 text-secondary"></iconify-icon>
                                </div>
                                <p class="mb-0 fs-14">Language <span class="text-dark fw-semibold">English</span></p>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                    <iconify-icon icon="solar:check-circle-bold-duotone"
                                        class="fs-20 text-secondary"></iconify-icon>
                                </div>
                                <p class="mb-0 fs-14">Status <span
                                        class="badge bg-success-subtle text-success ms-1">Active</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('password.change') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-lock-alt me-1"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Business Stats Card -->

            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <!-- Profile Image -->
                                <div class="col-12 mb-4 text-center">
                                    <div class="position-relative d-inline-block">
                                        @if (Auth::user()->profile_image)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                                                alt="Profile" class="rounded-circle" width="120" height="120"
                                                id="profile-image-preview">
                                        @else
                                            <img src="{{ asset('images.png') }}" alt="Profile" class="rounded-circle"
                                                width="120" height="120" id="profile-image-preview">
                                        @endif
                                        <div class="position-absolute bottom-0 end-0">
                                            <label for="profile_image" class="btn btn-sm btn-primary rounded-circle">
                                                <i class="bx bx-camera"></i>
                                            </label>
                                            <input type="file" id="profile_image" name="profile_image" class="d-none"
                                                accept="image/*" onchange="previewImage(this)">
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name" value="{{ Auth::user()->first_name }}"
                                        required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" value="{{ Auth::user()->last_name }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username" value="{{ Auth::user()->username }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ Auth::user()->email }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ Auth::user()->phone }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Business Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="business_name" class="form-label">Business Name</label>
                                    <input type="text"
                                        class="form-control @error('business_name') is-invalid @enderror"
                                        id="business_name" name="business_name"
                                        value="{{ Auth::user()->business_name }}" required>
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_type" class="form-label">Business Type</label>
                                    <input type="text"
                                        class="form-control @error('business_type') is-invalid @enderror"
                                        id="business_type" name="business_type"
                                        value="{{ Auth::user()->business_type }}" required>
                                    @error('business_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_address" class="form-label">Business Address</label>
                                    <textarea class="form-control @error('business_address') is-invalid @enderror" id="business_address"
                                        name="business_address" rows="3" required>{{ Auth::user()->business_address }}</textarea>
                                    @error('business_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="business_description" class="form-label">Business Description</label>
                                    <textarea class="form-control @error('business_description') is-invalid @enderror" id="business_description"
                                        name="business_description" rows="3">{{ Auth::user()->business_description ?? '' }}</textarea>
                                    @error('business_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('profile-image-preview').setAttribute('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
