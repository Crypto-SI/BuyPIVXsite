<? $success = $alerts['success'] ?? []; ?>

<div class="alert alert--success <? if($success): ?>active<? endif; ?>">

    <ul>
        <? foreach((array) $success as $s): ?>
            <li><? echo $s; ?></li>
        <? endforeach; ?>
    </ul>

    <div class="alert-close button button--green button--small" data-deactivate="alert--error">Close</div>
</div>
