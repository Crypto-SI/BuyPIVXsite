/**
 *------------------------------------------------------------------------------
 *
 *  Trigger/Create Alert
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = {
            error: '.alert--error ul',
            success: '.alert--success ul'
        },
        modifier = 'active';

    var elements = {
            error: document.querySelector( find.error ),
            success: document.querySelector( find.success )
        },
        handle = function( element, messages ) {
            element.innerHTML = '';

            if (typeof messages === 'string') {
                messages = [messages];
            }

            for (var i = 0, n = messages.length; i < n; i++) {
                var child = document.createElement('li');
                    child.textContent = messages[i];

                element.appendChild( child );
            }

            element.parentElement.classList.add( modifier );
        };


    window.alert = {
        error: function( messages ) {
            handle( elements.error, messages );
        },
        success: function( messages ) {
            handle( elements.success, messages );
        }
    };


})( window, document );
