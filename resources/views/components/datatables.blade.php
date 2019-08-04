{{-- Include the actual table that will be converted to a datatable --}}
<table 
    id="{{ $id }}" 
    class="{{ $classes ?? '' }}"
    @if(isset($checkboxes) && $checkboxes)
        data-checkboxes="true"
    @endif
> {{ $slot }} </table>

{{-- Include the js that will be used to manage the datatables functionality --}}
<script defer>
	$(document).ready( function() {
		const tableID = `#{{ $id }}`

		// 	Prepend or append an empty cell on each row
		@if(!isset($rowIndexes) || ( isset($rowIndexes) && $rowIndexes) )
			insertEmptyColumn(tableID, 1)
		@endif

		// 	Clone the header values into the footer
		cloneHeader(tableID)

		// 	Define default parameters
		var buttons = [ ]
		var order = [ ]
		var customTitleId = ''
		var headers = getHeadersAsArray(tableID)

		// 	Printable options
		@if(isset($exports['print']) && $exports['print']['enabled'])
			const printOptions = JSON.parse('@json($exports['print']['options'])')
			buttons.push({ ...printOptions , ...{ 
				extend : 'print',
				title: `<h3 class="text-center font-weight-bold">${printOptions.title}</h3>`,
				messageTop: function() {
					let messageTop = $(`${tableID}-title-input input`).val() ||  printOptions.messageTop
					return `<h4 class="text-center font-weight-bold my-3">${messageTop}</h4>`
				},
				customize: function ( win ) {
					//  Include the print styles. Pass the logo if it is defined
					const logo = JSON.parse('@json($logo ?? false)')
					printStyles( win, logo)
				},
			}})
		@endif

		// 	CSV Options
		@if(isset($exports['csv']) && $exports['csv']['enabled'])
			buttons.push( { ...(JSON.parse('@json($exports['csv']['options'])')), ...{ 
				extend : 'csvHtml5',
				customize: function ( win ) {
			
				},
			} })
		@endif

		// 	PDF Options
		@if(isset($exports['pdf']) && $exports['pdf']['enabled'])
			buttons.push( { ...(JSON.parse('@json($exports['pdf']['options'])')), ...{ 
				extend : 'pdfHtml5',
				customize: function ( win ) {
					// 
				},
			} })
		@endif

		// 	Copy Options
		@if(isset($copy) && $copy)
			buttons.push('copyHtml5')
		@endif

		// 	Column visibility
		@if(isset($column_visibility) && $column_visibility)
			buttons.push('colvis')
		@endif

		// 	Column visibility
		@if(isset($custom_title) && $custom_title)
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
			dom: `<"row"<"col-lg-8"<"${customTitleId}.title-input d-inline-block"><"d-inline-block"B>><"col-lg-4 text-right"l>>rt<"row"<"col-lg-4"i><"col-lg-8"p>>`,
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
			}
		});
	})
</script>