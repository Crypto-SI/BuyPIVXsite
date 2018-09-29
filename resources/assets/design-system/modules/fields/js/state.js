/**
 *------------------------------------------------------------------------------
 *
 *  Simplify Handling Field States
 *
 *  Modifiers Were Originally Dependent On Form Element ':focus' ':checked'
 *  Selectors To Modify States Resulting In Long Selectors Once Compiled.
 *
 *  JS Unifies States By Shifting Modifiers To Parent
 *  - Also Enables Sticking To A Unified State System Across All Modules!
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    // TODO: PHP Template Should Handle Default State
    var find = {
            container: 'field',
            tag: 'field-tag'
        },
        modifier = 'active';

    let elements = document.getElementsByClassName( find.container ),
        event = new Event( 'change' );


    for (var i = 0, n = elements.length; i < n; i++) {
        let element = elements[i],
            tag = element.getElementsByClassName( find.tag )[0] || null;

        if (!tag) {
            continue;
        }

        if (['checkbox', 'radio'].includes( tag.type )) {

            if (tag.checked) {
                element.classList.add( modifier );
            }

            tag.addEventListener('change', function() {
                if (this.checked) {
                    if (this.type == 'radio') {
                        var siblings = document.getElementsByName(  this.name  );

                        for (var j = 0, o = siblings.length; j < o; j++) {
                            var sibling = siblings[j];

                            if (sibling === this) {
                                continue;
                            }

                            sibling.dispatchEvent( event );
                        }
                    }

                    element.classList.add( modifier );
                }
                else {
                    element.classList.remove( modifier );
                }
            });
        }
        else {
            tag.addEventListener('blur', function() {
                element.classList.remove( modifier );
            });

            tag.addEventListener('focus', function() {
                element.classList.add( modifier );
            });
        }
    }


})( window, document );
