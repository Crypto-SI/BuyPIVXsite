/**
 *------------------------------------------------------------------------------
 *
 *  Kill Click Events For Disabled Elements
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    // TODO: Replace Most With PHP UI Code
    var find = {
            disabled: 'disabled',
            field: 'field-tag'
        },
        tagNames = [
            'INPUT',
            'TEXTAREA',
            'SELECT',
            'BUTTON'
        ];

    var elements = document.getElementsByClassName( find.disabled );


    for (var i = 0, n = elements.length; i < n; i++) {
        var element = elements[i],
            field = element.getElementsByClassName( find.field )[0] || null;

        if (element.tagName == 'A') {
            element.removeAttribute( 'href' );
        }

        // Handles Fields Not Using The '.field' Module
        if (tagNames.includes( element.tagName )) {
            element.disabled = true;
        }

        // Handles '.field' Module
        if (field) {
            field.disabled = true;
        }

        element.addEventListener('click', ( e ) => {
            e.preventDefault();
        });
    }


})( window, document );
