@extends(package_resource('layouts.app'))
@section('datatables-css')
	<link href="{{ package_asset('css/app.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <table id="ajax-table" class="table table-bordered">
                <thead>
                      <tr>
                          <th>Name</th>
                          <th>Gender</th>
                      </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
{{-- Include the js that will be used to manage the datatables functionality --}}
@section('datatables-js')
	<script src="{{ package_asset('js/manifest.js') }}"></script>
	<script src="{{ package_asset('js/vendor.js') }}"></script>
	<script src="{{ package_asset('js/app.js') }}"></script>
	<script src="{{ package_asset('js/master.js') }}"></script>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            const table = $('#ajax-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ route('datatables.users') }}`,
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        _token: `{{ csrf_token() }}`
                    }
                },
                'columns': [
                    { data : 'name', title : 'Name', searchable : false, visible : true },
                    { data : 'gender', title : 'Gender', searchable : false, visible : true },
                ]	
            });
        })
    </script>
@endsection