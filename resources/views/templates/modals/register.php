<div class="modal modal--small" data-id="register">

    <div class="modal-signin modal-register">
        <form action="/" class='modal-signin-form' method='post'>

            <div class="page-header">
                <h2 class="page-header-title">Create an account</h2>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Email</span>
 
                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='email' type="text">
                </label>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Username</span>

                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='username' type="text">
                </label>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Password</span>

                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='password' type="password">
                </label>
            </div>


            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Master Recovery Key (click to copy)</span>

                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='password' type="text" value="Place Key String Here">
                </label>
            </div>

            <div class="field field--required field--border field--purple">
                <span class="field-title field-title--color">Confirm Master Recovery Key</span>

                <label class="field-text field-text--input">
                    <input class="field-mask field-tag" name='password' type="text" value="Place Key String Here">
                </label>
            </div>


            <span class="modal-register-terms">
                By registering you agree to our
                <a href="#" class="link link--inline link--purple">Terms Of Service</a>
                and
                <a href="#" class="link link--inline link--purple">Privacy Policy</a>
            </span>

            <button class="modal-signin-submit button button--large button--purple" name='registration' value='1'>Continue</button>

        </form>

        <div class="modal-signin-switch">
            Already have an account? <span class="link link--color link--purple link--inline" data-modal="signin">Sign In</span>
        </div>
    </div>

</div>
