/**
 *------------------------------------------------------------------------------
 *
 *  Add '.active' To Any Element On Page Using Trigger
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = 'data-activate',
        modifier = 'active';

    var elements = document.querySelectorAll( `[${find}]` );


    for (var i = 0, n = elements.length; i < n; i++) {
        elements[i].addEventListener('click', function( e ) {
            e.stopPropagation();

            var attr = this.getAttribute( find ),
                target = document.getElementsByClassName( attr )[0] || null;

            if (!target) {
                return;
            }

            var siblings = target.parentElement.children;

            for (var i = 0, n = siblings.length; i < n; i++) {
                siblings[i].classList.remove( modifier );
            }

            target.classList.add( modifier );
        });
    }


})( window, document );
