<? $errors = $alerts['errors'] ?? []; ?>

<div class="alert alert--error <? if($errors): ?>active<? endif; ?>">

    <ul>
        <? foreach((array) $errors as $e): ?>
            <li><? echo $e; ?></li>
        <? endforeach; ?>
    </ul>

    <div class="alert-close button button--primary button--small" data-deactivate="alert--error">Close</div>
</div>
