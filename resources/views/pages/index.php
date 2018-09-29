<script>
    window.config = {
        description: "<? echo $description; ?>",
        fee: <? echo $fee; ?>,
        price: <? echo $price; ?>,
        stripekey: '<? echo $stripeKey; ?>'
    };
</script>

<script src='https://checkout.stripe.com/checkout.js'></script>

<section class="home-header" style="background-image: url('/public/images/home.svg');">
    <div class="container">

        <div class="home-header-form">
            <img class='home-header-brand' src="/public/images/logo.png" alt="">
            <span class="home-header-subtitle">
                Buy <b>PIVX</b> Using Paypal or Stripe!
            </span>

            <form id="purchase" method="post">
                <div class="field field--required field--border field--pivx">
                    <span class="field-title field-title--color">Amount In USD</span>

                    <label class="field-text field-text--input">
                        <input class="field-mask field-tag" name='fiat' step="0.01" type="number" value='1'>
                    </label>
                </div>

                <div class="field field--required field--border field--pivx">
                    <span class="field-title field-title--color">Total <b>PIVX</b></span>

                    <label class="field-text field-text--input">
                        <input class="field-mask field-tag" name='crypto' step="0.01" type="number" value='1'>
                    </label>
                </div>

                <div class="home-header-address field field--required field--border field--pivx">
                    <span class="field-title field-title--color"><b>PIVX</b> Address</span>

                    <label class="field-text field-text--input">
                        <input class="field-mask field-tag" name='address' type="text">
                    </label>

                    <span class="field-description">
                        Enter your PIVX Address

                        <a href="https://pivx.org/wallet/" class="home-header-create link link--inline link--pivx">
                            Create Address
                        </a>
                    </span>
                </div>

                <div class="field field--right field--pivx">
                    <label class="field-check field-check--radio">
                        <span class="field-title">Checkout With Stripe</span>

                        <span class="field-mask">
                            <input class="field-tag" name='payment' value='stripe' type="radio">

                            <input type="hidden" name='stripeamount'>
                            <input type="hidden" name='stripeemail'>
                            <input type="hidden" name='stripetoken'>
                        </span>
                    </label>
                </div>

                <div class="field field--right field--pivx">
                    <label class="field-check field-check--radio">
                        <span class="field-title">Checkout With Paypal</span>

                        <span class="field-mask">
                            <input class="field-tag" name='payment' value='paypal' type="radio" checked>
                        </span>
                    </label>
                </div>

                <p>
                    The final price will include a fee of 2.9% + $0.30 to pay the fees charged by both Paypal and Stripe
                </p>

                <button class="button button--pivx" name='purchase' value='1'>Continue</button>
            </form>
        </div>

    </div>
</section>

<section class="home-page">
    <div class="container">

        <div class="page-header">
            <h3 class="page-header-title">Frequently Asked Questions</h3>
        </div>

        <h4>Question #1</h4>
        <p>
            Think back to your training — did anything like this happen? How did you handle it?
        </p>

        <h4>Question #2</h4>
        <p>
            Ask yourself if you need to shift your goal — maybe you were hoping for a “PR” (personal record) or a specific time, but now you’re just going to focus on finishing.
        </p>

        <h4>Question #3</h4>
        <p>
            Seek out support (within race rules) — most marathons have aid stations and medical staff so if you need to get some help with a blister, chaffing, sprained ankle etc. do it. That’s what they’re here for.
        </p>

        <h4>Question #4</h4>
        <p>
            Ask yourself if you can you make the situation better with the things you can control, like pace, form, and, at least to some extent, attitude — or is it going to take a miracle? If it’s the latter, what is the likelihood of a miracle happening?
        </p>

        <h4>Question #5</h4>
        <p>
            Live to marathon (or just live) another day — a marathon will hurt no matter what, but if you fear you are putting your health in jeopardy, or that you may be doing permanent damage, it’s time to call it a day and head for the nearest aid station, medical tent, or on-course personnel. You can always decide to try again in the future.
        </p>

    </div>
</section>
