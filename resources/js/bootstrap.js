window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');
    
    window.JSZip = require('jszip');
    window.pdfMake = require('pdfmake/build/pdfmake.js');
    window.pdfFonts = require('pdfmake/build/vfs_fonts.js');
    pdfMake.vfs = pdfFonts.pdfMake.vfs;

    require('fs');
    require('datatables.net-dt');
    require('datatables.net-buttons-dt');
    require('datatables.net-buttons/js/buttons.colVis');
    require('datatables.net-buttons/js/buttons.print');
    require('datatables.net-buttons/js/buttons.html5');
} catch (e) {}