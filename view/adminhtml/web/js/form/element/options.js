define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function (_, uiRegistry, select, modal) {
    'use strict';
    return select.extend({

        initialize: function (){

            var field1 = uiRegistry.get('index = author');
            var field2 = uiRegistry.get('index = custom_author');
            var status = this._super().initialValue;
            if (status == 0) {
                field1.show();
                field2.hide();
            } else{
                field2.show();
                field1.hide();
            }
            return this;
        },

        /**
         * @param value
         * @returns {*}
         */
        onUpdate: function (value) {

            var field1 = uiRegistry.get('index = author');
            var field2 = uiRegistry.get('index = custom_author');

            if (value == 0) {
                field1.show();
                field2.hide();
            } else {
                field1.hide();
                field2.show();
            }
            return this._super();
        },
    });
});