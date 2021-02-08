{{-- Get all datatables payload, and extract them into usable variables --}}
@php extract(datatables_payload($id = $attributes->get('id') )) @endphp

{{-- Include the actual table that will be converted to a datatable --}}
<table {{ $attributes }}> 
	{{ $slot }} 
</table>

{{-- Tested 111 --}}

{{-- Include the datatable configurations --}}
@push('datatables-config')
	<script type="text/javascript">
		window.addEventListener('load', function() {
			$(document).ready( function() {
				const tableID = `#{{ $id }}`

				// 	Prepend or append an empty cell on each row
				@if(!isset($rowIndexes) || ( isset($rowIndexes) && $rowIndexes) )
					insertEmptyColumn(tableID, 1)
				@endif

				// 	Define default parameters
				var buttons = []
				var order = []
				var customTitleId = ''
				var customTitleClass = ''
				var filters = JSON.parse('@json($filters ?? [])')
				var rowIndexes = ajax = hasFilters = false

				// 	Row indexes
				@if(!isset($rowIndexes) || ( isset($rowIndexes) && $rowIndexes) )
					rowIndexes = true
				@endif

				// 	Set the headers variable
				@if(isset($ajax) && $ajax['enabled'])
					var ajax = true
					var headers = getHeadersFromFilters(filters, rowIndexes)
					prepareTableForAjax(tableID, headers)
				@else
					var headers = getHeadersFromHtml(tableID)
				@endif
				
				// 	Printable options
				@if(isset($exports['print']) && $exports['print']['enabled'])
					var options = JSON.parse('@json($exports['print']['options'])')
					buttons.push({ ...options , ...{ 
						extend: 'print',
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
						extend: 'excelHtml5', 
						customize: function ( win ) {},
					} })
				@endif

				// 	CSV Options
				@if(isset($exports['csv']) && $exports['csv']['enabled'])
					buttons.push( { ...(JSON.parse('@json($exports['csv']['options'])')), ...{
						extend: 'csvHtml5', 
						customize: function ( win ) {},
					} })
				@endif

				// 	PDF Options
				@if(isset($exports['pdf']) && $exports['pdf']['enabled'])
					buttons.push( { ...(JSON.parse('@json($exports['pdf']['options'])')), ...{ 
						extend: 'pdfHtml5',
						customize: function ( win ) {},
					} })
				@endif

				// 	Copy Options
				@if(isset($exports['copy']) && $exports['copy']['enabled'])
					buttons.push( { ...(JSON.parse('@json($exports['copy']['options'])')), ...{ 
						extend: 'copyHtml5',
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
					customTitleClass = '.title-input'
				@endif

				// 	Determine if filters have been defined
				@if(isset($filters) && count($filters) && isset($searching) && $searching) 
					// 	Define date formats
					@foreach($filters as $filter)
						@if($filter['type']) 
							var hasFilters = true;
						@endif
						@isset($filter['js_format'])
							$.fn.dataTable.moment('{{ $filter['js_format'] }}');
						@endisset
					@endforeach

					// 	Clone headers if table needs filters
					if(hasFilters) cloneHeader(tableID)
				@endif

				// 	Determine sorting options
				@if(isset($sorting))
					@foreach($sorting as $option)
						var column = headers.map( ( column ) => column.data ).indexOf(`{{ $option['column'] }}`)
						// 	Push if column exists
						if(column > 0) {
							order.push([ column, `{{ $option['order'] }}` ])
						}
					@endforeach
				@endif

				// 	Define the datatables object
				const table = $(tableID).DataTable({
					dom: `<"row"<"col-lg-3 text-left"l><"col-lg-9"<"${customTitleId}${customTitleClass} d-inline-block"><"d-inline-block"B>>>rt<"row"<"col-lg-4"i><"col-lg-8"p>>`,
					order: order,
					searching: Boolean(Number(@json($searching ?? 1))),
					paging: Boolean(Number(@json($paging ?? 1))),
					ordering: Boolean(Number(@json($ordering ?? 1))),
					info: Boolean(Number(@json($info ?? 1))),
					columns: headers,
					responsive: true,
					buttons: buttons,
					@if(isset($ajax) && $ajax['enabled'])
						processing: true,
						serverSide: true,
						ajax: {
							url: `{{ route($ajax['options']['route']) }}`,
							dataType: 'json',
							type: 'POST',
							data: {
								_token : `{{ csrf_token() }}`,
								filters : headers, 
								row_indexes : rowIndexes,
							},
							dataSrc: function(json) {
								filters = json.filters
								return json.data
							}
						},
					@endif
					initComplete: function () {	
						// 	Add table numberings on the first column
						if (rowIndexes && !ajax) {
							addRowIndexes(this.api())
						}

						// 	Determine if filters have been defined
						if(hasFilters) {
							var newArguments = Array.prototype.slice.call(arguments)
							newArguments.push(filters)
							addColumnSearching.apply(this, newArguments)
						}
							
						// 	Check if any hidden columns have been defined
						@if(isset($hiddenColumns))
							@foreach($hiddenColumns as $column)
								var key = this.api().column(`{{ $column }}:name`).index()
								var column = this.api().column(key).visible(false)
							@endforeach
						@endif

						// 	Initialize a custom title input if its defined 
						@if(isset($customTitle) && $customTitle)
							initializeCustomTitle()
						@endif					
					}
				});
			})
		})
	</script>
@endpush