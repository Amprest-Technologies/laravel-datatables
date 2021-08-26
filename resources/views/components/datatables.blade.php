{{-- Get all datatables payload, and extract them into usable variables --}}
@php extract(datatables_payload($id = $attributes->get('id'))) @endphp

{{-- Include the actual table that will be converted to a datatable --}}
<table {{ $attributes }}> 
	{{ $slot }} 
</table>

{{-- Include the datatable configurations --}}
@push('datatables-config')
	<script type="text/javascript">
		window.addEventListener('load', function() {
			$(document).ready( function() {
				const tableID = `#{{ $id }}`;
				const organization = `{{ $attributes->get('organization-name') ?? config('app.name') }}`;

				// 	Define default parameters
				var options = {};
				var order = [];
				var buttons = [];
				var customTitleId = '';
				var customTitleClass = '';
				var filters = JSON.parse('@json($filters ?? [])');
				var rowIndexes = false; 
				var ajax = false;
				var hasFilters = false;

				{{-- Enable row indexes --}}
				@if(!isset($rowIndexes) || ($rowIndexes ?? false))
					insertEmptyColumn(tableID, 1);
					rowIndexes = true;
				@endif

				// 	Get headers from the table
				var headers = getHeadersFromHtml(tableID, filters);
				
				{{-- Printable options --}}
				@if($exports['print']['enabled'] ?? false)
					let printOptions = JSON.parse('@json($exports['print']['options'])');
					buttons.push({ ...printOptions , ...{ 
						extend: 'print',
						title: organization,
						messageTop: function() {
							let titleElement = $(`${tableID}-title-input input`).val();
							let messageTop = titleElement ? titleElement : (printOptions.messageTop ?? printOptions.title);
							messageTop = messageTop == undefined ? null : messageTop;
							return `<h5 class="message-top">${messageTop}</h5>`;
						},
						customize: function ( win ) {
							printStyles( win, `{{ $attributes->get('organization-logo') }}`)
						},
					}});
				@endif

				{{-- Excel Options --}}
 				@if($exports['excel']['enabled'] ?? false)
					let excelOptions = JSON.parse('@json($exports['excel']['options'])');
					buttons.push({ ...excelOptions, ...{
						extend: 'excelHtml5', 
						filename: function(){
							let filename = $(`${tableID}-title-input input`).val();
							return filename ? filename : (excelOptions.filename ?? excelOptions.title);
						},
						title: function(){
							let title = $(`${tableID}-title-input input`).val();
							return title ? title : (excelOptions.title ?? excelOptions.filename);
						}
					}});
				@endif

				{{-- CSV Options --}}
				@if($exports['csv']['enabled'] ?? false)
					let csvOptions = JSON.parse('@json($exports['csv']['options'])');
					buttons.push({ ...csvOptions, ...{
						extend: 'csvHtml5',
						filename: function(){
							let filename = $(`${tableID}-title-input input`).val();
							return filename ? filename : csvOptions.filename;
						}
					}})
				@endif

				{{-- PDF Options --}}
				@if($exports['pdf']['enabled'] ?? false)
					let pdfOptions = JSON.parse('@json($exports['pdf']['options'])');
					buttons.push( { ...pdfOptions, ...{ 
						extend: 'pdfHtml5',
						filename: function(){
							let filename = $(`${tableID}-title-input input`).val();
							return filename ? filename : (pdfOptions.filename ?? pdfOptions.title);
						},
						title: function(){
							let title = $(`${tableID}-title-input input`).val();
							return title ? title : (pdfOptions.title ?? pdfOptions.filename);
						},
						customize: function (document) {
							document.content[1].table.widths = Array(document.content[1].table.body[0].length + 1).join('*').split('');
						}
					}})
				@endif

				{{-- Copy Options --}}
				@if($exports['copy']['enabled'] ?? false)
					let copyOptions = JSON.parse('@json($exports['copy']['options'])');
					buttons.push( { ...copyOptions, ...{ 
						extend: 'copyHtml5',
					}});
				@endif

				{{-- JSON Options --}}
				@if($exports['json']['enabled'] ?? false)
					let jsonOptions = JSON.parse('@json($exports['json']['options'])')
					buttons.push( { ...jsonOptions, ...{ 
						action: function ( e, dt, button, config ) {
							let data = dt.buttons.exportData( jsonOptions.exportOptions );
							$.fn.dataTable.fileSave(
								new Blob( [ JSON.stringify( data ) ] ),
								`${jsonOptions.filename}${jsonOptions.extension}`
							);
						},
					}});
				@endif

				{{-- Column Visibility --}}
				@if($columnVisibility ?? false)
					buttons.push('colvis');
				@endif

				{{-- Configure custom titles --}}
				@if($customTitle ?? false)
					customTitleId = `${tableID}-title-input`;
					customTitleClass = '.title-input.';
				@endif

				{{-- Determine if filters have been defined --}}
				@if(count($filters ?? []) && ($searching ?? false)) 
					@foreach($filters as $filter)
						@if($filter['type']) 
							hasFilters = true; 
							cloneHeader(tableID)
							@break 
						@endif
					@endforeach
				@endif

				{{-- Determine sorting options --}}
				@if($sorting ?? false)
					@foreach($sorting as $option)
						@if(($column = $option['column'] ?? null) && ($order = $option['order'] ?? null))
							let index = headers.findIndex(column => column.name == `{{ $column }}`);
							order.push([index, `{{ $order }}`]);
						@endif
					@endforeach
				@endif

				// 	Define the datatables object
				const table = $(tableID).DataTable({
					dom: `<"table-container"<"control-panel top"<"buttons-control"<"${customTitleId}${customTitleClass}"><"buttons"B>><"length-control"l>><"table-panel"rt><"control-panel"<"table-information"i><"pagination"p>>>`,
					order: order.length > 0 ? order : [],
					searching: Boolean(Number(@json($searching ?? 1))),
					paging: Boolean(Number(@json($paging ?? 1))),
					ordering: Boolean(Number(@json($ordering ?? 1))),
					info: Boolean(Number(@json($info ?? 1))),
					columns: headers,
					responsive: true,
					buttons: buttons,
					initComplete: function () {	
						// 	Add table numberings on the first column
						if (rowIndexes && !ajax) addRowIndexes(this.api());

						// 	Determine if filters have been defined
						if(hasFilters) {
							var newArguments = Array.prototype.slice.call(arguments);
							newArguments.push(filters);
							addColumnSearching.apply(this, newArguments);
						}
							
						{{-- Check if any hidden columns have been defined --}}
						@if($hiddenColumns ?? false)
							@foreach($hiddenColumns as $column)
								var key = this.api().column(`{{ $column }}:name`).index();
								var column = this.api().column(key).visible(false);
							@endforeach
						@endif

						{{-- Initialize a custom title input if its defined  --}}
						@if($customTitle ?? false) initializeCustomTitle(); @endif	
					}
				});
			})
		})
	</script>
@endpush