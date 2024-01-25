@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>

</section>

<section class="content">
    <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data" id="productForm">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            placeholder="Title">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug"> Slug </label>
                                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug"
                                            readonly>
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10"
                                            class="summernote" placeholder="Description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>
                            <input type="hidden" name="image_id" id="image_id">
                            <div id="image" name="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="row" id="product-gallery">

                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" id="price" class="form-control"
                                            placeholder="Price">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control"
                                            placeholder="Compare Price">
                                        <p class="text-muted mt-3">
                                            To show a reduced price, move the product’s original price into Compare at
                                            price. Enter a lower value into Price.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input type="text" name="sku" id="sku" class="form-control" placeholder="sku">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control"
                                            placeholder="Barcode">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" value="No" name="track_qty" id="track_qty">
                                            <input class="custom-control-input" type="checkbox" id="track_qty"
                                                name="track_qty" value="Yes" checked>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" class="form-control"
                                            placeholder="Qty">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4  mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value=""> Select Category... </option>
                                    @foreach ($categories as $category )
                                    <option value="{{ $category->id }}"> {{ $category->name }} </option>
                                    @endforeach
                                </select>
                                <p class="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="subcategory">Sub category</label>
                                <select name="subcategory_id" id="subcategory_id" class="form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value=""> Select Brand... </option>
                                    @foreach ($brands as $brand )
                                    <option value="{{ $brand->id }}"> {{ $brand->name }} </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Featured product</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button class="btn btn-primary">Create</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>

</section>
@endsection


@section('customJs')
<script>
Dropzone.autoDiscover = false;
$(function() {
    // Summernote
    $('.summernote').summernote({
        height: '300px'
    });

        const dropzone = $("#image").dropzone({
            
            url: "{{ route('image.create') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) { 
                // $("#image_id").val(response.id);
                console.log("response", response.ImagePath);
                console.log("response", response);

                var html = `
                <div class="card" style="width: 18rem;">
                    <img src="${response.ImagePath}" class="card-img-top" alt="...">
                        <div class="card-body"> 
                         <a href="#" class="btn btn-danger btn-sm delete"> Delete </a>
                        </div> 
                </div>`;

                $("#product-gallery").append(html);
            
            },error: function(error) {

                console.log("error", error);
            }
        });

});


$('#title').change(function() {
    var element = $(this);
    $.ajax({
        type: "get",
        url: "{{ route('getSlug') }}",
        data: {
            title: element.val()
        },
        dataType: "json",
        success: function(response) {
            if (response["status"] == true) {
                $("#slug").val(response["slug"]);
            }
        }
    });
});

$('#category_id').change(function() {
    var categoryId = $(this).val();
    var subcategorySelect = $('#subcategory_id');
    $.ajax({
        type: "get",
        data: {
            category_id: categoryId
        },
        url: "{{ route('getSubCategory') }}",
        dataType: "json",
        success: function(response) {
            if (response.status == true) {
                subcategorySelect.empty();
                $.each(response.subCategories, function(index, subcategory) {
                    subcategorySelect.append($('<option>', {
                        value: subcategory.id,
                        text: subcategory.name,
                    }));
                });
            };
        }
    });
});

$("#productForm").submit(function(e) {
    e.preventDefault();
    var formArray = $(this).serializeArray();
    $.ajax({
        type: "post",
        data: formArray,
        url: "{{ route('products.store') }}",
        dataType: "json",
        success: function(response) {
            if (response.status) {
                // $(".error-message").html("");
                window.location.href = "{{ route('products.index') }}";

            } else {
                var errors = response.message;
                $('.error').removeClass('text-danger').html('');
                $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
                $.each(errors, function(key, value) {
                    $(`#${key}`).addClass('is_invalid').siblings('p').addClass(
                        'text-danger').html(value);
                });
            }
        },
        error: function() {
            console.log("error");
        }
    });
});
</script>
@endsection