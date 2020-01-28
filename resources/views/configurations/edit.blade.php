@extends(package_resource('layouts.app'))
@section('title', 'Edit this table\'s configurations')
@section('content')
    <section class="row mb-3">
        <div class="col-lg-12">
            <form action="{{ route('datatables.configurations.store') }}" method="post">
                @csrf
                <label class="mb-0 font-weight-bold" for="">List a new column</label>
                <div class="input-group mb-1 mt-0">
                    <input 
                        name="name" 
                        type="text" 
                        class="form-control" 
                        placeholder="The Table's Unique Column Name" 
                        required
                        value="{{ old('name') ?: '' }}"
                    >
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">List Column</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-12">
            @if($columns = $configuration->column)
                <table class="table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($columns as $column)
                            <tr>
                                <td>{{ $column }}</td>
                            </tr>
                        @endforeach
                    </tbody>                
                </table>
            @else
                <h5 class="my-3">No columns have been specified.</h5>            
            @endif
        </div>
    </section>
    <hr class="mb-5">
    <section class="row">
        <div class="col-lg-12">
            <form action="{{ route('datatables.configurations.update', [ 'configuration' => $configuration ]) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">General Configurations</h5>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Identifier</label>
                        <input 
                            name="configurations[id]" 
                            type="text" 
                            class="form-control @error('configurations.id') is-invalid @enderror" 
                            value="{{ $configurations['id'] }}"
                        >
                        @error('configurations.id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Classes</label>
                        <input 
                            name="configurations[classes]" 
                            type="text" 
                            class="form-control @error('configurations.classes') is-invalid @enderror" 
                            value="{{ $configurations['classes'] }}"
                        >
                        @error('configurations.classes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Info</label>
                        @php $info = old('configurations.info') ?: ( $configurations['info'] ?: '' ) @endphp
                        <select name="configurations[info]" class="form-control @error('configurations.info') is-invalid @enderror">
                            <option {{ $info ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$info ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.info')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Ordering</label>
                        @php $ordering = old('configurations.ordering') ?: ( $configurations['ordering'] ?: '' ) @endphp
                        <select name="configurations[ordering]" class="form-control @error('configurations.ordering') is-invalid @enderror">
                            <option {{ $ordering ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$ordering ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.ordering')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Searching</label>
                        @php $searching = old('configurations.searching') ?: ( $configurations['searching'] ?: '' ) @endphp
                        <select name="configurations[searching]" class="form-control @error('configurations.searching') is-invalid @enderror">
                            <option {{ $searching ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$searching ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.searching')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Row Indexes</label>
                        @php $rowIndexes = old('configurations.rowIndexes') ?: ( $configurations['rowIndexes'] ?: '' ) @endphp
                        <select name="configurations[rowIndexes]" class="form-control @error('configurations.rowIndexes') is-invalid @enderror">
                            <option {{ $rowIndexes ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$rowIndexes ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.rowIndexes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Column Visibility</label>
                        @php $columnVisibility = old('configurations.columnVisibility') ?: ( $configurations['columnVisibility'] ?: '' ) @endphp
                        <select name="configurations[columnVisibility]" class="form-control @error('configurations.columnVisibility') is-invalid @enderror">
                            <option {{ $columnVisibility ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$columnVisibility ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.columnVisibility')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Custom Export Title  </label>
                        @php $customTitle = old('configurations.customTitle') ?: ( $configurations['customTitle'] ?: '' ) @endphp
                        <select name="configurations[customTitle]" class="form-control @error('configurations.customTitle') is-invalid @enderror">
                            <option {{ $customTitle ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$customTitle ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.customTitle')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- The filtering section --}}
                <div class="form-row">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">General Configurations</h5>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-lg-12">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection