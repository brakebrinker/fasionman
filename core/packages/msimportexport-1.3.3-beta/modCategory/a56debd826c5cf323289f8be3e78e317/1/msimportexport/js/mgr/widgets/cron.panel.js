Msie.panel.Cron = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        layout: 'form'
        , header: false
        , border: false
        , defaults: {border: false}
        , cls: 'form-with-labels'
        , style: {'margin-top': '10px'}
        , items: [{
            xtype: 'msie-grid-cron'
            , type: config.type || 1
            , sPemain: config.isPemain
        },{
            xtype: 'combo-boolean'
            , fieldLabel: _('setting_msimportexport.import.cron_log')
            , description: _('setting_msimportexport.import.cron_log_desc')
            , name: 'cron_log'
            , hiddenName: 'cron_log'
            , allowBlank: true
            , anchor: '100%'
            , value: config.options.cron_log
        },{
            xtype: 'textfield'
            , fieldLabel: _('msimportexport.cron.label_cron_task')
            , value: config.options.cron_file_path
            , allowBlank: true
            , readOnly: true
            , anchor: '100%'
        }]
    });
    Msie.panel.Cron.superclass.constructor.call(this, config);
};
Ext.extend(Msie.panel.Cron, MODx.Panel, {});
Ext.reg('msie-panel-cron', Msie.panel.Cron);
