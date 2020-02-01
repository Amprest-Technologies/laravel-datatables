@extends(package_resource('layouts.app'))
@section('title', 'Edit this table\'s configurations')
@section('content')
    <section class="row mb-3">
        <div class="col-lg-12">
            <form action="{{ route('datatables.columns.store', [ 'configuration' => $configuration ]) }}" method="post">
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
            <div class="my-3">
                @if(count($columns = $configuration->columns))
                    <table class="table table-sm table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Column Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($columns as $column)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $column }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal-{{ $loop->iteration }}">
                                            Delete
                                        </button>
                                        <div class="modal fade" id="delete-modal-{{ $loop->iteration }}" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="delete-modal-label">Modal title</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-left">
                                                        Are you sure you want to delete this column? The process is irreversible.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-success" data-dismiss="modal">No, Do not delete</button>
                                                        <form class="d-inline" method="post" action="{{ route('datatables.columns.destroy', [ 'configuration' => $configuration, 'column' => $column ]) }}">
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
                    <h5>No columns have been specified.</h5>  
                    <hr class="mb-5">
                @endif
            </div>
        </div>
    </section>
    <section class="row">
        <div class="col-lg-12">
            <form action="{{ route('datatables.configurations.update', [ 'configuration' => $configuration ]) }}" method="post">
                @csrf
                @method('PUT')
                {{-- The general section --}}
                <div class="form-row">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">General</h5>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Identifier</label>
                        <input 
                            name="configurations[id]" 
                            type="text" 
                            class="form-control @error('configurations.id') is-invalid @enderror" 
                            value="{{ old('configurations.id') ?: $configurations['id'] }}"
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
                            value="{{ old('configurations.classes') ?: $configurations['classes'] }}"
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

                <div class="form-row mt-4">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">Ajax Configurations</h5>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Enabled</label>
                        @php $enabled = old('configurations.ajax.enabled') ?: ( $configurations['ajax']['enabled'] ?: '' ) @endphp
                        <select name="configurations[ajax][enabled]" class="form-control @error('configurations.ajax.enabled') is-invalid @enderror">
                            <option {{ $enabled ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$enabled ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.ajax.enabled')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">End Point Route Name</label>
                        <input 
                            name="configurations[ajax][options][route]" 
                            type="text" 
                            class="form-control @error('configurations.ajax.options.route') is-invalid @enderror" 
                            value="{{ old('configurations.ajax.options.route') ?: $configurations['ajax']['options']['route'] }}"
                        >
                        @error('configurations.ajax.options.route')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- The Column filtering, sorting, display section --}}
                <div class="form-row mt-4">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">Column Configurations</h5>
                    </div>
                    <div class="form-group col-lg-12">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Column</th>
                                    <th>Title</th>
                                    <th>Server Name</th>
                                    <th>Type</th>
                                    <th>Sorting</th>
                                    <th>Hidden</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($columns as $column)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $column }}</td>
                                        <td>
                                            @php 
                                                $value = collect($configurations['filters'])
                                                    ->where('name', Str::slug(strtolower($column), '_'))
                                                    ->first();
                                                
                                            @endphp
                                            <input 
                                                name="columns[{{ $column }}][title]" 
                                                type="text" 
                                                class="form-control form-control-sm"
                                                value="{{ $value['title'] ?? null }}"
                                            >
                                        </td>
                                        <td>
                                            <input 
                                                name="columns[{{ $column }}][server]" 
                                                type="text" 
                                                class="form-control form-control-sm"
                                                value="{{ $value['server'] ?? null }}"
                                            >
                                        </td>
                                        <td>
                                            <select name="columns[{{ $column }}][type]" class="form-control form-control-sm">
                                                <option {{ collect($configurations['filters'])
                                                    ->where('name', Str::slug(strtolower($column), '_'))
                                                    ->where('type', null)
                                                    ->first() ? 'selected' : ''}} value="">None</option>
                                                <option {{ collect($configurations['filters'])
                                                    ->where('name', Str::slug(strtolower($column), '_'))
                                                    ->where('type', 'input')
                                                    ->first() ? 'selected' : ''}} value="input">Input</option>
                                                <option {{ collect($configurations['filters'])
                                                    ->where('name', Str::slug(strtolower($column), '_'))
                                                    ->where('type', 'select')
                                                    ->first() ? 'selected' : ''}} value="select">Select</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="columns[{{ $column }}][sorting]" class="form-control form-control-sm">
                                                <option {{ collect($configurations['sorting'])
                                                    ->where('column', Str::slug(strtolower($column), '_'))
                                                    ->where('order', '!=', 'asc')
                                                    ->where('order', '!=', 'desc')
                                                    ->first() ? 'selected' : ''}} value="">None</option>
                                                <option {{ collect($configurations['sorting'])
                                                    ->where('column', Str::slug(strtolower($column), '_'))
                                                    ->where('order', 'asc')
                                                    ->first() ? 'selected' : ''}} value="asc">Ascending</option>
                                                <option {{ collect($configurations['sorting'])
                                                    ->where('column', Str::slug(strtolower($column), '_'))
                                                    ->where('order', 'desc')
                                                    ->first() ? 'selected' : ''}} value="desc">Descending</option>
                                            </select>
                                        </td>
                                        <td>
                                            @php $hidden = in_array(Str::slug(strtolower($column), '_'), $configurations['hiddenColumns']); @endphp
                                            <select name="columns[{{ $column }}][hidden]" class="form-control form-control-sm">
                                                <option {{ $hidden ? 'selected' : '' }} value="1">True</option>
                                                <option {{ $hidden ? '' : 'selected' }} value="0">False</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- The Exports section - Print --}}
                <div class="form-row mt-4">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold mb-4">Table Exports</h5>
                        <h5 class="font-weight-bold">Print</h5>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.print.enabled') ?: ( $configurations['exports']['print']['enabled'] ?: '' ) @endphp
                        <select name="configurations[exports][print][enabled]" class="form-control @error('configurations.exports.print.enabled') is-invalid @enderror">
                            <option {{ $enabled ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$enabled ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.print.enabled')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Header</label>
                        @php $header = old('configurations.exports.print.options.header') ?: ( $configurations['exports']['print']['options']['header'] ?: '' ) @endphp
                        <select name="configurations[exports][print][options][header]" class="form-control @error('configurations.exports.print.options.header') is-invalid @enderror">
                            <option {{ $header ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$header ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.print.options.header')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Footer</label>
                        @php $footer = old('configurations.exports.print.options.footer') ?: ( $configurations['exports']['print']['options']['footer'] ?: '' ) @endphp
                        <select name="configurations[exports][print][options][footer]" class="form-control @error('configurations.exports.print.options.footer') is-invalid @enderror">
                            <option {{ $footer ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$footer ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.print.options.footer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Auto Print</label>
                        @php $autoPrint = old('configurations.exports.print.options.autoPrint') ?: ( $configurations['exports']['print']['options']['autoPrint'] ?: '' ) @endphp
                        <select name="configurations[exports][print][options][autoPrint]" class="form-control @error('configurations.exports.print.options.autoPrint') is-invalid @enderror">
                            <option {{ $autoPrint ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$autoPrint ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.print.options.autoPrint')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Page Size</label>
                        @php $pageSize = old('configurations.exports.print.options.pageSize') ?: ( $configurations['exports']['print']['options']['pageSize'] ?: '' ) @endphp
                        <select name="configurations[exports][print][options][pageSize]" class="form-control @error('configurations.exports.print.options.pageSize') is-invalid @enderror">
                            <option {{ $pageSize == 'A3' ? 'selected' : '' }} value="A3">A3</option>
                            <option {{ $pageSize == 'A4' ? 'selected' : '' }} value="A4">A4</option>
                            <option {{ $pageSize == 'A5' ? 'selected' : '' }} value="A5">A5</option>
                            <option {{ $pageSize == 'LEGAL' ? 'selected' : '' }} value="LEGAL">LEGAL</option>
                            <option {{ $pageSize == 'LETTER' ? 'selected' : '' }} value="LETTER">LETTER</option>
                            <option {{ $pageSize == 'TABLOID' ? 'selected' : '' }} value="TABLOID">TABLOID</option>
                        </select>
                        @error('configurations.exports.print.options.pageSize')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Orientation</label>
                        @php $orientation = old('configurations.exports.print.options.orientation') ?: ( $configurations['exports']['print']['options']['orientation'] ?: '' ) @endphp
                        <select name="configurations[exports][print][options][orientation]" class="form-control @error('configurations.exports.print.options.orientation') is-invalid @enderror">
                            <option {{ $orientation == 'landscape' ? 'selected' : '' }} value="landscape">Landscape</option>
                            <option {{ $orientation == 'portrait' ? 'selected' : '' }} value="portrait">Portrait</option>
                        </select>
                        @error('configurations.exports.print.options.orientation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Button Text</label>
                        <input 
                            name="configurations[exports][print][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.text') ?: $configurations['exports']['print']['options']['text'] }}"
                        >
                        @error('configurations.exports.print.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Document Title</label>
                        <input 
                            name="configurations[exports][print][options][title]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.title') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.title') ?: $configurations['exports']['print']['options']['title'] }}"
                        >
                        @error('configurations.exports.print.options.title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Document Logo</label>
                        <input 
                            name="configurations[exports][print][options][logo]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.logo') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.logo') ?: $configurations['exports']['print']['options']['logo'] }}"
                        >
                        @error('configurations.exports.print.options.logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Message Top</label>
                        <input 
                            name="configurations[exports][print][options][messageTop]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.messageTop') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.messageTop') ?: $configurations['exports']['print']['options']['messageTop'] }}"
                        >
                        @error('configurations.exports.print.options.messageTop')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Message Bottom</label>
                        <input 
                            name="configurations[exports][print][options][messageBottom]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.messageBottom') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.messageBottom') ?: $configurations['exports']['print']['options']['messageBottom'] }}"
                        >
                        @error('configurations.exports.print.options.messageBottom')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.print.options.exportOptions.columns') ?: ( $configurations['exports']['print']['options']['exportOptions']['columns'] ?: '' ) @endphp
                        <select name="configurations[exports][print][options][exportOptions][columns]" class="form-control @error('configurations.exports.print.options.exportOptions.columns') is-invalid @enderror">
                            <option {{ $columns == '' ? 'selected' : '' }} value="">All Columns</option>
                            <option {{ $columns == ':visible' ? 'selected' : '' }} value=":visible">Only Visible Columns</option>
                        </select>
                        @error('configurations.exports.print.options.exportOptions.columns')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- The Exports section - PDF --}}
                <div class="form-row mt-4">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">PDF</h5>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.pdf.enabled') ?: ( $configurations['exports']['pdf']['enabled'] ?: '' ) @endphp
                        <select name="configurations[exports][pdf][enabled]" class="form-control @error('configurations.exports.pdf.enabled') is-invalid @enderror">
                            <option {{ $enabled ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$enabled ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.pdf.enabled')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Header</label>
                        @php $header = old('configurations.exports.pdf.options.header') ?: ( $configurations['exports']['pdf']['options']['header'] ?: '' ) @endphp
                        <select name="configurations[exports][pdf][options][header]" class="form-control @error('configurations.exports.pdf.options.header') is-invalid @enderror">
                            <option {{ $header ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$header ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.pdf.options.header')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Footer</label>
                        @php $footer = old('configurations.exports.pdf.options.footer') ?: ( $configurations['exports']['pdf']['options']['footer'] ?: '' ) @endphp
                        <select name="configurations[exports][pdf][options][footer]" class="form-control @error('configurations.exports.pdf.options.footer') is-invalid @enderror">
                            <option {{ $footer ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$footer ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.pdf.options.footer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Auto Download</label>
                        @php $autoDownload = old('configurations.exports.pdf.options.autoDownload') ?: ( $configurations['exports']['pdf']['options']['autoDownload'] ?: '' ) @endphp
                        <select name="configurations[exports][pdf][options][autoDownload]" class="form-control @error('configurations.exports.pdf.options.autoDownload') is-invalid @enderror">
                            <option {{ $autoDownload ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$autoDownload ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.pdf.options.autoDownload')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">File Name</label>
                        <input 
                            name="configurations[exports][pdf][options][filename]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.filename') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.filename') ?: $configurations['exports']['pdf']['options']['filename'] }}"
                        >
                        @error('configurations.exports.pdf.options.filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Extension</label>
                        <input 
                            name="configurations[exports][pdf][options][extension]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.extension') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.extension') ?: $configurations['exports']['pdf']['options']['extension'] }}"
                        >
                        @error('configurations.exports.pdf.options.extension')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Page Size</label>
                        @php $pageSize = old('configurations.exports.pdf.options.pageSize') ?: ( $configurations['exports']['pdf']['options']['pageSize'] ?: '' ) @endphp
                        <select name="configurations[exports][pdf][options][pageSize]" class="form-control @error('configurations.exports.pdf.options.pageSize') is-invalid @enderror">
                            <option {{ $pageSize == 'A3' ? 'selected' : '' }} value="A3">A3</option>
                            <option {{ $pageSize == 'A4' ? 'selected' : '' }} value="A4">A4</option>
                            <option {{ $pageSize == 'A5' ? 'selected' : '' }} value="A5">A5</option>
                            <option {{ $pageSize == 'LEGAL' ? 'selected' : '' }} value="LEGAL">LEGAL</option>
                            <option {{ $pageSize == 'LETTER' ? 'selected' : '' }} value="LETTER">LETTER</option>
                            <option {{ $pageSize == 'TABLOID' ? 'selected' : '' }} value="TABLOID">TABLOID</option>
                        </select>
                        @error('configurations.exports.pdf.options.pageSize')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Orientation</label>
                        @php $orientation = old('configurations.exports.pdf.options.orientation') ?: ( $configurations['exports']['pdf']['options']['orientation'] ?: '' ) @endphp
                        <select name="configurations[exports][pdf][options][orientation]" class="form-control @error('configurations.exports.pdf.options.orientation') is-invalid @enderror">
                            <option {{ $orientation == 'landscape' ? 'selected' : '' }} value="landscape">Landscape</option>
                            <option {{ $orientation == 'portrait' ? 'selected' : '' }} value="portrait">Portrait</option>
                        </select>
                        @error('configurations.exports.pdf.options.orientation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Button Text</label>
                        <input 
                            name="configurations[exports][pdf][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.text') ?: $configurations['exports']['pdf']['options']['text'] }}"
                        >
                        @error('configurations.exports.pdf.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Document Title</label>
                        <input 
                            name="configurations[exports][pdf][options][title]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.title') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.title') ?: $configurations['exports']['pdf']['options']['title'] }}"
                        >
                        @error('configurations.exports.pdf.options.title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Document Logo</label>
                        <input 
                            name="configurations[exports][pdf][options][logo]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.logo') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.logo') ?: $configurations['exports']['pdf']['options']['logo'] }}"
                        >
                        @error('configurations.exports.pdf.options.logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Message Top</label>
                        <input 
                            name="configurations[exports][pdf][options][messageTop]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.messageTop') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.messageTop') ?: $configurations['exports']['pdf']['options']['messageTop'] }}"
                        >
                        @error('configurations.exports.pdf.options.messageTop')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Message Bottom</label>
                        <input 
                            name="configurations[exports][pdf][options][messageBottom]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.messageBottom') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.messageBottom') ?: $configurations['exports']['pdf']['options']['messageBottom'] }}"
                        >
                        @error('configurations.exports.pdf.options.messageBottom')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.pdf.options.exportOptions.columns') ?: ( $configurations['exports']['pdf']['options']['exportOptions']['columns'] ?: '' ) @endphp
                        <select name="configurations[exports][pdf][options][exportOptions][columns]" class="form-control @error('configurations.exports.pdf.options.exportOptions.columns') is-invalid @enderror">
                            <option {{ $columns == '' ? 'selected' : '' }} value="">All Columns</option>
                            <option {{ $columns == ':visible' ? 'selected' : '' }} value=":visible">Only Visible Columns</option>
                        </select>
                        @error('configurations.exports.pdf.options.exportOptions.columns')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- The Exports section - Excel --}}
                <div class="form-row mt-4">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">Excel</h5>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.excel.enabled') ?: ( $configurations['exports']['excel']['enabled'] ?: '' ) @endphp
                        <select name="configurations[exports][excel][enabled]" class="form-control @error('configurations.exports.excel.enabled') is-invalid @enderror">
                            <option {{ $enabled ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$enabled ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.excel.enabled')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Header</label>
                        @php $header = old('configurations.exports.excel.options.header') ?: ( $configurations['exports']['excel']['options']['header'] ?: '' ) @endphp
                        <select name="configurations[exports][excel][options][header]" class="form-control @error('configurations.exports.excel.options.header') is-invalid @enderror">
                            <option {{ $header ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$header ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.excel.options.header')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Footer</label>
                        @php $footer = old('configurations.exports.excel.options.footer') ?: ( $configurations['exports']['excel']['options']['footer'] ?: '' ) @endphp
                        <select name="configurations[exports][excel][options][footer]" class="form-control @error('configurations.exports.excel.options.footer') is-invalid @enderror">
                            <option {{ $footer ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$footer ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.excel.options.footer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">File Name</label>
                        <input 
                            name="configurations[exports][excel][options][filename]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.filename') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.filename') ?: $configurations['exports']['excel']['options']['filename'] }}"
                        >
                        @error('configurations.exports.excel.options.filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Extension</label>
                        <input 
                            name="configurations[exports][excel][options][extension]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.extension') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.extension') ?: $configurations['exports']['excel']['options']['extension'] }}"
                        >
                        @error('configurations.exports.excel.options.extension')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Button Text</label>
                        <input 
                            name="configurations[exports][excel][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.text') ?: $configurations['exports']['excel']['options']['text'] }}"
                        >
                        @error('configurations.exports.excel.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Document Title</label>
                        <input 
                            name="configurations[exports][excel][options][title]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.title') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.title') ?: $configurations['exports']['excel']['options']['title'] }}"
                        >
                        @error('configurations.exports.excel.options.title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Document Logo</label>
                        <input 
                            name="configurations[exports][excel][options][logo]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.logo') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.logo') ?: $configurations['exports']['excel']['options']['logo'] }}"
                        >
                        @error('configurations.exports.excel.options.logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Message Top</label>
                        <input 
                            name="configurations[exports][excel][options][messageTop]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.messageTop') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.messageTop') ?: $configurations['exports']['excel']['options']['messageTop'] }}"
                        >
                        @error('configurations.exports.excel.options.messageTop')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Message Bottom</label>
                        <input 
                            name="configurations[exports][excel][options][messageBottom]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.messageBottom') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.messageBottom') ?: $configurations['exports']['excel']['options']['messageBottom'] }}"
                        >
                        @error('configurations.exports.excel.options.messageBottom')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.excel.options.exportOptions.columns') ?: ( $configurations['exports']['excel']['options']['exportOptions']['columns'] ?: '' ) @endphp
                        <select name="configurations[exports][excel][options][exportOptions][columns]" class="form-control @error('configurations.exports.excel.options.exportOptions.columns') is-invalid @enderror">
                            <option {{ $columns == '' ? 'selected' : '' }} value="">All Columns</option>
                            <option {{ $columns == ':visible' ? 'selected' : '' }} value=":visible">Only Visible Columns</option>
                        </select>
                        @error('configurations.exports.excel.options.exportOptions.columns')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                 {{-- The Exports section - CSV --}}
                <div class="form-row mt-4">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">CSV</h5>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.csv.enabled') ?: ( $configurations['exports']['csv']['enabled'] ?: '' ) @endphp
                        <select name="configurations[exports][csv][enabled]" class="form-control @error('configurations.exports.csv.enabled') is-invalid @enderror">
                            <option {{ $enabled ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$enabled ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.csv.enabled')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Header</label>
                        @php $header = old('configurations.exports.csv.options.header') ?: ( $configurations['exports']['csv']['options']['header'] ?: '' ) @endphp
                        <select name="configurations[exports][csv][options][header]" class="form-control @error('configurations.exports.csv.options.header') is-invalid @enderror">
                            <option {{ $header ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$header ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.csv.options.header')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Footer</label>
                        @php $footer = old('configurations.exports.csv.options.footer') ?: ( $configurations['exports']['csv']['options']['footer'] ?: '' ) @endphp
                        <select name="configurations[exports][csv][options][footer]" class="form-control @error('configurations.exports.csv.options.footer') is-invalid @enderror">
                            <option {{ $footer ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$footer ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.csv.options.footer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">File Name</label>
                        <input 
                            name="configurations[exports][csv][options][filename]" 
                            type="text" 
                            class="form-control @error('configurations.exports.csv.options.filename') is-invalid @enderror" 
                            value="{{ old('configurations.exports.csv.options.filename') ?: $configurations['exports']['csv']['options']['filename'] }}"
                        >
                        @error('configurations.exports.csv.options.filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Extension</label>
                        <input 
                            name="configurations[exports][csv][options][extension]" 
                            type="text" 
                            class="form-control @error('configurations.exports.csv.options.extension') is-invalid @enderror" 
                            value="{{ old('configurations.exports.csv.options.extension') ?: $configurations['exports']['csv']['options']['extension'] }}"
                        >
                        @error('configurations.exports.csv.options.extension')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Button Text</label>
                        <input 
                            name="configurations[exports][csv][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.csv.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.csv.options.text') ?: $configurations['exports']['csv']['options']['text'] }}"
                        >
                        @error('configurations.exports.csv.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.csv.options.exportOptions.columns') ?: ( $configurations['exports']['csv']['options']['exportOptions']['columns'] ?: '' ) @endphp
                        <select name="configurations[exports][csv][options][exportOptions][columns]" class="form-control @error('configurations.exports.csv.options.exportOptions.columns') is-invalid @enderror">
                            <option {{ $columns == '' ? 'selected' : '' }} value="">All Columns</option>
                            <option {{ $columns == ':visible' ? 'selected' : '' }} value=":visible">Only Visible Columns</option>
                        </select>
                        @error('configurations.exports.csv.options.exportOptions.columns')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- The Exports section - Copy --}}
                <div class="form-row mt-4">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">Copy</h5>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.copy.enabled') ?: ( $configurations['exports']['copy']['enabled'] ?: '' ) @endphp
                        <select name="configurations[exports][copy][enabled]" class="form-control @error('configurations.exports.copy.enabled') is-invalid @enderror">
                            <option {{ $enabled ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$enabled ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.copy.enabled')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Header</label>
                        @php $header = old('configurations.exports.copy.options.header') ?: ( $configurations['exports']['copy']['options']['header'] ?: '' ) @endphp
                        <select name="configurations[exports][copy][options][header]" class="form-control @error('configurations.exports.copy.options.header') is-invalid @enderror">
                            <option {{ $header ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$header ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.copy.options.header')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Footer</label>
                        @php $footer = old('configurations.exports.copy.options.footer') ?: ( $configurations['exports']['copy']['options']['footer'] ?: '' ) @endphp
                        <select name="configurations[exports][copy][options][footer]" class="form-control @error('configurations.exports.copy.options.footer') is-invalid @enderror">
                            <option {{ $footer ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$footer ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.copy.options.footer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Button Text</label>
                        <input 
                            name="configurations[exports][copy][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.copy.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.copy.options.text') ?: $configurations['exports']['copy']['options']['text'] }}"
                        >
                        @error('configurations.exports.copy.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Document Title</label>
                        <input 
                            name="configurations[exports][copy][options][title]" 
                            type="text" 
                            class="form-control @error('configurations.exports.copy.options.title') is-invalid @enderror" 
                            value="{{ old('configurations.exports.copy.options.title') ?: $configurations['exports']['copy']['options']['title'] }}"
                        >
                        @error('configurations.exports.copy.options.title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Message Top</label>
                        <input 
                            name="configurations[exports][copy][options][messageTop]" 
                            type="text" 
                            class="form-control @error('configurations.exports.copy.options.messageTop') is-invalid @enderror" 
                            value="{{ old('configurations.exports.copy.options.messageTop') ?: $configurations['exports']['copy']['options']['messageTop'] }}"
                        >
                        @error('configurations.exports.copy.options.messageTop')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Message Bottom</label>
                        <input 
                            name="configurations[exports][copy][options][messageBottom]" 
                            type="text" 
                            class="form-control @error('configurations.exports.copy.options.messageBottom') is-invalid @enderror" 
                            value="{{ old('configurations.exports.copy.options.messageBottom') ?: $configurations['exports']['copy']['options']['messageBottom'] }}"
                        >
                        @error('configurations.exports.copy.options.messageBottom')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.copy.options.exportOptions.columns') ?: ( $configurations['exports']['copy']['options']['exportOptions']['columns'] ?: '' ) @endphp
                        <select name="configurations[exports][copy][options][exportOptions][columns]" class="form-control @error('configurations.exports.copy.options.exportOptions.columns') is-invalid @enderror">
                            <option {{ $columns == '' ? 'selected' : '' }} value="">All Columns</option>
                            <option {{ $columns == ':visible' ? 'selected' : '' }} value=":visible">Only Visible Columns</option>
                        </select>
                        @error('configurations.exports.copy.options.exportOptions.columns')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- The Exports section - JSON --}}
                <div class="form-row mt-4">
                    <div class="form-group col-lg-12">
                        <h5 class="font-weight-bold">JSON</h5>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.json.enabled') ?: ( $configurations['exports']['json']['enabled'] ?: '' ) @endphp
                        <select name="configurations[exports][json][enabled]" class="form-control @error('configurations.exports.json.enabled') is-invalid @enderror">
                            <option {{ $enabled ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$enabled ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.json.enabled')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Header</label>
                        @php $header = old('configurations.exports.json.options.header') ?: ( $configurations['exports']['json']['options']['header'] ?: '' ) @endphp
                        <select name="configurations[exports][json][options][header]" class="form-control @error('configurations.exports.json.options.header') is-invalid @enderror">
                            <option {{ $header ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$header ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.json.options.header')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold">Footer</label>
                        @php $footer = old('configurations.exports.json.options.footer') ?: ( $configurations['exports']['json']['options']['footer'] ?: '' ) @endphp
                        <select name="configurations[exports][json][options][footer]" class="form-control @error('configurations.exports.json.options.footer') is-invalid @enderror">
                            <option {{ $footer ? 'selected' : '' }} value="1">True</option>
                            <option {{ !$footer ? 'selected' : '' }} value="0">False</option>
                        </select>
                        @error('configurations.exports.json.options.footer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">File Name</label>
                        <input 
                            name="configurations[exports][json][options][filename]" 
                            type="text" 
                            class="form-control @error('configurations.exports.json.options.filename') is-invalid @enderror" 
                            value="{{ old('configurations.exports.json.options.filename') ?: $configurations['exports']['json']['options']['filename'] }}"
                        >
                        @error('configurations.exports.json.options.filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Extension</label>
                        <input 
                            name="configurations[exports][json][options][extension]" 
                            type="text" 
                            class="form-control @error('configurations.exports.json.options.extension') is-invalid @enderror" 
                            value="{{ old('configurations.exports.json.options.extension') ?: $configurations['exports']['json']['options']['extension'] }}"
                        >
                        @error('configurations.exports.json.options.extension')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Button Text</label>
                        <input 
                            name="configurations[exports][json][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.json.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.json.options.text') ?: $configurations['exports']['json']['options']['text'] }}"
                        >
                        @error('configurations.exports.json.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label class="font-weight-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.json.options.exportOptions.columns') ?: ( $configurations['exports']['json']['options']['exportOptions']['columns'] ?: '' ) @endphp
                        <select name="configurations[exports][json][options][exportOptions][columns]" class="form-control @error('configurations.exports.json.options.exportOptions.columns') is-invalid @enderror">
                            <option {{ $columns == '' ? 'selected' : '' }} value="">All Columns</option>
                            <option {{ $columns == ':visible' ? 'selected' : '' }} value=":visible">Only Visible Columns</option>
                        </select>
                        @error('configurations.exports.json.options.exportOptions.columns')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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