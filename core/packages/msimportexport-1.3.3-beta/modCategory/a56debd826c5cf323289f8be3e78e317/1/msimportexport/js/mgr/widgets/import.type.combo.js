Msie.combo.ImportType = function(config) {
    config = config || {};
    var data = [
        [_('msimportexport.import.combo.products'),'products']
        ,[_('msimportexport.import.combo.links'),'links']
        ,[_('msimportexport.import.combo.categories'),'categories']
    ];
    if(config.is_pemain){
        data.push([_('msimportexport.import.combo.pemains'),'pemains']);
    }
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: data
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,hiddenName: 'import_type'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,preventRender: true
        ,forceSelection: true
        ,enableKeyEvents: true
    });
    Msie.combo.ImportType.superclass.constructor.call(this,config);
};
Ext.extend(Msie.combo.ImportType,MODx.combo.ComboBox);
Ext.reg('msie-combo-import-type',Msie.combo.ImportType);