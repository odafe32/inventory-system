@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">All Categories List</h4>
                        <a href="{{ route('create-category') }}" class="btn btn-sm btn-primary">
                            Add Category
                        </a>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1"></label>
                                            </div>
                                        </th>
                                        <th>Categories</th>
                                        <th>Created By</th>
                                        <th>Description</th>
                                        <th>ID</th>
                                        <th>Keywords</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="category_{{ $category->id }}">
                                                    <label class="form-check-label"
                                                        for="category_{{ $category->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                        @if ($category->thumbnail)
                                                            <img src="{{ Storage::url($category->thumbnail) }}"
                                                                alt="{{ $category->title }}" class="avatar-md">
                                                        @else
                                                            <img src="{{ asset('assets/images/product/p-1.png') }}"
                                                                alt="Default" class="avatar-md">
                                                        @endif
                                                    </div>
                                                    <p class="text-dark fw-medium fs-15 mb-0">{{ $category->title }}</p>
                                                </div>
                                            </td>
                                            <td>{{ optional($category->creator)->business_name }}</td>
                                            <td>{{ Str::limit($category->description, 30) }}</td>
                                            <td>{{ $category->tag_id }}</td>
                                            <td>{{ $category->meta_keywords }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('edit-category', $category->id) }}"
                                                        class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18">
                                                        </iconify-icon>
                                                    </a>

                                                    <form action="{{ route('categories.delete', $category->id) }}"
                                                        method="POST" class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this category?')">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                class="align-middle fs-18">
                                                            </iconify-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No categories found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($categories->hasPages())
                            <div class="mt-4 px-4">
                                {{ $categories->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
