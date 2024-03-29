Msie.combo.Presets = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        displayField: 'name'
        ,hiddenName: 'preset'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,editable: true
        ,url:  Msie.config.connectorUrl
        ,listeners: {
            render : function(){
                var self = this;
                this.store.on('load', function(){
                    self.setVal(self.val);
                });
            }
        }
        ,baseParams:{
            action: 'mgr/presets/fields/getlist'
            ,type: config.type || 'products'
            ,act: config.act || 1
        }
    });
    Msie.combo.Presets.superclass.constructor.call(this,config);
};
Ext.extend(Msie.combo.Presets,MODx.combo.ComboBox,{
    val:null
    ,reload:function(value) {
        this.val = value;
        this.getStore().reload();
    }
    ,setVal: function(value) {
        if(value) {
            var store = this.getStore();
            var valueField = this.valueField;
            var displayField = this.displayField;
            var recordNumber = store.findExact(valueField, value, 0);
            if (recordNumber == -1)
                return -1;
            var displayValue = store.getAt(recordNumber).data[displayField];
            this.setValue(value);
            this.setRawValue(displayValue);
            this.selectedIndex = recordNumber;
            return recordNumber;
        }
    }
});
Ext.reg('msie-combo-presets',Msie.combo.Presets);