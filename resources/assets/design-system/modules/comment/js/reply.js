/**
 *------------------------------------------------------------------------------
 *
 *  Reply To Comment
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = {
            comment: '.proposal-drawer form',
            reply: 'comments-submit-reply',
            trigger: 'comment-reply'
        };

    var comment = document.querySelector( find.comment ),
        triggers = document.getElementsByClassName( find.trigger );

    if ( comment ) {
        var clone = comment.cloneNode(true);
    }


    for (var i = 0, n = triggers.length; i < n; i++) {
        var trigger = triggers[i];

        if ( comment ) {
            trigger.addEventListener('click', function() {
                var id = this.parentElement.parentElement.dataset.id,
                    hidden = clone.getElementsByClassName( find.reply )[0] || null;

                if ( hidden ) {
                    hidden.value = id;
                    this.parentElement.appendChild( clone );
                }
            });
        }
        else {
            trigger.addEventListener('click', function() {
                window.alert.error( 'You Must Login To Submit A Comment' );
            });
        }
    }


})( window, document );
