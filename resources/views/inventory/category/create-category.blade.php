@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="bg-light text-center rounded p-3">
                                <img id="preview-image" src="{{ asset('assets/images/img.webp') }}" alt="Preview"
                                    class="img-fluid rounded" style="max-width: 200px; height: auto;">
                            </div>
                            <div class="mt-3">
                                <h4 id="preview-title">Category Title</h4>
                                <div class="row">
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">Created By:</p>
                                        <h5 class="mb-0" id="preview-creator">{{ Auth::user()->business_name }}</h5>
                                    </div>

                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">ID:</p>
                                        <h5 class="mb-0" id="preview-tag">{{ $generated_tag_id }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-top">
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    <button type="submit" class="btn btn-primary w-100">Create Category</button>
                                </div>
                                <div class="col-lg-6">
                                    <a href="{{ route('category') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">General Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="category-title" class="form-label">Category Title</label>
                                        <input type="text" name="title" id="category-title" class="form-control"
                                            placeholder="Enter Title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="creator" class="form-label">Created By</label>
                                        <select class="form-control" name="created_by" id="creator" required>
                                            <option value="{{ Auth::id() }}" selected>{{ Auth::user()->business_name }}
                                            </option>
                                        </select>
                                        @error('created_by')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>



                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="product-id" class="form-label">Tag ID</label>
                                        <input type="text" name="tag_id" id="product-id" class="form-control"
                                            value="{{ $generated_tag_id }}" readonly>
                                        @error('tag_id')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="thumbnail" class="form-label">Thumbnail</label>
                                        <input type="file" class="form-control" id="thumbnail" name="thumbnail"
                                            accept="image/*" required>
                                        @error('thumbnail')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control bg-light-subtle" name="description" id="description" rows="7"
                                            placeholder="Type description">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Meta Options</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="meta-title" class="form-label">Meta Title</label>
                                        <input type="text" name="meta_title" id="meta-title" class="form-control"
                                            placeholder="Enter Title" value="{{ old('meta_title') }}">
                                        @error('meta_title')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="meta-keywords" class="form-label">Meta Tag Keywords</label>
                                        <input type="text" name="meta_keywords" id="meta-keywords"
                                            class="form-control" placeholder="Enter keywords"
                                            value="{{ old('meta_keywords') }}">
                                        @error('meta_keywords')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="meta-description" class="form-label">Meta Description</label>
                                        <textarea class="form-control bg-light-subtle" name="meta_description" id="meta-description" rows="4"
                                            placeholder="Type meta description">{{ old('meta_description') }}</textarea>
                                        @error('meta_description')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.getElementById('thumbnail').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview-image').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('category-title').addEventListener('input', function(e) {
                document.getElementById('preview-title').textContent = e.target.value || 'Category Title';
            });
        </script>
    @endpush
@endsection
