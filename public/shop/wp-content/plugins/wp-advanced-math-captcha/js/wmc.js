/* Woo Checkout Block */
(function waitForWP_captcha() {
    if (typeof wp !== 'undefined' && wp.data) {
        var unsubscribe = wp.data.subscribe(function () {
            jQuery('#mc-input').on('input', function () {
                wp.data.dispatch('wc/store/checkout').__internalSetExtensionData('wmc', {
                    token: jQuery('#mc-input').val()
                });
                unsubscribe();
            });
        });
    } else {
        setTimeout(waitForWP_captcha, 100); // wait for wp
    }
})();



