<div class="modal modal--small" data-id="transaction-send">
    <div class="modal-send">

        <div class="page-header">
            <h3 class="page-header-title">Send Money</h3>
        </div>

        <div class="field field--required field--border field--purple">
            <span class="field-title field-title--color">Recipient</span>

            <label class="field-text field-text--input">
                <input class="field-mask field-tag" name='recipient' type="text">
            </label>

            <div class="button button--icon">
                <div class="icon"><? $icon('layout.headline'); ?></div>
            </div>

            <span class="field-description">Once social media feature is created offer autocomplete here - Create QR Code SVG as well</span>
        </div>

        <div class="field field--required field--border field--purple">
            <span class="field-title field-title--color">Amount</span>

            <label class="field-text field-text--input">
                <input class="field-mask field-tag" name='amount' placeholder='USD' type="text">
            </label>

            <span class="field-description">Once Smart price is pulled from exchanges autoupdate fiat to SMART conversion here</span>
        </div>

        <div class="button button--purple">Continue</div>
    </div>
</div>
