@extends('admin.layouts.app')


@section('content')
    <section class="content-header">
        <div class="container my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> {{ isset($page->id) ? 'Update Page' : 'Create Page' }} </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('pages.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

    </section>

    <section class="content">

        <div class="container-fluid">
            <form action="{{ route('pages.store') }}" method="POST" id="pageForm">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ isset($page->id) ? $page->id : 0 }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ isset($page->name) ? $page->name : '' }}" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        value="{{ isset($page->slug) ? $page->slug : '' }}" placeholder="Slug" readonly>
                                    <p></p>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content">Content</label>
                                    <textarea value={{ isset($page->content) ? $page->content : '' }} name="content" id="content" class="summernote" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control"
                                        value="{{ isset($page->status) ? $page->status : '' }}">
                                        <option {{ isset($page->status) == 1 ? 'selected' : '' }} value="1"> Active
                                        </option>
                                        <option {{ isset($page->status) == 0 ? 'selected' : '' }} value="0"> InActive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row"></div>

                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary"> {{ isset($page->id) ? 'Update' : 'Create' }} </button>
                    <a href="{{ route('pages.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>

    </section>
@endsection


@section('customJs')
    <script>
        $('.summernote').summernote({
            height: '300px'
        });

        $('#pageForm').on('submit', function(event) {
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
                    window.location.href = "{{ route('pages.index') }}";
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
