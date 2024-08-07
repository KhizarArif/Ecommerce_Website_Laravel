@extends('admin.layouts.app')


@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Categories</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">New Category</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">

                <!-- Reset Button Start -->
                <div class="card-title">
                    <form action="">
                        <button type="button" class="btn btn-danger"> <a href="{{ route('categories.index')}}"
                                class="text-white text-decoration-none"> Reset </a> </button>
                    </form>
                </div>
                <!-- Reset Button End -->

                <!-- Import And Export Functionality Start -->
                <!-- Import And Export Functionality End -->


                <!-- Export Button Start  -->
                <button class="btn btn-primary mx-3" >
                    <a href="{{route('view-pdf')}}" class="text-white">
                        <i class="fa fa-thin fa-eye"></i>
                        View PDF
                    </a>
                </button>
                <!-- Export Button End  -->
                <!-- Search  Start -->
                <div class="card-tools">
                    <form action="">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="search" name="table_search" class="form-control float-right"
                                placeholder="Search" value="{{ request()->get('table_search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Search  End -->

            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60"> ID </th>
                            <th width="80"></th>
                            <th> Name </th>
                            <th> Slug </th>
                            <th width="100"> Status </th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($categories->isNotEmpty())
                        @foreach ($categories as $category)
                        <tr>
                            <td> {{ $category->id }} </td>
                            <td>
                                @if (!empty($category->categoryImages->first()->image)) 
                                <img src="{{ asset('uploads/category/small/'.$category->categoryImages->first()->image)}}"
                                    class="img-thumbnail" width="50">
                                @endif
                            </td>
                            <td> {{ $category->name }} </td>
                            <td> {{ $category->slug }} </td>
                            <td>
                                @if ($category->status == 1)
                                <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @else
                                <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('categories.edit', $category->id) }}">
                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                        </path>
                                    </svg>
                                </a>

                                <a href="#" class="btn btn-light btn-sm" onclick="deleteItem('{{$category->id}}')">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>

                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5"> Record Not Found </td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <ul class="pagination pagination m-0 float-right">
                    <li class="page-item"><a class="page-link" href="#">«</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">»</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection


@section('customJs')
<script>
    function deleteItem(categoryId) {
        var url = "{{ route('categories.delete', 'ID') }}";
        var newUrl = url.replace('ID', categoryId);
        Swal.fire({
            title: "Do you want to save the changes?",
            text: "Data will be lost if you don't save it",
            showDenyButton: true,
            confirmButtonText: "Delete",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    data: {},
                    url: newUrl,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        Swal.fire("Deleted Successfully!", "", "success");
                        // window.location.reload();
                        window.location.href = "{{ route('categories.index') }}";
                    }
                });
            }
        });

    }
</script>
@endsection