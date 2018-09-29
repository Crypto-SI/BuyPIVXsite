/**
 *------------------------------------------------------------------------------
 *
 *  Modal Manager
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    window.template('modals', { 
        attr: {
            child: 'data-id',
            trigger: {
                activate: 'data-modal',
                modifier: 'data-modal-modifier'
            }
        },
        container: 'modals',
        children: 'modal'
    });


})( window, document );
