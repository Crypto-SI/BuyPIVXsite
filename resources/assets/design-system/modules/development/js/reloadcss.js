/**
 *--------------------------------------------------------------------------
 *
 *  Frontend Development Helper
 *
 *  Displays Browser Dimensions, And Reloads CSS On Button Press
 *
 */

(function( window, document ) {

    // Produce Stricter Errors
    'use strict';


    // Abort When Site Container Is Missing
    var site = document.getElementsByClassName( 'site' )[0] || null;

    if (!site) {
        return;
    }


    var html = `
            <span class='development-dimensions'>${window.innerWidth} x ${window.innerHeight}</span>
            <span class='development-reloadcss'>R</span>
        `,

        development = document.createElement('div'),
        stylesheet = document.getElementById('stylesheet');


    // Insert HTML
    development.innerHTML = html;
    development.className = 'development';

    site.parentNode.insertBefore(development, site);


    // Handle Reload/Resize Triggers
    let dimensions = document.getElementsByClassName('development-dimensions')[0] || null,
        reload = document.getElementsByClassName('development-reloadcss')[0] || null;

    if (!dimensions || !reload) {
        return;
    }


    // Reload CSS
    reload.addEventListener('click', () => {
        stylesheet.setAttribute('href', '/public/css/app.css?js=' + Math.random( 0, 10000 ));
    });


    // Update Dimensions Text
    window.addEventListener('resize', () => {
        dimensions.textContent = `${window.innerWidth} x ${window.innerHeight}`;
    });


})( window, document );
