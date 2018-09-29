/**
 *------------------------------------------------------------------------------
 *
 *  Provides Opening/Closing Functionality For Modals And Menus
 *
 */

window.template = (function( key, config ) {

    // Produce Stricter Errors
    'use strict';


    // Container
    let container = document.getElementsByClassName( config.container )[0] || null;

    if (!container) {
        return;
    }


    var close = {
            parent: function(parent = container) {
                container.dispatchEvent(event.closed);
                parent.classList.remove( modifier );

                // Let Close Animation Finish Before Removing Modifiers
                setTimeout(() => {
                    parent.classList.remove( ...modifiers );

                    // Reset
                    modifiers = [];
                }, 300);
            },
            children: function(skip = null) {
                for (var i = 0, n = children.length; i < n; i++) {
                    var child = children[i];

                    if (skip && child === skip) {
                        continue;
                    }

                    child.classList.remove( modifier );
                    child.dispatchEvent(event.closed);
                }
            }
        },
        event = {
            closed: new Event('closed'),
            opened: new Event('opened')
        },
        find = function(attr) {
            return container.querySelectorAll( `[${config.attr.child}='${attr}']` )[0] || null;
        },
        modifier = 'active';

    // Individual Children + Button Triggers
    let children = container.getElementsByClassName( config.children ),
        triggers = document.querySelectorAll( `[${config.attr.trigger.activate}]` ),

        // Hold Modifiers That Have Been Applied To Container
        modifiers = [];


    // Close All Children If Container Is Clicked
    container.addEventListener('click', function( e ) {
        if (e.target !== this) {
            return;
        }

        close.children();
        close.parent( this );
    });

    container.addEventListener('opened', function() {
        document.documentElement.classList.add('noscroll');
    });

    container.addEventListener('closed', function() {
        document.documentElement.classList.remove('noscroll');
    });


    // Open/Close Children
    for (var i = 0, n = triggers.length; i < n; i++) {
        triggers[i].addEventListener('click', function() {
            var attr = this.getAttribute( config.attr.trigger.activate ),
                child = container.querySelectorAll( `[${config.attr.child}='${attr}']` )[0] || null;

            if (!child) {
                return;
            }

            close.children( child );
            child.classList.toggle( modifier );

            if (child.classList.contains( modifier )) {
                var attr = this.getAttribute( config.attr.trigger.modifier ),
                    add = `${config.container}--${attr}`;

				container.classList.add( modifier );

                if (attr) {
                    container.classList.add( add );
                    modifiers.push( add );
                }

                child.dispatchEvent(event.opened);
                container.dispatchEvent(event.opened);
            }
            else {
                child.dispatchEvent(event.closed);
                close.parent();
            }
        });
    }


    window[key] = {
        container, children, find, modifier, triggers
    };
});
