@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
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
        <input type="hidden" name="id" value="{{$product->id}}">
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
                                            placeholder="Title" value="{{$product->title}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug"> Slug </label>
                                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug"
                                            value="{{$product->slug}}" readonly>
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10"
                                            class="summernote"
                                            placeholder="Description"> {{$product->description}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>
                            <div id="image" name="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="product-gallery">
                        @if ($productImages -> isNotEmpty())
                        @foreach ($productImages as $image)
                        <div class="col-md-2" id="image-row-{{$image->id}}">
                            <div class="card">
                                <input type="hidden" name="image_array[]" value="{{$image->id}}">
                                <img src="{{ asset('uploads/product/small/'.$image->image) }}" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <a href="javascript:void(0)" onClick="deleteImage( {{ $image->id }})"
                                        class="btn btn-danger btn-sm delete"> Delete </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" id="price" class="form-control"
                                            value="{{$product->price}}" placeholder="Price">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control"
                                            value="{{$product->compare_price}}" placeholder="Compare Price">
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
                                        <input type="text" name="sku" id="sku" class="form-control" placeholder="sku"
                                            value="{{$product->sku}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control"
                                            value="{{$product->barcode}}" placeholder="Barcode">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="   ">
                                            <input type="hidden" value="No" name="track_qty" id="track_qty">
                                            <input class="" type="checkbox" id="track_qty" name="track_qty" value="Yes"
                                                {{ $product->track_qty == 'Yes' ? 'checked' : '' }}>
                                            <label for="track_qty"> Track Quantity </label>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" class="form-control"
                                            value="{{$product->qty}}" placeholder="Qty">
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
                                    <option value="1" {{ $product->status == 1 ? 'selected' : ''}}>Active</option>
                                    <option value="0" {{ $product->status == 0 ? 'selected' : ''}}>InActive</option>
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
                                    <option value="{{ $category->id }}"
                                        {{ $product->category_id == $category->id ? 'selected' : ''}}>
                                        {{ $category->name }} </option>
                                    @endforeach
                                </select>
                                <p class="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="subcategory">Sub category</label>
                                <select name="subcategory_id" id="subcategory_id" class="form-control">
                                    @foreach ($subcategories as $subcategory )
                                    <option value="{{ $subcategory->id }}"
                                        {{ $product->subcategory_id == $subcategory->id ? 'selected' : ''}}>
                                        {{ $subcategory->name }} </option>
                                    @endforeach
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
                                    <option value="{{ $brand->id }}"
                                        {{ $product->brand_id == $brand->id ? 'selected' : ''}}> {{ $brand->name }}
                                    </option>
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
                                    <option value="No" {{ $product->is_featured == 'No' ? 'selected' : ''}}>No</option>
                                    <option value="Yes" {{ $product->is_featured == 'Yes' ? 'selected' : ''}}>Yes
                                    </option>
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button class="btn btn-primary"> Update </button>
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

    $(document).ready(function() {
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

                console.log("success", response.image_id);
                var product = `
                <div class="col-md-2" id="image-row-${response.image_id}">
                    <div class="card" >
                        <input type="hidden" name="image_array[]" value="${response.image_id}">
                        <img src="{{ asset('${response.ImagePath}') }}" class="card-img-top" alt="...">
                            <div class="card-body"> 
                            <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger btn-sm delete"> Delete </a>
                            </div> 
                    </div>
                </div>
                `;

                $("#product-gallery").append(product);

            },
            complete: function(file) {
                this.removeFile(file);
            }
        });
    })

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
                $('.error').removeClass('text-danger').html('');
                $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
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


function deleteImage(imageId) { 
    console.log('Deleting image with ID:', imageId);
    $('#image-row-' + imageId).remove(); 
}
</script>
@endsection