{{-- Get all datatables payload, and extract them into usable variables --}}
@php extract(datatables_payload($id = ($attributes->get('identifier') ?? $attributes->get('id')))) @endphp

{{-- Include the actual table that will be converted to a datatable --}}
<table {{ $attributes }}>{{ $slot }}</table>

{{-- Include the datatable configurations --}}
@push('datatables-config')
	<script type="text/javascript">
		window.addEventListener('load', function() {
			$(document).ready( function() {
				// 	Define constants
				let tableID = @js("#{$id}");
				let organization = @js($attributes->get('organization-name') ?? config('app.name'));
				let title = @js($attributes->get('title') ?? null);
				let logo = @js($attributes->get('organization-logo'));

				// 	Define default parameters
				let buttons = [];
				let customTitleId = '';
				let customTitleClass = '';
				let hasColumns = false;
				let rowIndexes = Boolean(Number(@json($rowIndexes ?? 0))); 
				let searching = Boolean(Number(@json($searching ?? 0)));
				let customTitle = Boolean(Number(@json($customTitle ?? 0)));
				let columnVisibility = Boolean(Number(@json($columnVisibility ?? 0)));
				let exports = JSON.parse('@json($exports ?? [])');
				let columns = JSON.parse('@json($columns ?? [])');

				//	Enable row indexes
				if(rowIndexes) insertEmptyColumn(tableID, 1);
				
				// 	Get headers from the table
				let headers = getHeadersFromHtml(tableID, columns);
				
				// 	Include export options if they exist
				if(Object.keys(exports).length) {
					//	Printable options
					if(Number(exports.print.enabled)) {
						let printOptions = exports.print.options;
						buttons.push({ ...printOptions , ...{ 
							extend: 'print',
							title: organization,
							exportOptions: {
								columns : ':visible:not(th.exclude-from-export)'
							},
							messageTop: function() {
								let titleElement = $(`${tableID}-title-input input`).val() || title;
								let messageTop = titleElement ? titleElement : (printOptions.messageTop ?? printOptions.title);
								messageTop = messageTop == undefined ? null : messageTop;
								return `<h5 class="message-top">${messageTop}</h5>`;
							},
							customize: function ( win ) {
								printStyles( win, logo)
							}
						}});
					}
	
					//	Excel Options
					if(Number(exports.excel.enabled)) {
						let excelOptions = exports.excel.options;
						buttons.push({ ...excelOptions, ...{
							extend: 'excelHtml5', 
							exportOptions: {
								columns : ':visible:not(th.exclude-from-export)'
							},
							filename: function(){
								let filename = $(`${tableID}-title-input input`).val() || title;
								return filename ? filename : (excelOptions.filename ?? excelOptions.title);
							},
							title: function(){
								let excelTitle = $(`${tableID}-title-input input`).val() || title;
								return excelTitle ? excelTitle : (excelOptions.title ?? excelOptions.filename);
							}
						}});
					}
	
					//	CSV Options
					if(Number(exports.csv.enabled)){
						let csvOptions = exports.csv.options;
						buttons.push({ ...csvOptions, ...{
							extend: 'csvHtml5',
							exportOptions: {
								columns : ':visible:not(th.exclude-from-export)'
							},
							filename: function(){
								let filename = $(`${tableID}-title-input input`).val() || title;
								return filename ? filename : csvOptions.filename;
							}
						}});
					}
	
					//	PDF Options
					if(Number(exports.pdf.enabled)){
						let pdfOptions = exports.pdf.options;
						buttons.push( { ...pdfOptions, ...{ 
							extend: 'pdfHtml5',
							exportOptions: {
								columns : ':visible:not(th.exclude-from-export)'
							},
							filename: function(){
								let filename = $(`${tableID}-title-input input`).val() || title;
								return filename ? filename : (pdfOptions.filename ?? pdfOptions.title);
							},
							title: function(){
								let pdfTitle = $(`${tableID}-title-input input`).val() || title;
								return pdfTitle ? pdfTitle : (pdfOptions.title ?? pdfOptions.filename);
							},
							customize: function (document) {
								document.content[1].table.widths = Array(document.content[1].table.body[0].length + 1).join('*').split('');
							}
						}});
					}
	
					//	Copy Options
					if(Number(exports.copy.enabled)) {
						let copyOptions = exports.copy.options;
						buttons.push( { ...copyOptions, ...{ 
							extend: 'copyHtml5',
							exportOptions: {
								columns : ':visible:not(th.exclude-from-export)'
							}
						}});
					}
	
					//	JSON Options
					if(Number(exports.json.enabled)) {
						let jsonOptions = exports.json.options;
						buttons.push( { ...jsonOptions, ...{ 
							exportOptions: {
								columns : ':visible:not(th.exclude-from-export)'
							},
							action: function ( e, dt, button, config ) {
								let data = dt.buttons.exportData( jsonOptions.exportOptions );
								let filename = $(`${tableID}-title-input input`).val() || title;

								$.fn.dataTable.fileSave(
									new Blob( [ JSON.stringify( data ) ] ),
									`${filename ? filename : jsonOptions.filename}${jsonOptions.extension}`
								);
							},
						}});
					}
				}

				//	Column Visibility
				if(columnVisibility) buttons.push('colvis');

				//	Configure custom titles --}}
				if(customTitle){
					customTitleId = `${tableID}-title-input`;
					customTitleClass = '.title-input.';
				}

				//	Determine if columns have been defined
				if(columns.length && searching) {
					if(columns.find((column) => column.type !== null)){
						hasColumns = true; 
						cloneHeader(tableID)
					}
				}

				// 	Define the datatables object
				const table = $(tableID).DataTable({
					dom: `<"table-container"<"control-panel top"<"buttons-control"<"${customTitleId}${customTitleClass}"><"buttons"B>><"length-control"l>><"table-panel"rt><"control-panel"<"table-information"i><"pagination"p>>>`,
					order: getSortingOrder(columns, headers),
					searching: Boolean(Number(@json($searching ?? 1))),
					paging: Boolean(Number(@json($paging ?? 1))),
					ordering: Boolean(Number(@json($ordering ?? 1))),
					info: Boolean(Number(@json($info ?? 1))),
					columns: headers,
					responsive: true,
					buttons: buttons,
					initComplete: function () {
						//	Get the datatable instance 
						const api = this.api();

						// 	Add table numberings on the first column
						if(rowIndexes) addRowIndexes(api);

						// 	Determine if columns have been defined
						if(hasColumns) {
							var newArguments = Array.prototype.slice.call(arguments);
							newArguments.push(columns);
							addColumnSearching.apply(this, newArguments);
						}
							
						//	Check if any hidden columns have been defined, and hide them
						getHiddenColumns(columns).forEach(function(column){
							var key = api.column(`${column}:name`).index();
							var column = api.column(key).visible(false);
						});

						//	Initialize a custom title input if its defined
						if(customTitle) initializeCustomTitle();
					}
				});
			})
		})
	</script>
@endpush