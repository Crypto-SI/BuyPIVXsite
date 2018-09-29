/**
 *------------------------------------------------------------------------------
 *
 *  Toggle Modifier Provided Within Data Attribute Or Use Default Modifier
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = "[data-trigger='toggle']",
        modifier = 'active';

    var elements = document.querySelectorAll( find );


    for (var i = 0, n = elements.length; i < n; i++) {
        elements[i].addEventListener('click', function( e ) {
            e.stopPropagation();

            if (e.currentTarget == e.target) {
                this.classList.add( modifier );
            }
        });
    }

    document.addEventListener('click', function( e ) {
        var elements = document.querySelectorAll( `${find}.${modifier}` );

        for (var i = 0, n = elements.length; i < n; i++) {
            elements[i].classList.remove( modifier );
        }
    });


})( window, document );
