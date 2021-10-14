@extends('layouts.user')

@section('title')
    Home
@endsection

@section('_content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h3 class="font-weight-bold">Recent Projects</h3>
            </div>
        </div>
        <div class="row">
            @foreach (range(1, 3) as $item)
                <div class="col-4">
                    <a href="#">
                        <div class="card">
                            <img class="card-img" src="https://mdbcdn.b-cdn.net/img/Photos/Others/intro1.jpg">
                            <div class="card-img-overlay">
                                <h4 class="text-dark">Lorem.</h4>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="row mt-1">
            <div class="col-12">
                <a href="#" class="text-primary">See All Projects</a>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="font-weight-bold">Assigned to me</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-hover table-bordered table-sm" id="datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('_scripts')
    <script>
        $(function () {
            $('#datatable').DataTable({
                searching: false,
                processing: true,
                serverside: false,
                ajax: {
                    url: '{{ route("ajax") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', searchable: true, orderable: true},
                    {data: 'title'},
                    {data: 'description'}
                ]
            })
        });
    </script>
@endsection
