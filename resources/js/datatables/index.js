/* --------------------------------------------------------------
* Require datatables dependancies
* --------------------------------------------------------------
*/
import moment from 'moment';
require('./datetime-moment')
require('./datatables-exports-styles')

$(() => {
    /* --------------------------------------------------------------
     * Initialise the date range picker plugin
     * --------------------------------------------------------------
     */
    const input = $('input[name="daterange"]')
    const id =  input.data('id')
    const format = input.data('format')
    const column = input.data('column')

    //  Make the input a daterangepicker instance
    input.daterangepicker({
        opens: 'center',
        showDropdowns: true,
        alwaysShowCalendars: true,
        locale: { cancelLabel: 'Clear' }, 
        ranges: {
           'Today': [ moment(), moment() ],
           'Yesterday': [ moment().subtract(1, 'days'), moment().subtract(1, 'days') ],
           'Last 7 Days': [ moment().subtract(6, 'days'), moment() ],
           'This Month': [ moment().startOf('month'), moment().endOf('month') ],
           'Last 30 Days': [ moment().subtract(29, 'days'), moment() ],
           'Last 90 Days': [ moment().subtract(29, 'days'), moment() ],
        }
    }, function(start, end) {
        $(`input[name=${id}-start_date_${column}]`).val( start.format(format) )
        $(`input[name=${id}-end_date_${column}]`).val( end.format(format) )
    }).on('cancel.daterangepicker', function(ev, picker) {
        $(`input[name=${id}-start_date_${column}]`).val('')
        $(`input[name=${id}-end_date_${column}]`).val('')
        input.val('').trigger('change')
    });

    /**
     * ------------------------------------------------------------------------
     * Enable bootstrap popovers
     * ------------------------------------------------------------------------
     */
    $('[data-toggle="popover"]').popover({
        html: true,
        container: 'body',
        sanitize: false,
        placement: 'bottom',
    })

    /* --------------------------------------------------------------
     *  Handle filtering of data by ranges
     * --------------------------------------------------------------
     */
    $(document).on('click', 'button.range-filter-action', function(){
        const id = $(this).data('id')
        const start = parseInt( $(`#${id} input[name=start_range]`).val(), 10 )
        const end = parseInt( $(`#${id} input[name=end_range]`).val(), 10 )

        // Set the text that will be shown on the text box
        if(start && isNaN(end)) var text = `>= ${start}` 
        else if (isNaN(start) && end) var text = `<= ${end}` 
        else if (start && end) var text = `${start} - ${end}`
        $(`input[name=input-${id}]`).val(text || '').trigger('change')

        //  Close the pop up
        closePopup(`${id}`)       
    })

    /* --------------------------------------------------------------
     *  Handle clearing of data by ranges
     * --------------------------------------------------------------
     */
    $(document).on('click', 'button.range-filter-clear', function(){
        // Set the text that will be shown on the text box
        $(`input[name=input-${ $(this).data('id') }]`).val('').trigger('change')

        //  Close the pop up
        closePopup(`${id}`)       
    })

    /* --------------------------------------------------------------
     *  Add an input form to declare a custom input
     * --------------------------------------------------------------
     */
    $('div.title-input').html(`
        <input 
            type="text" 
            class="form-control form-control-sm" 
            placeholder="Custom heading for exports" 
        />
    `)
})

/* --------------------------------------------------------------
 * This function appends select search fields into a datatable.
 * It takes in column indexes as parameters
 * --------------------------------------------------------------
 */
window.addColumnSearching = function(type, row, data, start, end, display) {
    let options = Array.prototype.slice.call(arguments)[2];

    // Get columns by index name
    const filtersObj = {}
    const columns = options.map(( item , index ) => {
        let key = this.api().column( `${item.column}:name` ).index()
        filtersObj[key] = { type: item.type, format: item.js_format }
        return key
    }).filter(function (el) {
        return el != null;
    }) 

    //  Get the datatables instance
    const table = this.api()

    //  Loop through all the columns specified
    table.columns(columns)
        .every(
            function() {
                let column = this;
                let columnIndex = column.index()

                //  Check what kind of filter is defined for a column
                switch ( filtersObj[columnIndex].type ) {
                    case 'select':
                        appendSelectFilter(column)
                        break

                    case 'date':
                        appendDatePicker(table, column, filtersObj[columnIndex].format)
                        break

                    case 'range':
                        appendRangePicker(table, column)
                        break

                    default:
                        appendInputFilter(column)
                        break
                }   
            }
        );
};

