/**
 *------------------------------------------------------------------------------
 *
 *  Handle Form Fiat <-> Crypto Conversion
 *
 */

(function( window, document ) {

    'use strict';


    let form = document.getElementById('purchase');

    if (!form || !window.config.fee || !window.config.price || !window.config.stripekey) {
        return;
    }


    let description = window.config.description,
        fields = {
            crypto: document.getElementsByName('crypto')[0] || null,
            fiat: document.getElementsByName('fiat')[0] || null,

            address: document.getElementsByName('address')[0] || null,
            payment: document.getElementsByName('payment'),

            stripeamount: document.getElementsByName('stripeamount')[0] || null,
            stripetoken: document.getElementsByName('stripetoken')[0] || null,
            stripeemail: document.getElementsByName('stripeemail')[0] || null
        },
        price = (window.config.price * window.config.fee) + window.config.price,
        submit = document.getElementsByName('purchase')[0] || null,
        values = {
            crypto: 1,
            fiat: 1
        },
        stripeamount;

    if (!fields.crypto || !fields.fiat || !fields.address || !fields.payment) {
        return;
    }


    function update() {
        values = {
            crypto: parseFloat(fields.crypto.value.trim()),
            fiat: parseFloat(fields.fiat.value.trim())
        };
    }


    fields.crypto.addEventListener('blur', function() {
        update();
        fields.fiat.value = (values.crypto * price).toFixed(2);
        update();
    });

    fields.fiat.addEventListener('blur', function() {
        update();
        fields.crypto.value = (values.fiat / price).toFixed(2);
        update();
    });


    var handler = StripeCheckout.configure({
            key: window.config.stripekey,
            image: "",
            name: 'Cryptocurrency Fiat Gateway',
            token: function(token) {
                fields.stripeamount.value = stripeamount;
                fields.stripeemail.value = token.email;
                fields.stripetoken.value = token.id;

                form.submit();
            }
        });

    submit.addEventListener('click', function(e) {
        if (!fields.address.value) {
            e.preventDefault();
            window.alert.error( 'Enter A Wallet Address To Continue' );

            return;
        }

        if (fields.fiat.value < 5) {
            e.preventDefault();
            window.alert.error( 'All Purchases Must Be More Than $5' );

            return;
        }

        var stripe = false;

        for (var i = 0, n = fields.payment.length; i < n; i++) {
            var field = fields.payment[i];

            if (field.value === 'stripe' && field.checked) {
                stripe = true;
                break;
            }
        }

        if (stripe) {
            e.preventDefault();

            stripeamount = ((values.fiat * 0.029) + 0.3) + values.fiat;
            stripeamount = Math.floor(stripeamount * 100).toFixed(0);

            handler.open({
                description: description.replace('[amount]', values.crypto),
                amount: stripeamount
            });
        }
    });

})( window, document );
