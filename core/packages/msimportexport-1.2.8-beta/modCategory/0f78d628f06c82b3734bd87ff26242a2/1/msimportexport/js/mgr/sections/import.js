Msie.page.Import = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'msie-panel-import'
        ,buttons: [{
            text: _('msimportexport.btn_save_settings')
            ,id: 'msie-btn-save'
            ,cls: 'primary-button'
            ,process: 'mgr/import/settings'
            ,method: 'remote'
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        }]
        ,components: [{
            xtype: 'msie-panel-import'
            ,is_pemain: config.is_pemain
            ,options: config.options
            ,renderTo: 'msie-panel-import-div'
        }]
    });
    Msie.page.Import.superclass.constructor.call(this,config);
};
Ext.extend(Msie.page.Import,MODx.Component,{});
Ext.reg('msie-page-import',Msie.page.Import);