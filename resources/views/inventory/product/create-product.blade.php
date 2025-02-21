@extends('layout.application_layout')
@section('content')
    <div class="container-xxl">

        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-body">
                            <img src="assets/images/product/p-1.png" alt="" class="img-fluid rounded bg-light">
                            <div class="mt-3">
                                <h4>Men Black Slim Fit T-shirt <span class="fs-14 text-muted ms-1">(Fashion)</span></h4>
                                <h5 class="text-dark fw-medium mt-3">Price :</h5>
                                <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                    <span class="text-muted text-decoration-line-through">$100</span> $80 <small class="text-muted"> (30% Off)</small>
                                </h4>
                                <div class="mt-3">
                                    <h5 class="text-dark fw-medium">Size :</h5>
                                    <div class="d-flex flex-wrap gap-2" role="group" aria-label="Basic checkbox toggle button group">
                                        <input type="checkbox" class="btn-check" id="size-s">
                                        <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-s">S</label>

                                        <input type="checkbox" class="btn-check" id="size-m" checked="">
                                        <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-m">M</label>

                                        <input type="checkbox" class="btn-check" id="size-xl">
                                        <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-xl">Xl</label>

                                        <input type="checkbox" class="btn-check" id="size-xxl">
                                        <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-xxl">XXL</label>

                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="card-footer bg-light-subtle">
                            <div class="row g-2">
                                <div class="col-lg-6">
                                    <a href="#!" class="btn btn-outline-secondary w-100">Create Product</a>
                                </div>
                                <div class="col-lg-6">
                                    <a href="#!" class="btn btn-primary w-100">Cancel</a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8 ">
                <div class="card">
                    <div class="card-header">
                            <h4 class="card-title">Add Product Photo</h4>
                    </div>
                    <div class="card-body">
                            <!-- File Upload -->
                            <form action="https://techzaa.in/" method="post" class="dropzone dz-clickable" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                                
                                <div class="dz-message needsclick">
                                    <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                    <h3 class="mt-4">Drop your images here, or <span class="text-primary">click to browse</span></h3>
                                    <span class="text-muted fs-13">
                                        1600 x 1200 (4:3) recommended. PNG, JPG and GIF files are allowed
                                    </span>
                                </div>
                            </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                            <h4 class="card-title">Product Information</h4>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form>
                                        <div class="mb-3">
                                                <label for="product-name" class="form-label">Product Name</label>
                                                <input type="text" id="product-name" class="form-control" placeholder="Items Name">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <form>
                                        <label for="product-categories" class="form-label">Product Categories</label>
                                        <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-control choices__input" id="product-categories" data-choices="" data-choices-groups="" data-placeholder="Select Categories" name="choices-single-groups" hidden="" tabindex="-1" data-choice="active"><option value="" data-custom-properties="[object Object]">Choose a categories</option></select><div class="choices__list choices__list--single"><div class="choices__item choices__placeholder choices__item--selectable" data-item="" data-id="1" data-value="" data-custom-properties="[object Object]" aria-selected="true">Choose a categories</div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Choose a categories" placeholder=""><div class="choices__list" role="listbox"><div id="choices--product-categories-item-choice-2" class="choices__item choices__item--choice is-selected choices__placeholder choices__item--selectable is-highlighted" role="option" data-choice="" data-id="2" data-value="" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">Choose a categories</div><div id="choices--product-categories-item-choice-1" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="1" data-value="Appliances" data-select-text="Press to select" data-choice-selectable="">Appliances</div><div id="choices--product-categories-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="Electronics" data-select-text="Press to select" data-choice-selectable="">Electronics</div><div id="choices--product-categories-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="Fashion" data-select-text="Press to select" data-choice-selectable="">Fashion</div><div id="choices--product-categories-item-choice-5" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="5" data-value="Footwear" data-select-text="Press to select" data-choice-selectable="">Footwear</div><div id="choices--product-categories-item-choice-6" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="6" data-value="Furniture" data-select-text="Press to select" data-choice-selectable="">Furniture</div><div id="choices--product-categories-item-choice-7" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="7" data-value="Headphones" data-select-text="Press to select" data-choice-selectable="">Headphones</div><div id="choices--product-categories-item-choice-8" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="8" data-value="Other Accessories" data-select-text="Press to select" data-choice-selectable="">Other Accessories</div><div id="choices--product-categories-item-choice-9" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="9" data-value="Sportswear" data-select-text="Press to select" data-choice-selectable="">Sportswear</div><div id="choices--product-categories-item-choice-10" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="10" data-value="Watches" data-select-text="Press to select" data-choice-selectable="">Watches</div></div></div></div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <form>
                                        <div class="mb-3">
                                                <label for="product-brand" class="form-label">Brand</label>
                                                <input type="text" id="product-brand" class="form-control" placeholder="Brand Name">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-4">
                                    <form>
                                        <div class="mb-3">
                                                <label for="product-weight" class="form-label">Weight</label>
                                                <input type="text" id="product-weight" class="form-control" placeholder="In gm &amp; kg">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-4">
                                    <form>
                                        <label for="gender" class="form-label">Gender</label>
                                        <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-control choices__input" id="gender" data-choices="" data-choices-groups="" data-placeholder="Select Gender" hidden="" tabindex="-1" data-choice="active"><option value="" data-custom-properties="[object Object]">Select Gender</option></select><div class="choices__list choices__list--single"><div class="choices__item choices__placeholder choices__item--selectable" data-item="" data-id="1" data-value="" data-custom-properties="[object Object]" aria-selected="true">Select Gender</div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Select Gender" placeholder=""><div class="choices__list" role="listbox"><div id="choices--gender-item-choice-3" class="choices__item choices__item--choice is-selected choices__placeholder choices__item--selectable is-highlighted" role="option" data-choice="" data-id="3" data-value="" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">Select Gender</div><div id="choices--gender-item-choice-1" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="1" data-value="Men" data-select-text="Press to select" data-choice-selectable="">Men</div><div id="choices--gender-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="Other" data-select-text="Press to select" data-choice-selectable="">Other</div><div id="choices--gender-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="Women" data-select-text="Press to select" data-choice-selectable="">Women</div></div></div></div>
                                    </form>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4">
                                    <div class="mt-3">
                                        <h5 class="text-dark fw-medium">Size :</h5>
                                        <div class="d-flex flex-wrap gap-2" role="group" aria-label="Basic checkbox toggle button group">
                                                <input type="checkbox" class="btn-check" id="size-xs1">
                                                <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-xs1">XS</label>

                                                <input type="checkbox" class="btn-check" id="size-s1">
                                                <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-s1">S</label>

                                                <input type="checkbox" class="btn-check" id="size-m1">
                                                <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-m1">M</label>

                                                <input type="checkbox" class="btn-check" id="size-xl1">
                                                <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-xl1">Xl</label>

                                                <input type="checkbox" class="btn-check" id="size-xxl1">
                                                <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-xxl1">XXL</label>
                                                <input type="checkbox" class="btn-check" id="size-3xl1">
                                                <label class="btn btn-light avatar-sm rounded d-flex justify-content-center align-items-center" for="size-3xl1">3XL</label>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control bg-light-subtle" id="description" rows="7" placeholder="Short description about the product"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <form>
                                        <div class="mb-3">
                                                <label for="product-id" class="form-label">Tag Number</label>
                                                <input type="number" id="product-id" class="form-control" placeholder="#******">
                                        </div>

                                    </form>
                                </div>
                                <div class="col-lg-4">
                                    <form>
                                        <div class="mb-3">
                                                <label for="product-stock" class="form-label">Stock</label>
                                                <input type="number" id="product-stock" class="form-control" placeholder="Quantity">
                                        </div>

                                    </form>
                                </div>
                                <div class="col-lg-4">
                                    <label for="product-stock" class="form-label">Tag</label>
                                    <div class="choices" data-type="select-multiple" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-control choices__input" id="choices-multiple-remove-button" data-choices="" data-choices-removeitem="" name="choices-multiple-remove-button" multiple="" hidden="" tabindex="-1" data-choice="active"><option value="Fashion" data-custom-properties="[object Object]">Fashion</option></select><div class="choices__list choices__list--multiple"><div class="choices__item choices__item--selectable" data-item="" data-id="1" data-value="Fashion" data-custom-properties="[object Object]" aria-selected="true" data-deletable="">Fashion<button type="button" class="choices__button" aria-label="Remove item: 'Fashion'" data-button="">Remove item</button></div></div><input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="null"></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><div class="choices__list" aria-multiselectable="true" role="listbox"><div id="choices--choices-multiple-remove-button-item-choice-1" class="choices__item choices__item--choice choices__item--selectable is-highlighted" role="option" data-choice="" data-id="1" data-value="Electronics" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">Electronics</div><div id="choices--choices-multiple-remove-button-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="Headphones" data-select-text="Press to select" data-choice-selectable="">Headphones</div><div id="choices--choices-multiple-remove-button-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="Watches" data-select-text="Press to select" data-choice-selectable="">Watches</div></div></div></div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                            <h4 class="card-title">Pricing Details</h4>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <form>
                                        <label for="product-price" class="form-label">Price</label>
                                        <div class="input-group mb-3">
                                                <span class="input-group-text fs-20"><i class="bx bx-dollar"></i></span>
                                                <input type="number" id="product-price" class="form-control" placeholder="000">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-4">
                                    <form>
                                        <label for="product-discount" class="form-label">Discount</label>
                                        <div class="input-group mb-3">
                                                <span class="input-group-text fs-20"><i class="bx bxs-discount"></i></span>
                                                <input type="number" id="product-discount" class="form-control" placeholder="000">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-4">
                                    <form>
                                        <label for="product-tex" class="form-label">Tex</label>
                                        <div class="input-group mb-3">
                                                <span class="input-group-text fs-20"><i class="bx bxs-file-txt"></i></span>
                                                <input type="number" id="product-tex" class="form-control" placeholder="000">
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="p-3 bg-light mb-3 rounded">
                    <div class="row justify-content-end g-2">
                            <div class="col-lg-2">
                                <a href="#!" class="btn btn-outline-secondary w-100">Create Product</a>
                            </div>
                            <div class="col-lg-2">
                                <a href="#!" class="btn btn-primary w-100">Cancel</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection