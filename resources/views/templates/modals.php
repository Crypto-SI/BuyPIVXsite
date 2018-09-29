<section class="modals">

    <div class="modals-close button button--icon">
        <div class="icon">
            <? $icon('close'); ?>
        </div>
    </div>

    <?php
        foreach ([] as $modal) {
            require "modals/${modal}.php";
        }
    ?>

</section>
