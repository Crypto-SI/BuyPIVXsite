/**
 *------------------------------------------------------------------------------
 *
 *  Remove '.active' From Any Element On Page Using Trigger
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = 'data-deactivate', 
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

            target.classList.remove( modifier );
        });
    }


})( window, document );

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

/**
 *------------------------------------------------------------------------------
 *
 *  Toggle Modifier Provided Within Data Attribute Or Use Default Modifier
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = "[data-trigger='toggle']",
        modifier = 'active';

    var elements = document.querySelectorAll( find );


    for (var i = 0, n = elements.length; i < n; i++) {
        elements[i].addEventListener('click', function( e ) {
            e.stopPropagation();

            if (e.currentTarget == e.target) {
                this.classList.add( modifier );
            }
        });
    }

    document.addEventListener('click', function( e ) {
        var elements = document.querySelectorAll( `${find}.${modifier}` );

        for (var i = 0, n = elements.length; i < n; i++) {
            elements[i].classList.remove( modifier );
        }
    });


})( window, document );

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

/**
 *------------------------------------------------------------------------------
 *
 *  Horizontal Scrolling Using Scroll Wheel
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = 'scroller',
        multiplier = 32,
        wheel = false;

    var elements = document.getElementsByClassName( find );


    function scroll( e ) {
        if (e.type == 'wheel') {
            wheel = true;
        }
        else if (wheel) {
            return;
        }

        var delta = Math.max( -1, Math.min( 1, (- e.deltaY / 3) )),
            width = Math.round( this.clientWidth ),

            scroll = {
                width: this.scrollWidth,
                total: this.scrollLeft + width
            };

        // Bail If Scrolling & 100% Scrolled or 0% Scrolled
        if (
            ( delta < 0 && scroll.total >= scroll.width ) ||    // On Scroll Down
            ( delta > 0 && scroll.total <= width )              // On Scroll Up
        ) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        this.scrollLeft = this.scrollLeft - ( delta * multiplier );
    }


    for (var i = 0, n = elements.length; i < n; i++) {
        var element = elements[i];

        element.addEventListener('wheel', scroll);
        element.addEventListener('mousewheel', scroll);
        element.addEventListener('DOMMouseScroll', scroll);

        // Hide Scrollbar By Calculating '.scroller' Height
        var style = window.getComputedStyle(element.parentElement),
            height = parseInt( style.paddingTop ) + parseInt( style.paddingBottom ) + parseInt( element.children[0].clientHeight );

        element.parentElement.style.height = `${height}px`;
    }


})( window, document );

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

/**
 *------------------------------------------------------------------------------
 *
 *  Tooltip
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    var find = ".tooltip:not([data-trigger='toggle'])",
        modifier = 'active';

    var elements = document.querySelectorAll( find );


    for (var i = 0, n = elements.length; i < n; i++) {
        elements[i].addEventListener('mouseenter', function() {
            this.classList.add( modifier );
        });
        elements[i].addEventListener('mouseleave', function() {
            this.classList.remove( modifier );
        });
    }


})( window, document );

/**
 *------------------------------------------------------------------------------
 *
 *  Handle Form Fiat <-> Crypto Conversion
 *
 */

(function( window, document ) {

    'use strict';


    let form = document.getElementById('purchase');

    if (!form || !window.config.fee || !window.config.price || !window.config.stripekey) {
        return;
    }


    let description = window.config.description,
        fields = {
            crypto: document.getElementsByName('crypto')[0] || null,
            fiat: document.getElementsByName('fiat')[0] || null,

            address: document.getElementsByName('address')[0] || null,
            payment: document.getElementsByName('payment'),

            stripeamount: document.getElementsByName('stripeamount')[0] || null,
            stripetoken: document.getElementsByName('stripetoken')[0] || null,
            stripeemail: document.getElementsByName('stripeemail')[0] || null
        },
        price = (window.config.price * window.config.fee) + window.config.price,
        submit = document.getElementsByName('purchase')[0] || null,
        values = {
            crypto: 1,
            fiat: 1
        },
        stripeamount;

    if (!fields.crypto || !fields.fiat || !fields.address || !fields.payment) {
        return;
    }


    function update() {
        values = {
            crypto: parseFloat(fields.crypto.value.trim()),
            fiat: parseFloat(fields.fiat.value.trim())
        };
    }


    fields.crypto.addEventListener('blur', function() {
        update();
        fields.fiat.value = (values.crypto * price).toFixed(2);
        update();
    });

    fields.fiat.addEventListener('blur', function() {
        update();
        fields.crypto.value = (values.fiat / price).toFixed(2);
        update();
    });


    var handler = StripeCheckout.configure({
            key: window.config.stripekey,
            image: "",
            name: 'Cryptocurrency Fiat Gateway',
            token: function(token) {
                fields.stripeamount.value = stripeamount;
                fields.stripeemail.value = token.email;
                fields.stripetoken.value = token.id;

                form.submit();
            }
        });

    submit.addEventListener('click', function(e) {
        if (!fields.address.value) {
            e.preventDefault();
            window.alert.error( 'Enter A Wallet Address To Continue' );

            return;
        }

        if (fields.fiat.value < 5) {
            e.preventDefault();
            window.alert.error( 'All Purchases Must Be More Than $5' );

            return;
        }

        var stripe = false;

        for (var i = 0, n = fields.payment.length; i < n; i++) {
            var field = fields.payment[i];

            if (field.value === 'stripe' && field.checked) {
                stripe = true;
                break;
            }
        }

        if (stripe) {
            e.preventDefault();

            stripeamount = ((values.fiat * 0.029) + 0.3) + values.fiat;
            stripeamount = Math.floor(stripeamount * 100).toFixed(0);

            handler.open({
                description: description.replace('[amount]', values.crypto),
                amount: stripeamount
            });
        }
    });

})( window, document );
