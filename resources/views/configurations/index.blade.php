@extends(package_resource('layouts.app'))
@section('title', 'List of all table configurations')
@section('content')
    <section class="row">
        <div class="col-lg-12">
            <form action="{{ route('datatables.configurations.store') }}" method="post">
                @csrf
                <label class="mb-0 font-weight-bold" for="">List a new table</label>
                <div class="input-group mb-1 mt-0">
                    <input 
                        name="identifier" 
                        type="text" 
                        class="form-control" 
                        placeholder="The Table's Unique Identifier" 
                        required
                        value="{{ old('identifier') ?: '' }}"
                    >
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">List Table</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="row">
        <div class="col-lg-12">
            @if(is_array($configurations) && count($configurations))
                <table class="table table-bordered table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th class="w-75">Table Identifier</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($configurations as $configuration)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $configuration->identifier }}</td>
                                <td class="text-center">
                                    <a href="{{ route('datatables.configurations.edit', [
                                        'configuration' => $configuration->identifier
                                    ]) }}" class="btn btn-primary btn-sm" href="">Edit</a>
                                    @if($configuration->deleted_at)
                                        <form class="d-inline" method="post" action="{{ route('datatables.configurations.restore', [ 'configuration' => $configuration->identifier ]) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm">Activate</button>
                                        </form>
                                    @else
                                        <form class="d-inline" method="post" action="{{ route('datatables.configurations.trash', [ 'configuration' => $configuration->identifier ]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-info btn-sm">Disable</button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal-{{ $configuration->identifier }}">
                                        Delete
                                    </button>
                                    <div class="modal fade" id="delete-modal-{{ $configuration->identifier }}" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="delete-modal-label">Modal title</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-left">
                                                    Are you sure you want to delete this table listing? The process is irreversible.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-success" data-dismiss="modal">No, Do not delete</button>
                                                    <form class="d-inline" method="post" action="{{ route('datatables.configurations.destroy', [ 'configuration' => $configuration->identifier ]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h5 class="mt-3">No table information is available.</h5>
            @endif
        </div>
    </section>
@endsection
