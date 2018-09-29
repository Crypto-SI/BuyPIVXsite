/**
 *------------------------------------------------------------------------------
 *
 *  On Select Change Update Mask
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    // TODO: PHP Template Should Set Default Text
    var find = {
            container: 'field-text--select',
            mask: 'field-mask',
            tag: 'field-tag'
        };

    var elements = document.getElementsByClassName( find.container );


    function update( element ) {
        var mask = element.getElementsByClassName( find.mask )[0] || null,
            tag = element.getElementsByClassName( find.tag )[0] || null;

        if ( mask && tag ) {
            mask.textContent = tag.options[tag.selectedIndex].text;
        }
    }


    for (var i = 0, n = elements.length; i < n; i++) {
        update( elements[i] );

        elements[i].addEventListener('change', function() {
            update( this );
        });
    }


})( window, document );
