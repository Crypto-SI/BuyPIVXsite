/**
 *------------------------------------------------------------------------------
 *
 *  Tooltip
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = ".tooltip:not([data-trigger='toggle'])",
        modifier = 'active';

    var elements = document.querySelectorAll( find );


    for (var i = 0, n = elements.length; i < n; i++) {
        elements[i].addEventListener('mouseenter', function() {
            this.classList.add( modifier );
        });
        elements[i].addEventListener('mouseleave', function() {
            this.classList.remove( modifier );
        });
    }


})( window, document );
