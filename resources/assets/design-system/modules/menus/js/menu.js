/**
 *------------------------------------------------------------------------------
 *
 *  Menu Manager
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    window.template('menus', {
        attr: {
            child: 'data-id',
            trigger: {
                activate: 'data-menu',
                modifier: 'data-menu-modifier'
            }
        },
        container: 'menus',
        children: 'menu'
    });


})( window, document );
