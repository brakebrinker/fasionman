Msie.combo.ExportFields = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        displayField: 'name'
        ,hiddenName: 'fields[]'
        ,valueField: 'val'
        ,fields: ['name','val']
        ,editable: true
        ,url:  Msie.config.connectorUrl
        ,baseParams:{
            action: 'mgr/fields/getlistexport'
            ,pemain: config.pemain || false
        }
    });
    Msie.combo.ExportFields.superclass.constructor.call(this,config);
};
Ext.extend(Msie.combo.ExportFields,MODx.combo.ComboBox);
Ext.reg('msie-combo-export-fields',Msie.combo.ExportFields);