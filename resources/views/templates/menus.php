<section class="menus">

    <div class="menus-close button button--icon">
        <div class="icon">
            <? $icon('close'); ?>
        </div>
    </div>

    <?php
        foreach ([] as $menu) {
            require "menus/${menu}.php";
        }
    ?>

</section>
