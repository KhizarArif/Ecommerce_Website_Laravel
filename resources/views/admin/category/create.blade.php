@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    <div class="container my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> {{ isset($category->id) ? 'Update Category' : 'Create Category' }} </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>

</section>

<section class="content">

    <div class="container-fluid">
        <form action="{{ route('categories.store') }}" method="POST" id="categoryForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="id" value="0">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ isset($category->name) ? $category->name : '' }}" placeholder="Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control"
                                    value="{{ isset($category->slug) ? $category->slug : '' }}" placeholder="Slug"
                                    readonly>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <div id="image" class="dropzone dz-clickable" name="image">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="category-gallery"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control"
                                    value="{{ isset($category->status) ? $category->status : '' }}">
                                    <option {{isset($category->status) == 1 ? 'selected' : ''}} value="1"> Active
                                    </option>
                                    <option {{isset($category->status) == 0 ? 'selected' : ''}} value="0"> InActive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Show on Home </label>
                                <select name="showHome" id="showHome" class="form-control"
                                    value="{{ isset($category->showHome) ? $category->showHome : '' }}">
                                    <option {{isset($category->showHome) == 'Yes' ? 'selected' : ''}} value="Yes"> Yes
                                    </option>
                                    <option {{isset($category->showHome) == 'No' ? 'selected' : ''}} value="No"> No
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary btn-submit">
                    {{ isset($category->id) ? 'Update' : 'Create'}} </button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>

</section>
@endsection


@section('customJs')
<script>
$('#categoryForm').on('submit', function(event) {
    event.preventDefault();
    var form = $(this);
    let actionUrl = form.attr('action');
    $(".btn-submit").prop('disabled', true);
    $("#loader").show();
    $.ajax({
        url: actionUrl,
        type: "POST",
        data: new FormData(form[0]),
        contentType: false,
        processData: false,
        success: function(response) {
            $(".btn-submit").prop('disabled', false);
            $("#loader").show();
            window.location.href = "{{ route('categories.index') }}";
        },
        error: function(error) {
            $("#loader").show();
            $(".btn-submit").prop('disabled', false);
            if (error.status === 422) {
                var errors = $.parseJSON(error.responseText);
                $.each(errors['errors'], function(key, val) {
                    $("#" + key + "_error").text(val[0]);
                });
            }
        }
    })
});

$('#name').change(function() {
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

Dropzone.autoDiscover = false;
$(document).ready(function() {
    const dropzone = $("#image").dropzone({
        url: "{{ route('image.create') }}",
        maxFiles: 10,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif/, image/jpg",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
console.log("response", response);
            console.log("success", response.image_id);
            var category = `
            <div class="col-md-2" id="image-row-${response.image_id}" >
            <div class="card" >
            <input type="hidden" name="image_array[]" value="${response.image_id}">
            <img src="{{ asset('${response.ImagePath}') }}" class="card-img-top" alt="...">
                <div class="card-body"> 
                <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger btn-sm delete"> Delete </a>
                </div> 
            </div>
            </div>
        `;

            $("#category-gallery").append(category);

        },
        complete: function(file) {
            this.removeFile(file);
        }
    });

})

function deleteImage(imageId) {
    $.ajax({
        url: "{{ route('delete.image', ['id' => ':imageId']) }}".replace(':imageId', imageId),
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $("#image-row-" + imageId).remove(); // Remove image from UI
                console.log("Image deleted successfully");
            } else {
                console.error("Error deleting image: ", response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error deleting image: ", error);
        }
    });
}
</script>
@endsection