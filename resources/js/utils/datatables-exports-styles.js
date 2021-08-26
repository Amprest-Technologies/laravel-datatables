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
    $(body).css({ 'font-size' : '10pt'})

    //  Table styling
    $(body).find('table').addClass('compact').css({
        'font-size' : 'inherit'
    })
    
    //  Remove the footer
    $(body).find( 'table' ).find('tfoot').remove(); 

    //  Center the title
    $(body).find('h1').css({
        'text-align': 'center',
        'margin-top': '1rem',
        'margin-bottom': '0',
        'font-weight': 'bold',
        'font-size': '1.7rem'
    });

    //  Message top styles
    $(body).find('h5.message-top').css({
        'margin-top': '.3rem', 
        'padding-bottom': '.8rem', 
        'margin-bottom': '.8rem', 
        'text-align': 'center', 
        'font-weight': 'bold', 
        'border-bottom': '1px solid #eef1f3',
    });
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
            height="60" width="60" 
            style="margin:1rem auto 0 auto; display:block; position:relative; max-width: 100%;" 
            src="${path}"/
        >`
    );
}