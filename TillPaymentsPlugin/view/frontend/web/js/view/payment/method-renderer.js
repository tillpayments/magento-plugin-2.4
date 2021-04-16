define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'tillpayments_creditcard',
                component: 'TillPayments_TillPaymentsPlugin/js/view/payment/method-renderer/creditcard'
            },
        );

        return Component.extend({});
    }
);
