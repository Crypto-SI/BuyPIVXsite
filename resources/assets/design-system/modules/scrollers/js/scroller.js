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
