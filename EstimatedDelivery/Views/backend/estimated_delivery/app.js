/**
 * VendorEstimatedDelivery — Backend App Bootstrap
 */
//{block name="backend/estimated_delivery/app"}
Ext.define('Shopware.apps.EstimatedDelivery', {
    extend: 'Enlight.app.SubApplication',

    name: 'Shopware.apps.EstimatedDelivery',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: ['Main'],
    views:       ['main.Form'],
    models:      [],
    stores:      [],

    launch: function () {
        var me = this;

        var win = me.getController('Main').getView('main.Form').create({
            width:  700,
            height: 600
        });

        // Load current config
        Ext.Ajax.request({
            url: '{url controller="EstimatedDeliveryConfig" action="load"}',
            success: function (response) {
                var data = Ext.decode(response.responseText);
                if (data.success && data.data) {
                    win.getForm().setValues(data.data);
                }
            }
        });

        win.show();
        return win;
    }
});
//{/block}
