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
    connector_url: '',
    util:{
        getMenu:function (actions, grid, selected) {
            var menu = [];
            var cls, icon, title, action = '';

            var has_delete = false;
            for (var i in actions) {
                if (!actions.hasOwnProperty(i)) {
                    continue;
                }

                var a = actions[i];
                if (!a['menu']) {
                    if (a == '-') {
                        menu.push('-');
                    }
                    continue;
                }
                else if (menu.length > 0 && !has_delete && (/^remove/i.test(a['action']) || /^delete/i.test(a['action']))) {
                    menu.push('-');
                    has_delete = true;
                }

                /*if (selected.length > 1) {
                    if (!a['multiple']) {
                        continue;
                    }
                    else if (typeof(a['multiple']) == 'string') {
                        a['title'] = a['multiple'];
                    }
                }*/

                cls = a['cls'] ? a['cls'] : '';
                icon = a['icon'] ? a['icon'] : '';
                title = a['title'] ? a['title'] : a['title'];
                action = a['action'] ? grid[a['action']] : '';

                menu.push({
                    handler: action,
                    text: String.format(
                        '<span class="{0}"><i class="x-menu-item-icon {1}"></i>{2}</span>',
                        cls, icon, title
                    ),
                    scope: grid,
                });
            }

            return menu;
        }
    }

});
Ext.reg('Msie',Msie);

Ext.Ajax.timeout = 0;
Ext.override(Ext.form.BasicForm, {timeout: Ext.Ajax.timeout / 1000});
Ext.override(Ext.form.FormPanel, {timeout: Ext.Ajax.timeout / 1000});
Ext.override(Ext.data.Connection, {timeout: Ext.Ajax.timeout});

Ext.override(Ext.Window, {
    onShow: function() {
        // skip MODx.msg windows, the animations do not work with them as they are always the same element!
        if (!this.el.hasClass('x-window-dlg')) {
            // first set the class that scales the window down a bit
            // this has to be done after the full window is positioned correctly by extjs
            this.addClass('anim-ready');
            // let the scale transformation to 0.7 finish before animating in
            var win = this; // we need a reference to this for setTimeout
            setTimeout(function() {
                if (win.mask !== undefined) {
                    // respect that the mask is not always the same object
                    if (win.mask instanceof Ext.Element) {
                        win.mask.addClass('fade-in');
                    } else {
                        win.mask.el.addClass('fade-in');
                    }
                }
                win.el.addClass('zoom-in');
            }, 250);
        } else {
            // we need to handle MODx.msg windows (Ext.Msg singletons, e.g. always the same element, no multiple instances) differently
            if(this.mask) {
                this.mask.addClass('fade-in');
                this.el.applyStyles({'opacity': 1});
            }
        }
    }
    ,onHide: function() {
        // for some unknown (to me) reason, onHide() get's called when a window is initialized, e.g. before onShow()
        // so we need to prevent the following routine be applied prematurely
        if (this.el.hasClass('zoom-in')) {
            this.el.removeClass('zoom-in');
            if (this.mask !== undefined) {
                // respect that the mask is not always the same object
                if (this.mask instanceof Ext.Element) {
                    this.mask.removeClass('fade-in');
                } else {
                    this.mask.el.removeClass('fade-in');
                }
            }
            this.addClass('zoom-out');
            // let the CSS animation finish before hiding the window
            var win = this; // we need a reference to this for setTimeout
            setTimeout(function() {
                // we have an unsolved problem with windows that are destroyed on hide
                // the zoom-out animation cannot be applied for such windows, as they
                // get destroyed too early, if someone knows a solution, please tell =)
                if (!win.isDestroyed) {
                    win.el.hide();
                    // and remove the CSS3 animation classes
                    win.el.removeClass('zoom-out');
                    win.el.removeClass('anim-ready');
                }
            }, 250);
        } else if (this.el.hasClass('x-window-dlg')) {
            // we need to handle MODx.msg windows (Ext.Msg singletons, e.g. always the same element, no multiple instances) differently
            this.el.applyStyles({'opacity': 0});

            if (this.mask !== undefined) {
                // respect that the mask is not always the same object
                if (this.mask instanceof Ext.Element) {
                    this.mask.removeClass('fade-in');
                } else {
                    if(this.mask.el) {
                        this.mask.el.removeClass('fade-in');
                    }
                }
            }
        }
    }
});

Msie = new Msie();