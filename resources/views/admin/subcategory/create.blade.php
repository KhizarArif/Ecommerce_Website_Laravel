@extends('admin.layouts.app')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="subcategory.html" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="{{ route('subcategories.store') }}" id="subcategoryForm" name="subcategoryForm">
            @csrf
            <input type="hidden" name="id" id="id" value="{{ isset($subcategory->id) ? $subcategory->id : 0 }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    @foreach ($categories as $category )
                                    <option value="{{ $category->id }}" {{ isset($subcategory->category_id) &&
                                        $subcategory->category_id == $category->id ? 'selected' : ''}}> {{
                                        $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name"
                                    value="{{ isset($subcategory->name) ? $subcategory->name : '' }}"
                                    class="form-control" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug"
                                    value="{{ isset($subcategory->slug) ? $subcategory->slug : '' }}"
                                    class="form-control" placeholder="Slug" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status"> Status </label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ isset($subcategory->status) && $subcategory->status == 1 ? 'selected' :
                                        ''}} value="1"> Active </option>
                                    <option {{ isset($subcategory->status) && $subcategory->status == 0 ? 'selected' :
                                        ''}} value="0"> InActive </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-primary"> {{ isset($subcategory->id) ? 'Update' : 'Create' }} </button>
                <a href="subcategory.html" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection


@section('customJs')
<script>
$('#subcategoryForm').on('submit', function(event) {
    event.preventDefault();
    var form = $(this);
    let actionUrl = form.attr('action');
    $.ajax({
        url: actionUrl,
        type: "POST",
        data: new FormData(form[0]),
        contentType: false,
        processData: false,
        success: function(response) {
            window.location.href = "{{ route('subcategories.index') }}";
        },
        error: function(error) {
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
</script>
@endsection