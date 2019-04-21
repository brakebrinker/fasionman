Msie.combo.Fields = function(config) {
    config = config || {};
    var action;
    switch (config.type) {
        case 'products':
        case 'pemains':
            action = 'mgr/fields/getlist';
          break;
        case 'links':
            action = 'mgr/link/getlist';
            break;
        default:
    };

    Ext.applyIf(config,{
        displayField: 'name'
        ,hiddenName: 'fields[]'
        ,valueField: 'val'
        ,fields: ['name','val']
        ,editable: true
        ,url:  Msie.config.connectorUrl
        ,baseParams:{
            action: action
            ,type: config.type
        }
    });
    Msie.combo.Fields.superclass.constructor.call(this,config);
};
Ext.extend(Msie.combo.Fields,MODx.combo.ComboBox);
Ext.reg('msie-combo-fields',Msie.combo.Fields);