/**
 * VendorEstimatedDelivery — Backend Config Form View (ExtJS)
 */
//{block name="backend/estimated_delivery/view/main/form"}
Ext.define('Shopware.apps.EstimatedDelivery.view.main.Form', {
    extend: 'Ext.form.Panel',
    alias:  'widget.estimated-delivery-form',
    title:  '{s name="config_title"}Estimated Delivery Settings{/s}',
    bodyPadding: 20,
    border: false,
    autoScroll: true,

    defaults: {
        labelWidth: 220,
        anchor: '100%'
    },

    initComponent: function () {
        var me = this;

        me.items = [
            {
                xtype: 'fieldset',
                title: '{s name="fieldset_general"}General{/s}',
                defaults: { labelWidth: 220, anchor: '100%' },
                items: [
                    {
                        xtype: 'checkbox',
                        name: 'active',
                        fieldLabel: '{s name="field_active"}Enable plugin{/s}',
                        inputValue: 1,
                        uncheckedValue: 0
                    },
                    {
                        xtype: 'textfield',
                        name: 'label_text',
                        fieldLabel: '{s name="field_label"}Label text{/s}',
                        allowBlank: false
                    },
                    {
                        xtype: 'textfield',
                        name: 'date_format',
                        fieldLabel: '{s name="field_date_format"}Date format (PHP){/s}',
                        helpText: 'e.g. D, d M  →  Mon, 01 Jan'
                    }
                ]
            },
            {
                xtype: 'fieldset',
                title: '{s name="fieldset_processing"}Processing & Delivery{/s}',
                defaults: { labelWidth: 220, anchor: '100%' },
                items: [
                    {
                        xtype: 'numberfield',
                        name: 'processing_days',
                        fieldLabel: '{s name="field_processing_days"}Processing days{/s}',
                        minValue: 0,
                        maxValue: 30
                    },
                    {
                        xtype: 'numberfield',
                        name: 'cutoff_hour',
                        fieldLabel: '{s name="field_cutoff"}Order cutoff hour (0–23){/s}',
                        helpText: 'Orders placed after this hour count as next-day.',
                        minValue: 0,
                        maxValue: 23
                    },
                    {
                        xtype: 'checkbox',
                        name: 'exclude_weekends',
                        fieldLabel: '{s name="field_weekends"}Skip weekends{/s}',
                        inputValue: 1,
                        uncheckedValue: 0
                    },
                    {
                        xtype: 'textareafield',
                        name: 'exclude_holidays',
                        fieldLabel: '{s name="field_holidays"}Holidays (YYYY-MM-DD){/s}',
                        helpText: 'Comma-separated. e.g. 2025-12-25, 2025-01-01',
                        rows: 4
                    }
                ]
            }
        ];

        me.dockedItems = [{
            xtype: 'toolbar',
            dock: 'bottom',
            items: ['->', {
                xtype: 'button',
                text: '{s name="btn_save"}Save{/s}',
                action: 'save',
                cls: 'primary'
            }]
        }];

        me.callParent(arguments);
    }
});
//{/block}
