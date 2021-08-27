/* --------------------------------------------------------------
* Require datatables dependancies
* --------------------------------------------------------------
*/
require('./datatables-exports-styles')

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
        let key = this.api().column( `${item.name}:name` ).index()
        filtersObj[key] = { type: item.type, format: item.js_format, options: item.options }
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
                let filter = filtersObj[columnIndex];

                //  Check what kind of filter is defined for a column
                switch ( filter.type ) {
                    case 'select':
                        appendSelectFilter(column, filter.options)
                        break

                    case 'input':
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
const appendSelectFilter = ( column, options = null ) => {
    //  Append the select boxes on the specified columns
    var select = $(
        `<select class="select-search">
            <option value="">Show All</option>
        </select>`
    )
        .appendTo( $(column.footer()).empty() )
        .on('change', function() {
            column
                .search($(this).val(), true, false)
                .draw();
        });

    //  For each column append options specified by the unique values of the column
    options = options ? options : column.data().unique().sort().toArray()
    options.map( ( option ) => {
        select.append(
            `<option value="${option}">${option}</option>`
        );
    })
}

/* --------------------------------------------------------------
 * Hadle appending of input fields to the datatables filter row
 * --------------------------------------------------------------
 */
const appendInputFilter = ( column, disabled = false ) => {
    let html =  $(`<input class="input-search" placeholder="Search..." ${ disabled ? 'disabled' : '' }>`)

    // Append the input fiels on the specified columns
    html.appendTo( $(column.footer()).empty() ).on( 'keyup change', function () {
        if ( column.search() !== this.value ) {
            column.search( this.value ).draw();
        }
    });
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
 * This function creates an array of table headers from the thead
 * tag
 * --------------------------------------------------------------
 */
window.getHeadersFromHtml = (tableID, columns = []) => {
    //  Define an empty headers array
    let headers = []

    //  Get the table headers
    $(`${ tableID } thead tr th`).each( function(){
        //  Get the header name and set a default data type
        let name = $(this).html().replace(/\s+/g, '_').toLowerCase();
        let type = "string";

        //  Define the data type
        let column = columns.find((column) => column.name == name);
        if(column && column.hasOwnProperty('data_type')) type = column['data_type'];

        //  Insert into the headers array
        headers.push({
            data : name,
            name : name,
            type : type
        })
    });

    //  Return the headers
    return headers;
}

/* --------------------------------------------------------------
 * This adds an empty column
 * --------------------------------------------------------------
 */
window.insertEmptyColumn = ( tableID, columns, position = 'prepend' ) => {
    for (var i = 0; i < columns; i++) {
        $(`${ tableID } thead tr`).prepend('<th>#</th>')
        switch ( position ) {
            case 'prepend':
                $(`${ tableID } tbody tr`).each( function() {
                    $(this).prepend('<td></td>')                
                })
            break
            case 'append':
                $(`${ tableID } tbody tr`).each( function() {
                    $(this).append('<td></td>')                
                })
            break
        }
    }    
}

/* --------------------------------------------------------------
 * This gets all columns that should be hidden
 * --------------------------------------------------------------
 */
window.getHiddenColumns = (columns = []) => {
    return columns.filter((column) => column.hidden != 0).map((column) => column.name);
} 

/* --------------------------------------------------------------
 * This gets all columns that should be sorted
 * --------------------------------------------------------------
 */
window.getSortingOrder = (columns = [], headers = []) => {
    return columns.filter((column) => column.sorting !== null).map((column) => {
        let index = headers.findIndex(col => col.name == column.name);
        return [index, column.sorting];
    });
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
 *  Add an input form to declare a custom input
 * --------------------------------------------------------------
 */
window.initializeCustomTitle = (title = '') => {
    $('div.title-input').html(`<input type="text" placeholder="Insert custom report title" value="${title}"/>`)
}