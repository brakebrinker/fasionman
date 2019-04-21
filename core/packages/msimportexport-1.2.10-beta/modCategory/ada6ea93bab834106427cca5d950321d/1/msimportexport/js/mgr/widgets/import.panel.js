Msie.panel.Import = function (config) {
    config = config || {};

    Ext.Ajax.timeout = 0;
    Ext.override(Ext.form.BasicForm, {timeout: Ext.Ajax.timeout / 1000});
    Ext.override(Ext.form.FormPanel, {timeout: Ext.Ajax.timeout / 1000});
    Ext.override(Ext.data.Connection, {timeout: Ext.Ajax.timeout});

    Ext.applyIf(config, {
        url: Msie.config.connectorUrl
        , baseParams: {}
        , border: false
        , id: 'msie-panel-import'
        , baseCls: 'modx-formpanel'
        , cls: 'container'
        , items: [{
            html: '<h2>' + _('msimportexport') + ': ' + _('msimportexport.page_title_import') + '</h2>'
            , border: false
            , cls: 'modx-page-header'
        }, {
            xtype: 'modx-tabs'
            , id: 'msie-import-tabs'
            , anchor: '100% 100%'
            , forceLayout: true
            , deferredRender: false
            , stateEvents: ['tabchange']
            , getState: function () {
                return {activeTab: this.items.indexOf(this.getActiveTab())};
            }
            , items: [
                this.getImportProducts(config)
                , this.getImportSettings(config)
            ]
        }, {
            xtype: 'panel'
            , id: 'msie-panel-report'
            , layout: 'form'
            , bodyCssClass: 'tab-panel-wrapper main-wrapper'
            , hidden: true
            , items: []
        }

        ]
        , listeners: {
            'setup': {fn: this.setup, scope: this}
            , 'beforeSubmit': {fn: this.beforeSubmit, scope: this}
            , 'success': {fn: this.success, scope: this}
        }
    });
    Msie.panel.Import.superclass.constructor.call(this, config);
};
Ext.extend(Msie.panel.Import, MODx.FormPanel, {
    windows: {}
    , steps: 0
    , offset: 0
    , fields: []
    , preset: []
    , reportData: {}
    , timeout: 0
    , ext: ''
    , totalFields: 0
    , memoryLimit: ''
    , phpversion: ''
    , timeStartImport: 0
    , timeStopImport: 0
    , chart: {
        time: []
        , create: []
        , update: []
        , error: []
    }
    , getImportSettings: function (config) {
        return {
            title: _('msimportexport.tab.import_settings')
            , id: 'msie-panel-settings'
            , layout: 'form'
            , labelAlign: 'top'
            , labelSeparator: ''
            , baseCls: 'modx-formpanel'
            , cls: 'container'
            , autoHeight: true
            , collapsible: false
            , animCollapse: false
            , hideMode: 'offsets'
            , items: [{
                xtype: 'hidden'
                , id: 'msie-chart-show'
                , value: config.options.chartShow
            }, {
                xtype: 'textfield'
                , id: 'msie-delimeter'
                , fieldLabel: _('setting_msimportexport.delimeter')
                , name: 'delimeter'
                , value: config.options.delimeter
                , anchor: '100%'
            }, {
                xtype: 'textfield'
                , id: 'msie-sub_delimeter'
                , fieldLabel: _('setting_msimportexport.import.sub_delimeter')
                , description: _('setting_msimportexport.import.sub_delimeter_desc')
                , name: 'sub_delimeter'
                , value: config.options.sub_delimeter
                , anchor: '100%'
            }, {
                xtype: 'textfield'
                , fieldLabel: _('setting_msimportexport.import.sub_delimeter2')
                , description: _('setting_msimportexport.import.sub_delimeter2_desc')
                , name: 'sub_delimeter2'
                , value: config.options.sub_delimeter2
                , anchor: '100%'
            }, {
                xtype: 'numberfield'
                , fieldLabel: _('setting_msimportexport.time_limit')
                , description: _('setting_msimportexport.time_limit_desc')
                , name: 'time_limit'
                , value: config.options.time_limit
                , anchor: '100%'
            }, {
                xtype: 'numberfield'
                , fieldLabel: _('setting_msimportexport.memory_limit')
                , description: _('setting_msimportexport.memory_limit_desc')
                , name: 'memory_limit'
                , value: config.options.memory_limit
                , anchor: '100%'
            }, {
                xtype: 'numberfield'
                , fieldLabel: _('setting_msimportexport.import.step_limit')
                , id: 'msie-step-limit'
                , description: _('setting_msimportexport.import.step_limit_desc')
                , name: 'step_limit'
                , value: config.options.step_limit
                , anchor: '100%'
            }, {
                xtype: 'textfield'
                , fieldLabel: _('setting_msimportexport.key')
                , description: _('setting_msimportexport.key_desc')
                , name: 'key'
                , value: config.options.key
                , anchor: '100%'
            }, {
                xtype: 'msie-combo-catalog'
                , fieldLabel: _('setting_msimportexport.import.root_catalog')
                , description: _('setting_msimportexport.import.root_catalog_desc')
                , name: 'catalog'
                , allowBlank: true
                , anchor: '100%'
                , value: config.options.catalog
            }, {
                xtype: 'modx-combo-template'
                , fieldLabel: _('setting_msimportexport.import.template_category')
                , description: _('setting_msimportexport.import.template_category_desc')
                , name: 'template_category'
                , hiddenName: 'template_category'
                , allowBlank: true
                , anchor: '100%'
                , value: config.options.template_category
            }, {
                xtype: 'combo-boolean'
                , fieldLabel: _('setting_msimportexport.import.use_only_root_catalog')
                , description: _('setting_msimportexport.import.use_only_root_catalog_desc')
                , name: 'use_only_root_catalog'
                , hiddenName: 'use_only_root_catalog'
                , allowBlank: true
                , anchor: '100%'
                , value: config.options.use_only_root_catalog
            }, {
                xtype: 'combo-boolean'
                , id: 'msie-skip-empty-parent'
                , fieldLabel: _('setting_msimportexport.skip_empty_parent')
                , description: _('setting_msimportexport.skip_empty_parent_desc')
                , name: 'skip_empty_parent'
                , hiddenName: 'skip_empty_parent'
                , allowBlank: true
                , anchor: '100%'
                , value: config.options.skip_empty_parent
            }, {
                xtype: 'combo-boolean'
                , id: 'msie-create-parent'
                , fieldLabel: _('setting_msimportexport.create_parent')
                , description: _('setting_msimportexport.create_parent_desc')
                , name: 'create_parent'
                , hiddenName: 'create_parent'
                , allowBlank: true
                , anchor: '100%'
                , value: config.options.create_parent
            }, {
                xtype: 'textfield'
                , fieldLabel: _('setting_msimportexport.import.id_parent_new_product')
                , description: _('setting_msimportexport.import.id_parent_new_product_desc')
                , name: 'id_parent_new_product'
                , value: config.options.id_parent_new_product
                , anchor: '100%'
            }, {
                xtype: 'combo-boolean'
                , id: 'msie-ignore-first-line'
                , fieldLabel: _('setting_msimportexport.ignore_first_line')
                , description: _('setting_msimportexport.ignore_first_line_desc')
                , name: 'ignore_first_line'
                , hiddenName: 'ignore_first_line'
                , allowBlank: true
                , anchor: '100%'
                , value: config.options.ignore_first_line
            }, /* {
             xtype: 'combo-boolean'
             , fieldLabel: _('setting_msimportexport.update_uri')
             , description: _('setting_msimportexport.update_uri_desc')
             , name: 'update_uri'
             , hiddenName: 'update_uri'
             , allowBlank: true
             , anchor: '100%'
             , value: config.options.update_uri
             },*/{
                xtype: 'combo-boolean'
                , fieldLabel: _('setting_msimportexport.debug')
                , description: _('setting_msimportexport.debug_desc')
                , name: 'debug'
                , hiddenName: 'debug'
                , allowBlank: true
                , anchor: '100%'
                , value: config.options.debug
            }, {
                xtype: 'combo-boolean'
                , fieldLabel: _('setting_msimportexport.import.utf8_encode')
                , description: _('setting_msimportexport.import.utf8_encode_desc')
                , name: 'utf8_encode'
                , hiddenName: 'utf8_encode'
                , allowBlank: true
                , anchor: '100%'
                , value: config.options.utf8_encode
            }, {
                xtype: 'textarea'
                , fieldLabel: _('setting_msimportexport.post_action')
                , description: _('setting_msimportexport.post_action_desc')
                , name: 'post_action'
                , allowBlank: true
                , anchor: '100%'
                , value: config.options.post_action
            }, {
                title: _('msimportexport.import.cron')
                , xtype: 'fieldset'
                , cls: 'x-fieldset-checkbox-toggle'
                , style: 'padding-top: 5px'
                , checkboxToggle: true
                , collapsed: Ext.state.Manager.get('msie_import_settings_cron') != true ? false : true
                , forceLayout: true
                , listeners: {
                    'expand': {
                        fn: function (p) {
                            Ext.state.Manager.set('msie_import_settings_cron', false);
                        }, scope: this
                    }
                    , 'collapse': {
                        fn: function (p) {
                            Ext.state.Manager.set('msie_import_settings_cron', true);
                        }, scope: this
                    }
                }
                , items: [{
                    layout: 'column'
                    , border: false
                    , fieldLabel: _('setting_msimportexport.import.cron_file_path')
                    , description: _('setting_msimportexport.import.cron_file_path_desc')
                    , items: [{
                        xtype: 'textfield'
                        , id: 'msie-cron-file-path'
                        , name: 'cron_file_path'
                        , value: config.options.cron_file_path
                        , msgTarget: 'under'
                        , allowBlank: true
                        , columnWidth: .7
                    }, {
                        xtype: 'button'
                        , text: _('msimportexport.btn_cron_run')
                        , scope: this
                        , cls: 'primary-button'
                        , handler: this.cronRun
                    }, {
                        xtype: 'button'
                        , text: _('msimportexport.btn_show_cron_link')
                        , scope: this
                        , handler: this.showCronLink
                    }]
                }, {
                    xtype: 'combo-boolean'
                    , fieldLabel: _('setting_msimportexport.import.cron_log')
                    , description: _('setting_msimportexport.import.cron_log_desc')
                    , name: 'cron_log'
                    , hiddenName: 'cron_log'
                    , allowBlank: true
                    , anchor: '100%'
                    , value: config.options.cron_log
                }]
            }]
        }
    }
    , getImportProducts: function (config) {
        var self = this;
        return {
            title: _('msimportexport.tab.import')
            , layout: 'form'
            , labelAlign: 'top'
            , labelSeparator: ''
            , baseCls: 'modx-formpanel'
            , cls: 'container'
            , autoHeight: true
            , collapsible: false
            , animCollapse: false
            , hideMode: 'offsets'
            , items: [{
                xtype: 'msie-combo-import-type'
                ,
                fieldLabel: _('msimportexport.import.label.type_import')
                ,
                name: 'import_type'
                ,
                id: 'msie-import-type'
                ,
                anchor: '72.5%'
                ,
                is_pemain: config.is_pemain
                ,
                value: Ext.state.Manager.get('msie_import-type') ? Ext.state.Manager.get('msie_import-type') : 'products'
                ,
                listeners: {
                    select: function (ele, rec, idx) {
                        self.hidePanelFields();
                        self.setReport();
                        self.reloadPresets(ele.value);
                        Ext.getCmp('msie-import-filename').setValue('');
                        Ext.state.Manager.set('msie_import-type', ele.value);
                    }
                }
            }, {
                layout: 'column'
                , border: false
                , fieldLabel: _('msimportexport.import_filename')
                , items: [{
                    xtype: 'textfield'
                    , name: 'filename'
                    , id: 'msie-import-filename'
                    , readOnly: true
                    , allowBlank: true
                    , msgTarget: 'under'
                    , columnWidth: .7
                }, {
                    xtype: 'button'
                    , text: _('msimportexport.import_upload')
                    , scope: this
                    , handler: this.uploadFile
                }, {
                    xtype: 'button'
                    , text: _('msimportexport.btn_import')
                    , cls: 'primary-button'
                    , scope: this
                    , handler: this.import
                }]
            }, {
                xtype: 'panel'
                , id: 'msie-setting-fields'
                , layout: 'form'
                , bodyStyle: 'padding-top: 15px'
                , hidden: true
                , items: [{
                    html: '<h3>' + _('msimportexport.setting.fields') + '</h3>'
                    , border: false
                }, {
                    layout: 'column'
                    , border: false
                    , labelAlign: 'top'
                    , fieldLabel: _('msimportexport.preset_fields')
                    , items: [{
                        xtype: 'msie-combo-presets'
                        ,
                        id: 'msie-combo-presets'
                        ,
                        name: 'preset'
                        ,
                        columnWidth: .4
                        ,
                        allowBlank: true
                        ,
                        type: Ext.getCmp('msie-import-type') ? Ext.getCmp('msie-import-type').getValue() : (Ext.state.Manager.get('msie_import-type') ? Ext.state.Manager.get('msie_import-type') : 'products')
                        ,
                        listeners: {
                            select: {fn: this.changePreset, scope: this}
                        }
                    }, {
                        xtype: 'button'
                        , text: '<i class="icon icon-cog"></i> '
                        , scope: this
                        , handler: this.presetsList
                    }]
                }, {
                    xtype: 'panel'
                    , bodyStyle: 'padding-top: 15px'
                    , layout: 'form'
                    , id: 'msie-panel-fields'
                    , items: []
                }]
            }]
        };
    }
    , changePreset: function (ele, rec, idx) {
        if (this.mask) {
            this.mask.show();
        }
        MODx.Ajax.request({
            url: Msie.config.connectorUrl
            , params: {
                action: 'mgr/presets/fields/get'
                , id: ele.value
            }
            , listeners: {
                'success': {
                    fn: function (r) {
                        this.mask.hide();
                        this.preset = r.object.fields ? r.object.fields : [];
                        this.buildFieldsList();
                    }, scope: this
                }
                , 'failure': {
                    fn: function (r) {
                        this.mask.hide();
                    }, scope: this
                }
            }
        });
    }
    , hidePanelFields: function () {
        var panel = Ext.getCmp('msie-setting-fields');
        panel.hide();
        this.fields = [];
    }
    , showPanelFields: function () {
        var panel = Ext.getCmp('msie-setting-fields');
        if (Ext.getCmp('msie-import-type').getValue() != 'categories') {
            panel.show();
        }
    }

    , buildFieldsList: function () {
        var type = Ext.getCmp('msie-import-type').getValue();
        if (this.fields) {
            var panel = Ext.getCmp('msie-panel-fields');
            if (panel) {
                panel.removeAll();
                for (var i = 0; i < this.fields.length; i++) {
                    var col = {
                        layout: 'column'
                        , border: false
                        , labelAlign: 'top'
                        , fieldLabel: _('msimportexport.col_num') + (i + 1)
                        , items: [{
                            xtype: 'textfield'
                            , readOnly: true
                            , columnWidth: .7
                            , value: this.fields[i]
                        }, {
                            xtype: 'msie-combo-fields'
                            , value: this.preset[i] ? this.preset[i] : -1
                            , type: type
                            , columnWidth: .3
                        }]
                    };
                    panel.add(col);
                    panel.doLayout();
                }
            }
        }
    }
    , reloadPresets: function (type) {
        var presets = Ext.getCmp('msie-combo-presets');
        if (type) presets.baseParams.type = type
        presets.reload();
        presets.setValue('');
        this.preset = [];
    }
    , resetFieldsList: function (type) {
        this.reloadPresets(type);
        this.buildFieldsList();
    }
    , resetChart: function () {
        this.chart = {
            time: []
            , create: []
            , update: []
            , error: []
        };
    }
    , setReport: function (data) {
        var panel = Ext.getCmp('msie-panel-report')
            , diffTimeImport = this.timeStopImport - this.timeStartImport
            , d = new Date(diffTimeImport)
            , totalTimeImport = Highcharts.dateFormat('%H:%M:%S.', diffTimeImport) + d.getMilliseconds()
            , chartShow = parseInt(Ext.getCmp('msie-chart-show').getValue());
        panel.removeAll();
        panel.hide();
        if (data) {
            var items = [{
                html: '<h2>' + _('msimportexport.result.report') + '</h2>'
                , border: false
            }];
            for (var key in data) {
                if (key === 'seek') {
                    continue;
                }
                if (key !== 'uri') {
                    items.push({
                        xtype: 'label'
                        ,
                        style: {display: 'block'}
                        ,
                        html: _('msimportexport.result.' + key) + (key == 'errors' ? ('<a href="' + Msie.config.managerUrl + '?a=system/event" target="_blank">' + data[key] + '</a>') : ('<span> ' + data[key] + '</span>'))
                    });
                }
            }
            if (data.uri) {
                items.push({
                    html: '<h2>' + _('msimportexport.result.report.uri') + '</h2>'
                    , style: {'padding-top': '20px'}
                    , border: false
                });
                for (var key in data.uri) {
                    items.push({
                        xtype: 'label'
                        ,
                        style: {display: 'block'}
                        ,
                        html: _('msimportexport.result.' + key) + (key == 'errors' ? ('<a href="' + Msie.config.managerUrl + '?a=system/event" target="_blank">' + data.uri[key] + '</a>') : ('<span> ' + data.uri[key] + '</span>'))
                    });
                }
                if (data.uri.failed) {
                    items.push({
                        xtype: 'msie-resource-duplicate-grid'
                        , style: {'padding-top': '25px'}
                    });
                }
            }
            items.push({
                xtype: 'label'
                , style: {display: 'block'}
                , html: _('msimportexport.result.time') + '<span> ' + totalTimeImport + '</span>'
            });
            if(chartShow) {
                items.push({
                    xtype: 'msie-panel-chart'
                    , dataset: {
                        data: this.chart
                        , sys: {
                            timeout: this.timeout
                            , memoryLimit: this.memoryLimit
                            , chunk: Ext.getCmp('msie-step-limit').getValue()
                            , phpversion: this.phpversion
                            , rows: this.reportData.rows
                            , totalFields: this.totalFields
                            , ext: this.ext
                        }
                    }
                });
            }
            panel.add(items);
            panel.doLayout();
            panel.show();

        }

    }
    , uploadFile: function (btn, e) {
        var data = this.getForm().getValues();
        var self = this;
        this.setReport();
        this.windows.upload = MODx.load({
            xtype: 'msie-window-upload'
            , listeners: {
                'success': function (o) {
                    if (o.a.result.object) {
                        var panel = Ext.getCmp('msie-panel-import');
                        Ext.getCmp('msie-import-filename').setValue(o.a.result.object.filename);

                        self.fields = o.a.result.object.fields;
                        self.resetChart();
                        self.showPanelFields();
                        self.buildFieldsList();
                        self.ext = Ext.util.Format.uppercase(o.a.result.object.filename.split('.').pop());
                        self.steps = o.a.result.object.steps;
                        self.offset = 0;
                        self.reportData = {};
                        self.totalFields = self.fields.length;
                        self.timeout = o.a.result.object.timeout;
                        self.memoryLimit = o.a.result.object.memory_limit;
                        self.phpversion = o.a.result.object.phpversion;
                    }
                    this.close();
                }
            }
        });
        this.windows.upload.setValues(data);
        this.windows.upload.show(e.target);
    }
    , cronRun: function (btn, e) {
        if (this.mask) {
            this.mask.show();
        }
        var params = this.getForm().getValues();
        params.action = 'mgr/import/cronRun';
        MODx.Ajax.request({
            url: Msie.config.connectorUrl
            , params: params
            , listeners: {
                'success': {
                    fn: function (e) {
                        this.mask.hide();
                        Ext.MessageBox.alert(_('success'), e.message);
                    }, scope: this
                }
                , 'failure': {
                    fn: function (r) {
                        this.mask.hide();
                        this.getForm().markInvalid(r.data);
                    }, scope: this
                }
            }
        });

    }
    , mergeImportData: function (slave, master) {
        var tmp = {};
        master = master || {};
        for (var key in slave) {
            if (Ext.isNumber(slave[key])) {
                if (!master[key]) {
                    master[key] = 0;
                }
                tmp[key] = master[key] + slave[key];
            } else {
                if (!master[key]) {
                    master[key] = {};
                }
                tmp[key] = this.mergeImportData(slave[key], master[key]);
            }
        }
        return tmp;

    }
    , _import: function (params) {
        this.offset++;
        var timeStart = new Date().getTime();
        MODx.Ajax.request({
            url: Msie.config.connectorUrl
            , params: params
            , listeners: {
                'success': {
                    fn: function (e) {
                        if (e.object) {
                            //Ext.MessageBox.updateProgress((this.offset/this.steps) , this.offset+'/'+this.steps);
                            Ext.MessageBox.updateProgress((this.offset / this.steps), this.offset);
                            // if(this.offset != this.steps && params.debug != 1 ) {

                            this.chart.time.push(new Date().getTime() - timeStart);
                            this.chart.create.push(e.object.create);
                            this.chart.update.push(e.object.update);
                            this.chart.error.push(e.object.errors);

                            if (e.object.seek > 0 && params.debug != 1) {
                                params.seek = e.object.seek;
                                params.offset = this.offset;
                                this.reportData = this.mergeImportData(e.object, this.reportData);
                                this._import(params);
                            } else {
                                Ext.Msg.hide();
                                this.timeStopImport = new Date().getTime();
                                this.reportData = this.mergeImportData(e.object, this.reportData);
                                this.hidePanelFields();
                                this.setReport(this.reportData);
                            }
                        }
                    }, scope: this
                }
                , 'failure': {
                    fn: function (r) {
                        this.getForm().markInvalid(r.data);
                    }, scope: this
                }
            }
        });

    }
    , import: function (btn, e) {
        var filename = Ext.getCmp('msie-import-filename');
        if (filename.getValue()) {
            var filename = Ext.getCmp('msie-import-filename');
            var params = this.getForm().getValues();
            params.action = 'mgr/import/import';
            params.steps = this.steps;
            params.seek = 0;
            Ext.Msg.show({
                title: _('please_wait')
                , msg: _('msimportexport.mess.import')
                , width: 340
                , progress: true
                , closable: false
            });
            Ext.getCmp('msie-import-filename').setValue('');
            this.timeStartImport = new Date().getTime();
            this._import(params);
        } else {
            this.getForm().markInvalid({'filename': _('msimportexport.err.err_ns')});
        }

    }
    , showCronLink: function (btn, e) {
        var url = Msie.config.importUrl + '?token=' + Msie.config.token + '&path=' + Ext.getCmp('msie-cron-file-path').getValue() + '&type=' + Ext.getCmp('msie-import-type').getValue();
        url += '&preset=' + Ext.getCmp('msie-combo-presets').getValue();
        this.windows.cronUrl = MODx.load({
            xtype: 'msie-window-cron-url'
            , cron_url: url
        });
        this.windows.cronUrl.show(e.target);
    }
    , presetsList: function (btn, e) {
        var type = Ext.getCmp('msie-import-type');
        this.windows.presetsList = MODx.load({
            xtype: 'msie-window-presets'
            , title: _('msimportexport.preset.title.win_list') + '"' + type.getRawValue() + '"'
            , type: type.getValue()
            , act: 1
            , listeners: {
                'preset-change': {
                    fn: function () {
                        this.resetFieldsList();
                    }, scope: this
                }
            }
        });
        this.windows.presetsList.show(e.target);
    }
    , beforeSubmit: function (o) {
        this.setReport();
        Ext.apply(o.form.baseParams, {});
    }
    , success: function (o) {
        self.timeout = o.result.object.timeout;
        self.memoryLimit = o.result.object.memory_limit;
        Ext.getCmp('msie-btn-save').setDisabled(false);
    }
    , setup: function () {
        if (!this.mask) {
            this.mask = new Ext.LoadMask(this.getEl());
        }
    }
});
Ext.reg('msie-panel-import', Msie.panel.Import);

