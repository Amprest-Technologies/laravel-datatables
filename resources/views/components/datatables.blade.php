{{-- Get all datatables payload, and extract them into usable variables --}}
@php extract(datatables_payload($id = $attributes->get('id') )) @endphp

{{-- Include the actual table that will be converted to a datatable --}}
<table {{ $attributes }}> 
	{{ $slot }} 
</table>

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
				var options = {};
				var order = [];
				var buttons = [];
				var customTitleId = '';
				var customTitleClass = '';
				var filters = JSON.parse('@json($filters ?? [])');
				var rowIndexes = ajax = hasFilters = false;

				// 	Row indexes
				@if(!isset($rowIndexes) || ( isset($rowIndexes) && $rowIndexes) )
					rowIndexes = true;
				@endif

				// 	Set the headers variable
				@if($ajax['enabled'] ?? false)
					var ajax = true;
					var headers = getHeadersFromFilters(filters, rowIndexes);
					prepareTableForAjax(tableID, headers);
				@else
					var headers = getHeadersFromHtml(tableID);
				@endif
				
				// 	Printable options
				@if(isset($exports['print']) && $exports['print']['enabled'])
					options = JSON.parse('@json($exports['print']['options'])');
					buttons.push({ ...options , ...{ 
						extend: 'print',
						title: function() {
							return `{{ config('app.name') }}`;
						},
						messageTop: function() {
							let titleElement = $(`${tableID}-title-input input`).val();
							let messageTop = titleElement ?? (options.messageTop ?? options.title);
							return `<h5 style="margin-bottom:1rem;">${messageTop}</h5>`
						},
						customize: function ( win ) {
							printStyles( win, options.logo)
						},
					}});
				@endif

				// 	Excel Options
				@if($exports['excel']['enabled'] ?? false)
					options = JSON.parse('@json($exports['excel']['options'])')
					buttons.push({ ...options, ...{
						extend: 'excelHtml5', 
						customize: function ( win ) {},
					}});
				@endif

				// 	CSV Options
				@if($exports['csv']['enabled'] ?? false)
					options = JSON.parse('@json($exports['csv']['options'])');
					buttons.push({ ...options, ...{
						extend: 'csvHtml5', 
						customize: function ( win ) {},
					}})
				@endif

				// 	PDF Options
				@if($exports['pdf']['enabled'] ?? false)
					options = JSON.parse('@json($exports['pdf']['options'])');
					buttons.push( { ...options, ...{ 
						extend: 'pdfHtml5',
						customize: function ( win ) {},
					}})
				@endif

				// 	Copy Options
				@if($exports['copy']['enabled'] ?? false)
					options = JSON.parse('@json($exports['copy']['options'])');
					buttons.push( { ...options, ...{ 
						extend: 'copyHtml5',
						customize: function ( win ) {},
					}});
				@endif

				// 	JSON Options
				@if($exports['json']['enabled'] ?? false)
					var options = JSON.parse('@json($exports['json']['options'])')
					buttons.push( { ...options, ...{ 
						action: function ( e, dt, button, config ) {
							var data = dt.buttons.exportData( options.exportOptions );
							$.fn.dataTable.fileSave(
								new Blob( [ JSON.stringify( data ) ] ),
								`${options.filename}${options.extension}`
							);
						},
					}});
				@endif

				// 	Column visibility
				@if($columnVisibility ?? false)
					buttons.push('colvis');
				@endif

				// 	Configure custom titles
				@if($customTitle ?? false)
					customTitleId = `${tableID}-title-input`;
					customTitleClass = '.title-input.';
				@endif

				// 	Determine if filters have been defined
				@if(count($filters ?? []) && ($searching ?? false)) 
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
					if(hasFilters) cloneHeader(tableID);
				@endif

				// 	Determine sorting options
				@if($sorting ?? false)
					@foreach($sorting as $option)
						var column = headers.map( ( column ) => column.data ).indexOf(`{{ $option['column'] }}`);
						if(column > 0) order.push([ column, `{{ $option['order'] }}` ]);
					@endforeach
				@endif

				// 	Define the datatables object
				const table = $(tableID).DataTable({
					dom: `<"table-container"<"control-panel top"<"buttons-control"<"${customTitleId}${customTitleClass}"><"buttons"B>><"length-control"l>><"table-panel"rt><"control-panel"<"table-information"i><"pagination"p>>>`,
					order: order,
					searching: Boolean(Number(@json($searching ?? 1))),
					paging: Boolean(Number(@json($paging ?? 1))),
					ordering: Boolean(Number(@json($ordering ?? 1))),
					info: Boolean(Number(@json($info ?? 1))),
					columns: headers,
					responsive: true,
					buttons: buttons,
					@if($ajax['enabled'] ?? false)
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
								filters = json.filters;
								return json.data;
							}
						},
					@endif
					initComplete: function () {	
						// 	Add table numberings on the first column
						if (rowIndexes && !ajax) addRowIndexes(this.api());

						// 	Determine if filters have been defined
						if(hasFilters) {
							var newArguments = Array.prototype.slice.call(arguments);
							newArguments.push(filters);
							addColumnSearching.apply(this, newArguments);
						}
							
						// 	Check if any hidden columns have been defined
						@if($hiddenColumns ?? false)
							@foreach($hiddenColumns as $column)
								var key = this.api().column(`{{ $column }}:name`).index();
								var column = this.api().column(key).visible(false);
							@endforeach
						@endif

						// 	Initialize a custom title input if its defined 
						@if($customTitle ?? false)
							initializeCustomTitle();
						@endif					
					}
				});
			})
		})
	</script>
@endpush