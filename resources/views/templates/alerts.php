<section class="alerts">

    <?php
        foreach ([
            'errors',
            'success'
        ] as $alert) {
            require "alerts/${alert}.php";
        }
    ?>

</section>
