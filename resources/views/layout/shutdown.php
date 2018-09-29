<!DOCTYPE html>
<html lang='en'>
    <head>
        <?php // Preserve Order, These Tags Need To Be Listed First ?>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, height=device-height, initial-scale=0.96, minimal-ui'>

        <title>Error</title>

        <?php // Control Search Engine Behavior ?>
        <meta name='robots' content='index,follow'>
        <meta name='googlebot' content='index,follow'>

        <?php // iOS Add To Homescreen Capable ( Web App ) ?>
        <meta name='apple-mobile-web-app-capable' content='yes'>

        <?php // Android Add To Homescreen ( Web App ) ?>
        <meta name='mobile-web-app-capable' content='yes'>

        <?php // Self Explanatory ?>
        <link rel='shortcut icon' href='/favicon.ico'>
        <link href='/public/css/app.css?t=<?php echo time(); ?>' id='stylesheet' rel='stylesheet' type='text/css'>

        <!-- Needs To Be Configurable Or Added To CSS -->
        <link href='https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700' rel='stylesheet'>

        <?php // NoJS ?>
        <noscript>
            Javascript Is Required To View This Website! <br />

            <a href='https://www.enable-javascript.com/' target='_blank'>
                Follow These Instructions To Enable Javascript In Your Web Browser.
            </a>
        </noscript>
    </head>
    <body>
        <div class="container" style='margin: 40px auto;'>
            <div class="page-header">
                <h2>Mayday, Mayday!</h2>
            </div>
            <p>Seems the site has exploded! Contact <a class='link link--color link--inline link--primary' href='https://www.twitter.com/izzycasillasjr'>@izzycasillasjr</a> on twitter.</p>
        </div>
    </body>
</html>
