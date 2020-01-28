{{-- Include the style that will be used to manage the datatables functionality --}}
@section('datatables-css')
	<link href="{{ package_asset('css/app.css') }}" rel="stylesheet">
@endsection

{{-- Get all datatables payload, and extract them into usable variables --}}
@php extract(datatables_payload($id, $identifier ?? null)); @endphp

{{-- Include the actual table that will be converted to a datatable --}}
<table 
    id="{{ $id }}" 
    class="{{ $classes }}"
> {{ $slot }} </table>

{{-- Include the js that will be used to manage the datatables functionality --}}
@section('datatables-js')
	<script src="{{ package_asset('js/manifest.js') }}"></script>
	<script src="{{ package_asset('js/vendor.js') }}"></script>
	<script src="{{ package_asset('js/app.js') }}"></script>
	<script src="{{ package_asset('js/master.js') }}"></script>
@endsection

{{-- Include the datatables scripts --}}
@section('js')
	@parent
	<script>
		$(document).ready( function() {
			const tableID = `#{{ $id }}`

			// 	Prepend or append an empty cell on each row
			@if(!isset($rowIndexes) || ( isset($rowIndexes) && $rowIndexes) )
				insertEmptyColumn(tableID, 1)
			@endif

			// 	Clone the header values into the footer
			cloneHeader(tableID)

			// 	Define default parameters
			var buttons = []
			var order = []
			var customTitleId = ''
			var headers = getHeadersAsArray(tableID)

			// 	Printable options
			@if(isset($exports['print']) && $exports['print']['enabled'])
				var options = JSON.parse('@json($exports['print']['options'])')
				buttons.push({ ...options , ...{ 
					messageTop: function() {
						let organization = options.organization
						let messageTop = $(`${tableID}-title-input input`).val() ||  options.messageTop
						return `<h5 class="text-center font-weight-bold my-3 text-uppercase">${organization ? organization + ' : ' : null } ${messageTop}</h5>`
					},
					customize: function ( win ) {
                        //  Include the print styles. Pass the logo if it is defined
                        printStyles( win, options.logo)
					},
				}})
			@endif

			// 	Excel Options
			@if(isset($exports['excel']) && $exports['excel']['enabled'])
				buttons.push( { ...(JSON.parse('@json($exports['excel']['options'])')), ...{ 
					customize: function ( win ) {},
				} })
			@endif

			// 	CSV Options
			@if(isset($exports['csv']) && $exports['csv']['enabled'])
				buttons.push( { ...(JSON.parse('@json($exports['csv']['options'])')), ...{ 
					customize: function ( win ) {},
				} })
			@endif

			// 	PDF Options
			@if(isset($exports['pdf']) && $exports['pdf']['enabled'])
				buttons.push( { ...(JSON.parse('@json($exports['pdf']['options'])')), ...{ 
					customize: function ( win ) {},
				} })
			@endif

			// 	Copy Options
			@if(isset($exports['copy']) && $exports['copy']['enabled'])
				buttons.push( { ...(JSON.parse('@json($exports['copy']['options'])')), ...{ 
					customize: function ( win ) {},
				} })
			@endif

			// 	JSON Options
			@if(isset($exports['json']) && $exports['json']['enabled'])
				var options = JSON.parse('@json($exports['json']['options'])')
				buttons.push( { ...options, ...{ 
					action: function ( e, dt, button, config ) {
						var data = dt.buttons.exportData( options.exportOptions );
						$.fn.dataTable.fileSave(
							new Blob( [ JSON.stringify( data ) ] ),
							`${options.filename}${options.extension}`
						);
					},
				} })
			@endif

			// 	Column visibility
			@if(isset($columnVisibility) && $columnVisibility)
				buttons.push('colvis')
			@endif

			// 	Configure custom titles
			@if(isset($customtitle) && $customtitle)
				customTitleId = `${tableID}-title-input`
			@endif

			// 	Determine the date formats defined
			@foreach($filters as $filter)
				@isset($filter['js_format'])
					$.fn.dataTable.moment('{{ $filter['js_format'] }}');
				@endisset
			@endforeach

			// 	Determine sorting options
			@if(isset($sorting) && $sorting)
				@foreach($sorting as $option)
					var column = headers.map( ( column ) => column.name ).indexOf(`{{ $option['column'] }}`)
					order.push([ column, `{{ $option['order'] }}` ])
				@endforeach
			@endif

			// 	Define the datatables object
			const table = $(tableID).DataTable({
				dom: `<"row"<"col-lg-9"<"${customTitleId}.title-input d-inline-block"><"d-inline-block"B>><"col-lg-3 text-right"l>>rt<"row"<"col-lg-4"i><"col-lg-8"p>>`,
				order: order,
				searching: @json($searching ?? true),
				paging: @json($paging ?? true),
				ordering: @json($ordering ?? true),
				info: @json($info ?? true),
				columns: headers,
				responsive: true,
				buttons: buttons,
				initComplete: function () {	
					// 	Add table numberings on the first column
					@if(!isset($rowIndexes) || ( isset($rowIndexes) && $rowIndexes) )
						addRowIndexes(this.api())
					@endif

					// 	Define filters in the table
					var newArguments = Array.prototype.slice.call(arguments)
					newArguments.push(JSON.parse('@json($filters ?? [])'))
					addColumnSearching.apply(this, newArguments)

					// 	Initialize a custom title input if its defined 
					@if(isset($customtitle) && $customtitle)
						initializeCustomTitle()
					@endif
				}
			});
		})
	</script>
@endsection