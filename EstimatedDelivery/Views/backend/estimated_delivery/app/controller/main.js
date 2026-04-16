/**
 * VendorEstimatedDelivery — Backend Config Controller (ExtJS)
 */
//{block name="backend/estimated_delivery/controller/main"}
Ext.define('Shopware.apps.EstimatedDelivery.controller.Main', {
    extend: 'Enlight.app.Controller',

    refs: [
        { ref: 'form', selector: 'estimated-delivery-form' }
    ],

    init: function () {
        var me = this;

        me.control({
            'estimated-delivery-form button[action=save]': {
                click: me.onSave
            }
        });

        me.callParent(arguments);
    },

    onSave: function () {
        var me   = this;
        var form = me.getForm().getForm();

        if (!form.isValid()) {
            return;
        }

        form.submit({
            url: '{url controller="EstimatedDeliveryConfig" action="save"}',
            success: function () {
                Shopware.Notification.createGrowlMessage(
                    '{s name="save_success_title"}Saved{/s}',
                    '{s name="save_success_msg"}Settings saved successfully.{/s}',
                    'EstimatedDelivery'
                );
            },
            failure: function (form, action) {
                Shopware.Notification.createGrowlMessage(
                    '{s name="save_error_title"}Error{/s}',
                    action.result.message || '{s name="save_error_msg"}Could not save settings.{/s}',
                    'EstimatedDelivery'
                );
            }
        });
    }
});
//{/block}
