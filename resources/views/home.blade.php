@extends(package_resource('layouts.app'))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            @datatable( [ 'id'=> 'users-table' ] )
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>Male</td>
                    </tr>
                    <tr>
                        <td>Alvin Kaburu</td>
                        <td>Female</td>
                    </tr>
                    <tr>
                        <td>Alvin Kaburu</td>
                        <td>Male</td>
                    </tr>
                    <tr>
                        <td>Alvin Kaburu</td>
                        <td>Male</td>
                    </tr>
                    <tr>
                        <td>Alvin Kaburu</td>
                        <td>Male</td>
                    </tr>
                    <tr>
                        <td>Alvin Kaburu</td>
                        <td>Male</td>
                    </tr>
                </tbody>
            @enddatatable
        </div>
    </div>
@endsection