/* --------------------------------------------------------------
 * Hadle appending of select filters to the datatables filter row
 * --------------------------------------------------------------
 */
const appendSelectFilter = ( column ) => {
    //  Append the select boxes on the specified columns
    var select = $(
        `<select class="form-control text-center w-100">
            <option class="text-center" value="">Show All</option>
        </select>`
    )
        .appendTo( $(column.footer()).empty() )
        .on('change', function() {
            var val =  $.fn.dataTable.util.escapeRegex($(this).val());
            column
                .search(val ? "^" + val + "$" : "", true, false)
                .draw();
        });

    //  For each column append options specified by the unique values of the column
    column
        .data().unique().sort()
        .each(function(d, j) {
            select.append(
                '<option value="' + d + '">' + d + "</option>"
            );
        });
}

/* --------------------------------------------------------------
 * Hadle appending of input fields to the datatables filter row
 * --------------------------------------------------------------
 */
const appendInputFilter = ( column, disabled = false ) => {
    let html =  $(`<input class="form-control w-100" placeholder="Search..." ${ disabled ? 'disabled' : '' }>`)
    // Append the input fiels on the specified columns
    html.appendTo( $(column.footer()).empty() ).on( 'keyup change', function () {
        if ( column.search() !== this.value ) {
            column.search( this.value ).draw();
        }
    });
}

/* --------------------------------------------------------------
 * Hadle appending of date pickers into the table
 * --------------------------------------------------------------
 */
const appendDatePicker = ( table, column, format ) => {
    //  Get the table id
    const id = table.table().node().id

    //  Get the input and convert it to a data range object
    $(`<input 
            type="text" 
            name="daterange" 
            class="form-control w-100" 
            data-id="${id}"
            data-column="${column.index()}" 
            data-format="${format}" 
        />`)
        .appendTo( $(column.footer()).empty() )
        .on('change', function(){
            table.draw()
        })

    //  Append a hidden start and end date
    $(`
        <input type="hidden" name="${id}-start_date_${column.index()}">
        <input type="hidden" name="${id}-end_date_${column.index()}">
    `).appendTo('body')

    //  Add searching options
    addDateSearchOptions(column, format)
}

/* --------------------------------------------------------------
 * Hadle appending of range pickers into the table
 * --------------------------------------------------------------
 */
const appendRangePicker = ( table, column ) => {
    const index = column.index()
    const name = $(column.header()).html()
    const id = `range-picker-${index}`
    $(`<input 
        id="input-${id}"
        tabindex="0"
        name="input-${id}" 
        type="text" 
        readonly="true"
        data-toggle="popover", 
        title="${name} Range Picker"
        data-content="${ getPopoverContent(index, id) }"
        class="form-control range-input" 
        placeholder="Range Search"
    >`)
    .appendTo( $(column.footer()).empty() )

    //  Call the search by range function
    addRangeSearchOptions( index, id )

    //  Append for the change event from the document
    $(document).on('change', `input#input-${id}`, function(){
        table.draw()
    })
}

/* --------------------------------------------------------------
 * Return the pop over content and append it to the popover
 * --------------------------------------------------------------
 */
