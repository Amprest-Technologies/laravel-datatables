/**
 * -------------------------------------------------------------------------
 * This function handles the styling of the print and the print preview pages
 * -------------------------------------------------------------------------
 */
window.printStyles = ( win, logo = false ) => {
    //  Define the body
    const body = win.document.body

    //  Include the logo if the path is specified
    if (logo) insertLogo(body, logo)

    //  The body styling
    $(body).css({
        'font-size' : '10pt',
    })

    //  Table styling
    $( body ).find( 'table' ).addClass( 'compact' ).css({
        'font-size' : 'inherit'
    })
    
    //  Remove the footer
    $(body).find( 'table' ).find('tfoot').remove(); 
}

/**
 * -------------------------------------------------------------------------
 * This function handles the insertion of logos
 * -------------------------------------------------------------------------
 */
window.insertLogo = ( body, path ) => {
    //  Actual script to insert the  logo
    $(body).prepend(
       `<img 
            height="120" width="120" 
            style="margin:20px auto 20px auto; display:block; position:relative;" 
            class="img-responsive" 
            src="${path}"/
        >`
    );
}