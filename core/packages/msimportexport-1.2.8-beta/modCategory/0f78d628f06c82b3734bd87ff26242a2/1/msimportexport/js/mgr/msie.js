var Msie = function(config) {
    config = config || {};
    Msie.superclass.constructor.call(this,config);
};
Ext.extend(Msie,Ext.Component,{
    page:{},
    window:{},
    grid:{},
    tree:{},
    panel:{},
    combo:{},
    config: {},
    view: {},
    extra: {},
    connector_url: ''

});
Ext.reg('Msie',Msie);

Msie = new Msie();