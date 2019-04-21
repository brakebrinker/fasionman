Msie.combo.ExportCurrencyDefault = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data:[
                ['RUB','RUB'],
                ['EUR','EUR'],
                ['USD','USD'],
                ['UAH','UAH'],
                ['BYR','BYR'],
                ['KZT','KZT']
            ]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,hiddenName: 'ym_default_currency'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,preventRender: true
        ,forceSelection: true
        ,enableKeyEvents: true
    });
    Msie.combo.ExportCurrencyDefault.superclass.constructor.call(this,config);
};
Ext.extend(Msie.combo.ExportCurrencyDefault,MODx.combo.ComboBox);
Ext.reg('msie-combo-export-currency-default',Msie.combo.ExportCurrencyDefault);