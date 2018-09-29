<div class="modal modal--small" data-id='signin'>

    <div class="modal-signin">
        <form action="/" class='modal-signin-form' method='post'>

            <div class="page-header">
                <h2 class="page-header-title">Welcome back</h2>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Email</span>
 
                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='email' type="text">
                </label>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Password</span>

                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='password' type="password">
                </label>
            </div>

            <span class="modal-signin-fpw link link--color link--purple" data-modal="fpw">Forgot Password?</span>

            <button class="modal-signin-submit button button--large button--purple" name='login' value='1'>Sign In</button>
        </form>

        <span class="modal-signin-switch">
            Need an account? <span class="link link--color link--purple link--inline" data-modal="register">Register</span>
        </span>
    </div>

</div>