Msie.window.uploadFile = function (config) {
    config = config || {};
    this.ident = config.ident || 'gupit' + Ext.id();
    var type = Ext.getCmp('msie-import-type');
    Ext.applyIf(config, {
        title: _('msimportexport.win.upload_title')
        , id: this.ident
        , autoHeight: true
        , saveBtnText: _('msimportexport.import_upload')
        , url: Msie.config.connectorUrl
        , baseParams: {
            action: 'mgr/import/upload'
            , delimeter: Ext.getCmp('msie-delimeter').getValue()
            , ignore_first_line: Ext.getCmp('msie-ignore-first-line').getValue()
            , type: type.getValue()
        }
        , fileUpload: true
        , fields: [{
            layout: 'column'
            , border: false
            , defaults: {
                layout: 'form'
                , labelAlign: 'top'
                , border: false
                , cls: (MODx.config.connector_url) ? '' : 'main-wrapper' // check for 2.3
                , labelSeparator: ''
            }
            , items: [{
                columnWidth: 1
                , items: [{
                    xtype: (MODx.config.connector_url) ? 'fileuploadfield' : 'textfield' // check for 2.3
                    , inputType: (MODx.config.connector_url) ? 'text' : 'file' // check for 2.3
                    , fieldLabel: _('msimportexport.file')
                    , name: 'file'
                    , id: this.ident + '-file'
                    , anchor: '100%'
                }]
            }]
        }]
    });
    Msie.window.uploadFile.superclass.constructor.call(this, config);
};
Ext.extend(Msie.window.uploadFile, MODx.Window);
Ext.reg('msie-window-upload', Msie.window.uploadFile);

