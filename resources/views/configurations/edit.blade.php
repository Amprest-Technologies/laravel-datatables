@extends(package_resource('layouts.app'))
@section('title', "Edit Configurations : ".$configuration['identifier'])
@section('content')
    <section class="row">
        <div class="col-lg-12">
            <form action="{{ route('datatables.columns.store', [ 'configuration' => $configuration['identifier'] ]) }}" method="post">
                @csrf
                <label class="mb-0 fw-bold" for="">List a new column</label>
                <div class="input-group mb-1 mt-0">
                    <input 
                        name="name" 
                        type="text" 
                        class="form-control" 
                        placeholder="The Table's Unique Column Name" 
                        required
                        value="{{ old('name') ?? '' }}"
                    >
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">List Column</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="row">
        <div class="col-lg-12">
            <form action="{{ route('datatables.configurations.update', [ 'configuration' => $configuration['identifier'] ]) }}" method="post">
                @csrf
                @method('PUT')

                {{-- The Column filtering, sorting, display section --}}
                <div class="row mt-4">
                    <div class="mb-3 col-lg-12">
                        <h5 class="fw-bold">Column Configurations</h5>
                    </div>
                    <div class="mb-3 col-lg-12">
                        @if(count($configuration['columns']) ?? false)
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Column</th>
                                        <th>Title</th>
                                        <th>Server Name</th>
                                        <th>Type</th>
                                        <th>Data Type</th>
                                        <th>Sorting</th>
                                        <th>Hidden</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($configuration['columns'] as $column)
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
                                                <select name="columns[{{ $column }}][data_type]" class="form-control form-control-sm">
                                                    <option {{ collect($configurations['filters'])
                                                        ->where('name', Str::slug(strtolower($column), '_'))
                                                        ->where('data_type', 'string')
                                                        ->first() ? 'selected' : ''}} value="string">String</option>
                                                    <option {{ collect($configurations['filters'])
                                                        ->where('name', Str::slug(strtolower($column), '_'))
                                                        ->where('data_type', 'num')
                                                        ->first() ? 'selected' : ''}} value="num">Number</option>
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
                                                            <div class="modal-body text-start">
                                                                Are you sure you want to delete this column? The process is irreversible.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-success" data-dismiss="modal">No, Do not delete</button>
                                                                <a href="{{ route('datatables.columns.destroy', [ 'configuration' => $configuration['identifier'], 'column' => $column ]) }}" class="btn btn-danger">
                                                                    Yes, Delete
                                                                </a>
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
                            <h5>No column data is available. Please list a column above.</h5>  
                        @endif
                    </div>
                </div>

                {{-- The general section --}}
                <div class="row mt-4">
                    <div class="mb-3 col-lg-12">
                        <h5 class="fw-bold">General</h5>
                    </div>
                    <div class="mb-3 col-lg-12">
                        <label class="fw-bold">Identifier</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            value="{{ $configuration['identifier'] }}"
                            disabled
                        />
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Info</label>
                        @php $info = old('configurations.info') ?? ( $configurations['info'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Ordering</label>
                        @php $ordering = old('configurations.ordering') ?? ( $configurations['ordering'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Searching</label>
                        @php $searching = old('configurations.searching') ?? ( $configurations['searching'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Row Indexes</label>
                        @php $rowIndexes = old('configurations.rowIndexes') ?? ( $configurations['rowIndexes'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Column Visibility</label>
                        @php $columnVisibility = old('configurations.columnVisibility') ?? ( $configurations['columnVisibility'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Custom Export Title  </label>
                        @php $customTitle = old('configurations.customTitle') ?? ( $configurations['customTitle'] ?? '' ) @endphp
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

                <div class="row mt-4">
                    <div class="mb-3 col-lg-12">
                        <h5 class="fw-bold">Ajax Configurations</h5>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Enabled</label>
                        @php $enabled = old('configurations.ajax.enabled') ?? ( $configurations['ajax']['enabled'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">End Point Route Name</label>
                        <input 
                            name="configurations[ajax][options][route]" 
                            type="text" 
                            class="form-control @error('configurations.ajax.options.route') is-invalid @enderror" 
                            value="{{ old('configurations.ajax.options.route') ?? $configurations['ajax']['options']['route'] }}"
                        >
                        @error('configurations.ajax.options.route')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- The Exports section - Print --}}
                <div class="row mt-4">
                    <div class="mb-3 col-lg-12">
                        <h5 class="fw-bold mb-4">Table Exports</h5>
                        <h5 class="fw-bold">Print</h5>
                    </div>
                    <div class="mb-3 col-lg-12">
                        <label class="fw-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.print.enabled') ?? ( $configurations['exports']['print']['enabled'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Header</label>
                        @php $header = old('configurations.exports.print.options.header') ?? ( $configurations['exports']['print']['options']['header'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Footer</label>
                        @php $footer = old('configurations.exports.print.options.footer') ?? ( $configurations['exports']['print']['options']['footer'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Auto Print</label>
                        @php $autoPrint = old('configurations.exports.print.options.autoPrint') ?? ( $configurations['exports']['print']['options']['autoPrint'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Page Size</label>
                        @php $pageSize = old('configurations.exports.print.options.pageSize') ?? ( $configurations['exports']['print']['options']['pageSize'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Orientation</label>
                        @php $orientation = old('configurations.exports.print.options.orientation') ?? ( $configurations['exports']['print']['options']['orientation'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Button Text</label>
                        <input 
                            name="configurations[exports][print][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.text') ?? $configurations['exports']['print']['options']['text'] }}"
                        >
                        @error('configurations.exports.print.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Document Title</label>
                        <input 
                            name="configurations[exports][print][options][title]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.title') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.title') ?? $configurations['exports']['print']['options']['title'] }}"
                        >
                        @error('configurations.exports.print.options.title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Document Logo</label>
                        <input 
                            name="configurations[exports][print][options][logo]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.logo') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.logo') ?? $configurations['exports']['print']['options']['logo'] }}"
                        >
                        @error('configurations.exports.print.options.logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Message Top</label>
                        <input 
                            name="configurations[exports][print][options][messageTop]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.messageTop') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.messageTop') ?? $configurations['exports']['print']['options']['messageTop'] }}"
                        >
                        @error('configurations.exports.print.options.messageTop')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Message Bottom</label>
                        <input 
                            name="configurations[exports][print][options][messageBottom]" 
                            type="text" 
                            class="form-control @error('configurations.exports.print.options.messageBottom') is-invalid @enderror" 
                            value="{{ old('configurations.exports.print.options.messageBottom') ?? $configurations['exports']['print']['options']['messageBottom'] }}"
                        >
                        @error('configurations.exports.print.options.messageBottom')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.print.options.exportOptions.columns') ?? ( $configurations['exports']['print']['options']['exportOptions']['columns'] ?? '' ) @endphp
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
                <div class="row mt-4">
                    <div class="mb-3 col-lg-12">
                        <h5 class="fw-bold">PDF</h5>
                    </div>
                    <div class="mb-3 col-lg-12">
                        <label class="fw-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.pdf.enabled') ?? ( $configurations['exports']['pdf']['enabled'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Header</label>
                        @php $header = old('configurations.exports.pdf.options.header') ?? ( $configurations['exports']['pdf']['options']['header'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Footer</label>
                        @php $footer = old('configurations.exports.pdf.options.footer') ?? ( $configurations['exports']['pdf']['options']['footer'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Auto Download</label>
                        @php $autoDownload = old('configurations.exports.pdf.options.autoDownload') ?? ( $configurations['exports']['pdf']['options']['autoDownload'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">File Name</label>
                        <input 
                            name="configurations[exports][pdf][options][filename]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.filename') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.filename') ?? $configurations['exports']['pdf']['options']['filename'] }}"
                        >
                        @error('configurations.exports.pdf.options.filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Extension</label>
                        <input 
                            name="configurations[exports][pdf][options][extension]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.extension') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.extension') ?? $configurations['exports']['pdf']['options']['extension'] }}"
                        >
                        @error('configurations.exports.pdf.options.extension')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Page Size</label>
                        @php $pageSize = old('configurations.exports.pdf.options.pageSize') ?? ( $configurations['exports']['pdf']['options']['pageSize'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Orientation</label>
                        @php $orientation = old('configurations.exports.pdf.options.orientation') ?? ( $configurations['exports']['pdf']['options']['orientation'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Button Text</label>
                        <input 
                            name="configurations[exports][pdf][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.text') ?? $configurations['exports']['pdf']['options']['text'] }}"
                        >
                        @error('configurations.exports.pdf.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Document Title</label>
                        <input 
                            name="configurations[exports][pdf][options][title]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.title') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.title') ?? $configurations['exports']['pdf']['options']['title'] }}"
                        >
                        @error('configurations.exports.pdf.options.title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Document Logo</label>
                        <input 
                            name="configurations[exports][pdf][options][logo]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.logo') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.logo') ?? $configurations['exports']['pdf']['options']['logo'] }}"
                        >
                        @error('configurations.exports.pdf.options.logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Message Top</label>
                        <input 
                            name="configurations[exports][pdf][options][messageTop]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.messageTop') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.messageTop') ?? $configurations['exports']['pdf']['options']['messageTop'] }}"
                        >
                        @error('configurations.exports.pdf.options.messageTop')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Message Bottom</label>
                        <input 
                            name="configurations[exports][pdf][options][messageBottom]" 
                            type="text" 
                            class="form-control @error('configurations.exports.pdf.options.messageBottom') is-invalid @enderror" 
                            value="{{ old('configurations.exports.pdf.options.messageBottom') ?? $configurations['exports']['pdf']['options']['messageBottom'] }}"
                        >
                        @error('configurations.exports.pdf.options.messageBottom')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.pdf.options.exportOptions.columns') ?? ( $configurations['exports']['pdf']['options']['exportOptions']['columns'] ?? '' ) @endphp
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
                <div class="row mt-4">
                    <div class="mb-3 col-lg-12">
                        <h5 class="fw-bold">Excel</h5>
                    </div>
                    <div class="mb-3 col-lg-12">
                        <label class="fw-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.excel.enabled') ?? ( $configurations['exports']['excel']['enabled'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Header</label>
                        @php $header = old('configurations.exports.excel.options.header') ?? ( $configurations['exports']['excel']['options']['header'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Footer</label>
                        @php $footer = old('configurations.exports.excel.options.footer') ?? ( $configurations['exports']['excel']['options']['footer'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">File Name</label>
                        <input 
                            name="configurations[exports][excel][options][filename]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.filename') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.filename') ?? $configurations['exports']['excel']['options']['filename'] }}"
                        >
                        @error('configurations.exports.excel.options.filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Extension</label>
                        <input 
                            name="configurations[exports][excel][options][extension]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.extension') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.extension') ?? $configurations['exports']['excel']['options']['extension'] }}"
                        >
                        @error('configurations.exports.excel.options.extension')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Button Text</label>
                        <input 
                            name="configurations[exports][excel][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.text') ?? $configurations['exports']['excel']['options']['text'] }}"
                        >
                        @error('configurations.exports.excel.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Document Title</label>
                        <input 
                            name="configurations[exports][excel][options][title]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.title') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.title') ?? $configurations['exports']['excel']['options']['title'] }}"
                        >
                        @error('configurations.exports.excel.options.title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Document Logo</label>
                        <input 
                            name="configurations[exports][excel][options][logo]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.logo') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.logo') ?? $configurations['exports']['excel']['options']['logo'] }}"
                        >
                        @error('configurations.exports.excel.options.logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Message Top</label>
                        <input 
                            name="configurations[exports][excel][options][messageTop]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.messageTop') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.messageTop') ?? $configurations['exports']['excel']['options']['messageTop'] }}"
                        >
                        @error('configurations.exports.excel.options.messageTop')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Message Bottom</label>
                        <input 
                            name="configurations[exports][excel][options][messageBottom]" 
                            type="text" 
                            class="form-control @error('configurations.exports.excel.options.messageBottom') is-invalid @enderror" 
                            value="{{ old('configurations.exports.excel.options.messageBottom') ?? $configurations['exports']['excel']['options']['messageBottom'] }}"
                        >
                        @error('configurations.exports.excel.options.messageBottom')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label class="fw-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.excel.options.exportOptions.columns') ?? ( $configurations['exports']['excel']['options']['exportOptions']['columns'] ?? '' ) @endphp
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
                <div class="row mt-4">
                    <div class="mb-3 col-lg-12">
                        <h5 class="fw-bold">CSV</h5>
                    </div>
                    <div class="mb-3 col-lg-12">
                        <label class="fw-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.csv.enabled') ?? ( $configurations['exports']['csv']['enabled'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Header</label>
                        @php $header = old('configurations.exports.csv.options.header') ?? ( $configurations['exports']['csv']['options']['header'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Footer</label>
                        @php $footer = old('configurations.exports.csv.options.footer') ?? ( $configurations['exports']['csv']['options']['footer'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">File Name</label>
                        <input 
                            name="configurations[exports][csv][options][filename]" 
                            type="text" 
                            class="form-control @error('configurations.exports.csv.options.filename') is-invalid @enderror" 
                            value="{{ old('configurations.exports.csv.options.filename') ?? $configurations['exports']['csv']['options']['filename'] }}"
                        >
                        @error('configurations.exports.csv.options.filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Extension</label>
                        <input 
                            name="configurations[exports][csv][options][extension]" 
                            type="text" 
                            class="form-control @error('configurations.exports.csv.options.extension') is-invalid @enderror" 
                            value="{{ old('configurations.exports.csv.options.extension') ?? $configurations['exports']['csv']['options']['extension'] }}"
                        >
                        @error('configurations.exports.csv.options.extension')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Button Text</label>
                        <input 
                            name="configurations[exports][csv][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.csv.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.csv.options.text') ?? $configurations['exports']['csv']['options']['text'] }}"
                        >
                        @error('configurations.exports.csv.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.csv.options.exportOptions.columns') ?? ( $configurations['exports']['csv']['options']['exportOptions']['columns'] ?? '' ) @endphp
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
                <div class="row mt-4">
                    <div class="mb-3 col-lg-12">
                        <h5 class="fw-bold">Copy</h5>
                    </div>
                    <div class="mb-3 col-lg-12">
                        <label class="fw-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.copy.enabled') ?? ( $configurations['exports']['copy']['enabled'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Button Text</label>
                        <input 
                            name="configurations[exports][copy][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.copy.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.copy.options.text') ?? $configurations['exports']['copy']['options']['text'] }}"
                        >
                        @error('configurations.exports.copy.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.copy.options.exportOptions.columns') ?? ( $configurations['exports']['copy']['options']['exportOptions']['columns'] ?? '' ) @endphp
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
                <div class="row mt-4">
                    <div class="mb-3 col-lg-12">
                        <h5 class="fw-bold">JSON</h5>
                    </div>
                    <div class="mb-3 col-lg-12">
                        <label class="fw-bold">Enabled</label>
                        @php $enabled = old('configurations.exports.json.enabled') ?? ( $configurations['exports']['json']['enabled'] ?? '' ) @endphp
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
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">File Name</label>
                        <input 
                            name="configurations[exports][json][options][filename]" 
                            type="text" 
                            class="form-control @error('configurations.exports.json.options.filename') is-invalid @enderror" 
                            value="{{ old('configurations.exports.json.options.filename') ?? $configurations['exports']['json']['options']['filename'] }}"
                        >
                        @error('configurations.exports.json.options.filename')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Extension</label>
                        <input 
                            name="configurations[exports][json][options][extension]" 
                            type="text" 
                            class="form-control @error('configurations.exports.json.options.extension') is-invalid @enderror" 
                            value="{{ old('configurations.exports.json.options.extension') ?? $configurations['exports']['json']['options']['extension'] }}"
                        >
                        @error('configurations.exports.json.options.extension')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Button Text</label>
                        <input 
                            name="configurations[exports][json][options][text]" 
                            type="text" 
                            class="form-control @error('configurations.exports.json.options.text') is-invalid @enderror" 
                            value="{{ old('configurations.exports.json.options.text') ?? $configurations['exports']['json']['options']['text'] }}"
                        >
                        @error('configurations.exports.json.options.text')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="fw-bold">Columns To Export</label>
                        @php $columns = old('configurations.exports.json.options.exportOptions.columns') ?? ( $configurations['exports']['json']['options']['exportOptions']['columns'] ?? '' ) @endphp
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

                <div class="row">
                    <div class="mb-3 col-lg-12">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection