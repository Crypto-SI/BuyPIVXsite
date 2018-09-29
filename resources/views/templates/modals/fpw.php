<div class="modal modal--small" data-id='fpw'>

    <div class="modal-signin modal-fpw">
        <form action="/" class='modal-signin-form' method='post'>

            <div class="page-header">
                <h2 class="page-header-title">Reset Password</h2>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Username</span>

                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='username' type="text">
                </label>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">New Password</span>

                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='pw' type="password">
                </label>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Confirm New Password</span>

                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='cpw' type="password">
                </label>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Master Recovery Code</span>

                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='cpw' type="password">
                </label>
            </div>

            <button class="modal-signin-submit button button--large button--purple" name='upw' value='1'>Update Password</button>
        </form>
    </div>

</div>

<? if (isset($upw_form)): ?>

    <div class="modal modal--small active" data-id='cpw'>

        <div class="modal-signin modal-cpw">
            <form action="<? echo $upw_form; ?>" class='modal-signin-form' method='post'>

                <div class="page-header">
                    <h2 class="page-header-title">Update Password</h2>
                </div>

                <div class="field field--required field--border field--purple">
                    <span class="field-title field-title--color">New Password</span>

                    <label class="field-text field-text--input">
                        <input class="field-mask field-tag" name='pw' type="password">
                    </label>
                </div>

                <div class="field field--required field--border field--purple">
                    <span class="field-title field-title--color">Confirm New Password</span>

                    <label class="field-text field-text--input">
                        <input class="field-mask field-tag" name='cpw' type="password">
                    </label>
                </div>

                <button class="modal-signin-submit button button--large button--purple" name='upw' value='1'>Update password</button>
            </form>
        </div>

    </div>

<? endif; ?>