Msie.window.CronUrl = function (config) {
    config = config || {};
    this.ident = config.ident || 'gupit' + Ext.id();
    Ext.applyIf(config, {
        title: 'Cron url'
        , id: this.ident
        , modal: true
        , width: 650
        , autoHeight: true
        , buttons: [{
            text: config.cancelBtnText || _('cancel')
            , scope: this
            , handler: function () {
                config.closeAction !== 'close' ? this.hide() : this.close();
            }
        }]
        , fields: [{
            layout: 'column'
            , border: false
            , defaults: {
                layout: 'form'
                , labelAlign: 'top'
                , border: false
                , cls: (MODx.config.connector_url) ? '' : 'main-wrapper' // check for 2.3
                , labelSeparator: ''
            }
            , items: [{
                columnWidth: 1
                , items: [{
                    xtype: 'textarea'
                    , allowBlank: true
                    , anchor: '100%'
                    , value: config.cron_url
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    , text: _('msimportexport.import.cron_url_desc')
                    , cls: 'desc-under'
                }]
            }]
        }]
    });
    Msie.window.CronUrl.superclass.constructor.call(this, config);
};
Ext.extend(Msie.window.CronUrl, MODx.Window);
Ext.reg('msie-window-cron-url', Msie.window.CronUrl);
