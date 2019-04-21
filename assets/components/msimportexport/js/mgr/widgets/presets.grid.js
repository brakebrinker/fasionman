Msie.grid.presets = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'msie-grid-presets'
        ,url: Msie.config.connectorUrl
        ,baseParams: {
			action: 'mgr/presets/fields/getList'
			,type: config.type
			,act: config.act
		}
        ,fields: ['id','name']
        ,paging: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,save_action: 'mgr/presets/fields/updateFromGrid'
        ,autosave: true
		,listeners: {
			render : function(grid){
				this.store.on('update', this.onUpdate);
			}
		}
        ,columns: [{
				header: _('msimportexport.preset.header_id')
				,dataIndex: 'id'
				,sortable: true
				,width: 100
			},{
				header:_('msimportexport.preset.header_name')
				,dataIndex: 'name'
				,sortable: true
				,editor: {
					xtype: 'textfield'
				}
			}],tbar:[{
             text: _('msimportexport.preset.btn_create')
			,scope: this
            ,handler: {
					xtype: 'msie-window-preset-create'
					,blankValues: true
					,cls: 'primary-button'
					,type: config.type || 'products'
					,act: config.act || 1
					,listeners: {
						'success': {fn:this.onAdd,scope:this}
					}
				}
            }]
        ,getMenu: function() {
            return [{
                text: _('msimportexport.menu.duplicate')
                ,handler: this.duplicate
            },{
                text: _('msimportexport.menu.remove')
                ,handler: this.remove
            }];
        }
		,duplicate: function() {
			MODx.Ajax.request({
				url: this.config.url
				,params: {
					action: 'mgr/presets/fields/duplicate'
					,id: this.menu.record.id
				}
				,listeners: {
					'success': {fn:this.onAdd,scope:this}
				}
			});
        }
		,remove: function() {
            MODx.msg.confirm({
                title: _('msimportexport.title.win_remove')
                ,text: _('msimportexport.preset.confirm.remove')
                ,url: this.config.url
                ,params: {
                    action: 'mgr/presets/fields/remove'
                    ,id: this.menu.record.id
                }
                ,listeners: {
                    'success': {fn:this.onRemove,scope:this}
                }
            });
        }

    });
    Msie.grid.presets.superclass.constructor.call(this,config)
};
Ext.extend(Msie.grid.presets,MODx.grid.Grid,{
	onRemove:function(r){
		this.refresh();
		this.fireEvent('preset-remove',r.object.id);
	}
	,onAdd:function(r,d){
		this.refresh();
		this.fireEvent('preset-add');
	}
	,onUpdate:function(store, records, operation){
		if(operation == 'commit') {
			this.fireEvent('preset-update',records.id);
		}
	}
});
Ext.reg('msie-grid-presets',Msie.grid.presets);


Msie.window.CreatePreset = function(config) {
	config = config || {};
	var self = this;
	Ext.applyIf(config,{
		title: _('msimportexport.preset.title.win_create')
		,url: Msie.config.connectorUrl
		,autoHeight:true
		,modal: true
		,baseParams: {
			action: 'mgr/presets/fields/create'
			,type: config.type
			,act: config.act
		}
		,fields: [{
			xtype: 'textfield'
			,fieldLabel: _('msimportexport.preset.label_name')
			,name: 'name'
			,allowBlank:false
			,anchor: '100%'
		}]
	});
	Msie.window.CreatePreset.superclass.constructor.call(this,config);
};
Ext.extend(Msie.window.CreatePreset,MODx.Window);
Ext.reg('msie-window-preset-create',Msie.window.CreatePreset);