@extends('admin.layouts.app')


@section('content')
    <section class="content-header">
        <div class="container my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> {{ isset($exhibition->id) ? 'Update Exhibition' : 'Create Exhibition' }} </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('exhibitions.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

    </section>

    <section class="content">
        <div id="loader" style="display: none;">
            <p>Loading...</p>
        </div>
        <div class="container-fluid">
            <form action="{{ route('exhibitions.store') }}" method="POST" id="exhibitionForm"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id"
                    value="{{ isset($exhibition->id) ? $exhibition->id : 0 }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Exhibitions Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ isset($exhibition->name) ? $exhibition->name : '' }}" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        value="{{ isset($exhibition->slug) ? $exhibition->slug : '' }}" placeholder="Slug"
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
                                <div class="row" id="product-gallery">
                                    @if ($exhibitionImages->isNotEmpty())
                                        @foreach ($exhibitionImages as $image)
                                            <div class="col-md-2" id="image-row-{{ $image->id }}">
                                                <div class="card">
                                                    <input type="hidden" name="image_array[]" value="{{ $image->id }}">
                                                    <img src="{{ asset('uploads/exhibition/small/' . $image->image) }}"
                                                        class="card-img-top" alt="...">
                                                    <div class="card-body">
                                                        <a href="javascript:void(0)"
                                                            onClick="deleteImage( '{{ $image->id }}')"
                                                            class="btn btn-danger btn-sm delete"> Delete </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control"
                                        value="{{ isset($exhibition->status) ? $exhibition->status : '' }}">
                                        <option {{ isset($exhibition->status) == 1 ? 'selected' : '' }} value="1">
                                            Active
                                        </option>
                                        <option {{ isset($exhibition->status) == 0 ? 'selected' : '' }} value="0">
                                            InActive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Show on Home </label>
                                    <select name="showHome" id="showHome" class="form-control"
                                        value="{{ isset($exhibition->showHome) ? $exhibition->showHome : '' }}">
                                        <option {{ isset($exhibition->showHome) == 'Yes' ? 'selected' : '' }}
                                            value="Yes"> Yes
                                        </option>
                                        <option {{ isset($exhibition->showHome) == 'No' ? 'selected' : '' }}
                                            value="No"> No
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary btn-submit"> Update </button>
                    <a href="{{ route('exhibitions.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>

    </section>
@endsection


@section('customJs')
    <script>
        const dropzone = $("#image").dropzone({

            url: "{{ route('exhibitions.updateExhibitionImage') }}",
            maxFiles: 10,
            paramName: 'image',
            params: {
                'exhibition_id': '{{ $exhibition->id }}'
            },
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif, image/jpg",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                console.log("success", response);
                var product = `
                        <div class="col-md-2 d-flex" id="image-row-${response.image_id}">
                            <div class="card" >
                                <input type="hidden" name="image_array[]" value="${response.image_id}">
                                <img src="{{ '${response.ImagePath}' }}" class="card-img-top" alt="...">
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

        $('#exhibitionForm').on('submit', function(event) {
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
                    window.location.href = "{{ route('exhibitions.index') }}";
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


        function deleteImage(imageId) {
            $.ajax({
                type: 'DELETE',
                data: {
                    id: imageId
                },
                url: "{{ route('exhibitions.deleteExhibitionImage') }}",
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
