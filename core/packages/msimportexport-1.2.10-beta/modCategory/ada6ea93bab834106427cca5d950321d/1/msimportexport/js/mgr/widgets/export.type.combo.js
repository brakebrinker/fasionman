Msie.combo.ExportType = function(config) {
    config = config || {};
    var data = [
        [_('msimportexport.export.combo.products'),'products']
        ,[_('msimportexport.export.combo.links'),'links']
        ,[_('msimportexport.export.combo.categories'),'categories']
    ];
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: data
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,hiddenName: 'export_type'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,preventRender: true
        ,forceSelection: true
        ,enableKeyEvents: true
    });
    Msie.combo.ExportType.superclass.constructor.call(this,config);
};
Ext.extend(Msie.combo.ExportType,MODx.combo.ComboBox);
Ext.reg('msie-combo-export-type',Msie.combo.ExportType);