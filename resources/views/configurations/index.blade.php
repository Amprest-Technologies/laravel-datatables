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
            <table class="table table-bordered table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th class="w-75">Table Identifier</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($configurations as $configuration)
                        <tr>
                            <td>{{ $configuration->identifier }}</td>
                            <td class="text-center">
                                <a href="{{ route('datatables.configurations.edit', [
                                    'configuration' => $configuration
                                ]) }}" class="btn btn-primary btn-sm" href="">Edit</a>
                                <a href="{{ route('datatables.configurations.edit', [
                                    'configuration' => $configuration
                                ]) }}" class="btn btn-info btn-sm" href="">Disable</a>
                                <a href="{{ route('datatables.configurations.edit', [
                                    'configuration' => $configuration
                                ]) }}" class="btn btn-danger btn-sm" href="">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