const getPopoverContent = ( column, id ) => {
    return `
        <div class="row" id="${id}">
            <div class="col-lg-12 mt-1">
                <input name="start_range" type="number" class="form-control form-control-sm" placeholder="Start of Range" value="">
            </div>
            <div class="col-lg-12 mt-1">
                <input name="end_range" type="number" class="form-control form-control-sm" placeholder="End of Range" value="">
            </div>
            <div class="col-lg-12 mt-1">
                <div class="row">
                    <div class="col-lg-4 pr-1">
                        <button class="btn btn-sm btn-primary w-100 range-filter-action" data-id="${id}">Filter</button>
                    </div>
                    <div class="col-lg-4 px-0">
                        <button class="btn btn-sm btn-danger w-100" onclick="closePopup('${id}')">Close</button>
                    </div>
                    <div class="col-lg-4 pl-1">
                        <button class="btn btn-sm btn-info w-100 range-filter-clear" data-id="${id}">Clear</button>
                    </div>
                </div>
            </div>
        </div>                  
    `
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

/**
 * ------------------------------------------------------------------------
 * Close popup
 * ------------------------------------------------------------------------
 */
window.closePopup = ( id ) => {
    $(`#input-${id}`).popover('hide')
}

/* --------------------------------------------------------------
 * This function clones header values into the footer
 * --------------------------------------------------------------
 */
window.cloneHeader = ( tableID ) => {
    if(!$(`${tableID} tfoot`).length) $(tableID).append('<tfoot></tfoot>')
    $(`${tableID} tfoot`).insertAfter(`${tableID} thead`).html( $(`${tableID} thead tr`).clone() );
    $(`${tableID} tfoot th`).addClass('p-1').html('');
}

/* --------------------------------------------------------------
 * This function creates an array of table headers
 * --------------------------------------------------------------
 */
window.getHeadersAsArray = ( tableID ) => {
    let headers = []
    $(`${ tableID } thead tr th`).each( function(){
        headers.push({
            'name': $(this).html().replace(/\s+/g, '_').toLowerCase()
        })
    })
    return headers
}

/* --------------------------------------------------------------
 * This adds an empty column
 * --------------------------------------------------------------
 */
window.insertEmptyColumn = ( tableID, columns, position = 'prepend' ) => {
    for (var i = 0; i < columns; i++) {
        $(`${ tableID } thead tr`).prepend('<th class="text-center"></th>')
        switch ( position ) {
            case 'prepend':
                $(`${ tableID } tbody tr`).each( function() {
                    $(this).prepend('<td class="text-center"></td>')                
                })
            break
            case 'append':
                $(`${ tableID } tbody tr`).each( function() {
                    $(this).append('<td class="text-center"></td>')                
                })
            break
        }
    }    
}

/* --------------------------------------------------------------
 * Add numbering of datatables in the first column
 * --------------------------------------------------------------
 */
window.addRowIndexes = ( table, index = 0) => {
    table.on('order.dt search.dt', function () {
        table.column( index , { search:'applied', order:'applied' }).nodes().each( function ( cell, i ) {
            cell.innerHTML = i + 1;
            table.cell(cell).invalidate('dom'); 
        });
    }).draw(); 
}

/* --------------------------------------------------------------
 * Handle the selection of a date range
 * --------------------------------------------------------------
 */
window.addDateSearchOptions = ( column, format ) => {
    // Extend dataTables search, add date searching functionality
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        let start = $(`input[name=start_date_${column.index()}]`).val()
        let end = $(`input[name=end_date_${column.index()}]`).val()

        //  If start and end date are not defined, return true
        if(!start && !end) return true

        // Filter result by start date and end date 
        start = moment( start, format)
        end = moment( end, format)
        return moment(data[column.index()], format).isBetween(start, end, 'days', true)
    })
}

/**
 * ------------------------------------------------------------------------
 * Function that will be used to handle the range search
 * ------------------------------------------------------------------------
 */
window.addRangeSearchOptions = ( column, id ) => {
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            const start = parseInt( $(`#${id} input[name=start_range]`).val(), 10 )
            const end = parseInt( $(`#${id} input[name=end_range]`).val(), 10 )
            var value = parseFloat( data[column] )
            if ( ( isNaN( start ) && isNaN( end ) ) ||
            ( isNaN( start ) && value <= end ) ||
            ( start <= value && isNaN( end ) ) ||
            ( start <= value && value <= end ) )
            {
                return true;
            }
            return false
        }
    );
}

/* --------------------------------------------------------------
 *  Add an input form to declare a custom input
 * --------------------------------------------------------------
 */
window.initializeCustomTitle = () => {
    $('div.title-input').html(`
        <input 
            style="width: 300px;"
            type="text" 
            class="form-control form-control-sm" 
            placeholder="Insert table heading" 
        />
    `)
}