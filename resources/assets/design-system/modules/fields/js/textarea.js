/**
 *------------------------------------------------------------------------------
 *
 *  Autoresize Textarea On Keypress
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = 'field-tag--autoresize';

    var elements = document.getElementsByClassName( find );


    function resize( element ) {
        if ( element.clientHeight < element.scrollHeight ) {
            element.style.height = `${element.scrollHeight}px`;
        }
    }


    for (var i = 0, n = elements.length; i < n; i++) {
        var element = elements[i];

        resize( element );

        element.addEventListener('keyup', function() {
            resize( this );
        });
        element.addEventListener('keydown', function() {
            resize( this );
        });
    }


})( window, document );
