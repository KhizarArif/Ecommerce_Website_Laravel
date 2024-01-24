@extends('admin.layouts.app')


@section('content')
<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Brand</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{ route('brands.index') }}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
                    
				</section>
                
				<section class="content">
                    
					<div class="container-fluid">
						<form action="{{ route('brands.store') }}" method="POST" id="brandForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ isset($brand) ? $brand->id : '' }}" >
                        <div class="card">
							<div class="card-body">								
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Name</label>
											<input type="text" name="name" id="name" value="{{ isset($brand) ? $brand->name : '' }}" class="form-control" placeholder="Name">	
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="slug">Slug</label>
											<input type="text" name="slug" id="slug" value="{{ isset($brand) ? $brand->slug : '' }}" class="form-control" placeholder="Slug">	
										</div>
									</div>									
									<div class="col-md-6">
										<div class="mb-3">
											<label for="status"> Status </label>
											<select name="status" id="status" class="form-control"  >
                                            <option value="1" {{ isset($brand->status) == 1 ? 'selected' : ''}} > Active </option>
											<option value="0" {{ isset($brand->status) == 0 ? 'selected' : ''}}> InActive </option>
                                            </select>
										</div>
									</div>									
								</div>
							</div>							
						</div>
						<div class="pb-5 pt-3">
							<button class="btn btn-primary"> {{ isset($brand) ? 'Update' : 'create' }} </button>
							<a href="{{ route('brands.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
						</div>
                        </form>
					</div>
                    
				</section>
@endsection

@section('customJs')
<script>
$('#brandForm').on('submit', function(event) {
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
            window.location.href = "{{ route('brands.index') }}";
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