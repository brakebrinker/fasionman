Msie.combo.Сatalog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        displayField: 'pagetitle'
        ,hiddenName: 'catalog'
        ,valueField: 'id'
        ,fields: ['pagetitle','id']
        ,editable: true
        ,url:  Msie.config.connectorUrl
        ,baseParams:{
            action: 'mgr/catalog/getlist'
        }
    });
    Msie.combo.Сatalog.superclass.constructor.call(this,config);
};
Ext.extend(Msie.combo.Сatalog,MODx.combo.ComboBox);
Ext.reg('msie-combo-catalog',Msie.combo.Сatalog);