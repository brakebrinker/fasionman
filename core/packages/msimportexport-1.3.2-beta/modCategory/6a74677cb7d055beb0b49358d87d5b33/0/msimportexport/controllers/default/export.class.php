<?php
/**
 * Copyright 2015 by Prihod <prihod2004@gmail.com>
 *
 * @package msimportexport
 */
/**
 * Loads the build page.
 *
 * @package msimportexport
 * @subpackage controllers
 */

require_once dirname(dirname(dirname(__FILE__))) . '/index.class.php';


class ControllersMgrImportManagerController extends MsieMainController {
    public static function getDefaultController() {
        return 'export';
    }
}


class msImportExportExportManagerController extends MsieMainController {
    public function getPageTitle() { return $this->modx->lexicon('msimportexport.page_title_export'); }
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);

        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/export.ym_params.grid.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/export.currency_default.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/export.fields.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/export.type.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/export.currency_rate.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/export.format.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/presets.grid.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/alias.grid.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/presets.window.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/presets.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/category.tree.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/export.settings.panel.js');
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/widgets/export.panel.js');
        $this->addLastJavascript($this->msie->config['jsUrl'].'mgr/sections/export.js');
        $ymName  = $this->msie->modx->getOption('msimportexport.export.ym.name',null, '');
        $ymCompany = $this->modx->getOption('msimportexport.export.ym.company',null,'');
        $ymName = $ymName ? $ymName : $this->msie->modx->getOption('site_name',null,'');
        $ymCompany =$ymCompany ? $ymCompany : $ymName;

      //  $fields = array_diff(array_map('trim', explode(',', $this->modx->getOption('msimportexport.export.fields', null, ''))),array(''));
        $paramFields = $this->msie->strOption2Arr('msimportexport.export.ym.param_fields');

        $this->addHtml('<script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "msie-page-export"
                ,options: {
                    delimeter: "' . $this->modx->getOption('msimportexport.export.delimeter', null, ';') . '"
                    ,sub_delimeter: "' . $this->modx->getOption('msimportexport.export.sub_delimeter', null, '') . '"
                    ,where: "' . addslashes($this->modx->getOption('msimportexport.export.where', null, '')) . '"
                    ,debug: ' . $this->modx->getOption('msimportexport.export.debug', null, 0) . '
                    ,depth: ' . $this->modx->getOption('msimportexport.export.depth', null, 0) . '
                    ,limit: ' . $this->modx->getOption('msimportexport.export.limit', null, 0) . '
                    ,head: ' . $this->modx->getOption('msimportexport.export.head', null, 0) . '
                    ,convert_date: ' . $this->modx->getOption('msimportexport.export.convert_date', null, 0) . '
                    ,format_date: "' . addslashes($this->modx->getOption('msimportexport.export.format_date', null, '%m/%d/%Y %T')) . '"
                    ,excel_format_data: ' . $this->modx->getOption('msimportexport.export.excel_format_data', null, 0) . '
                    ,excel_insert_img: ' . $this->modx->getOption('msimportexport.export.excel_insert_img', null, 0) . '
                    ,excel_height_img: ' . $this->modx->getOption('msimportexport.export.excel_height_img', null, 50) . '
                    ,ym: {
                        name: "' . addslashes($ymName) . '"
                        ,company: "' . addslashes($ymCompany) .  '"
                        ,delivery_field: "' . $this->modx->getOption('msimportexport.export.ym.delivery_field',null,'') .  '"
                        ,in_stock_field: "' . $this->modx->getOption('msimportexport.export.ym.in_stock_field',null,'') .  '"
                        ,pickup_field: "' . $this->modx->getOption('msimportexport.export.ym.pickup_field',null,'') .  '"
                        ,default_currency: "' . $this->modx->getOption('msimportexport.export.ym.default_currency',null,'') .  '"
                        ,currency_rate: "' . $this->modx->getOption('msimportexport.export.ym.currency_rate',null,'') .  '"
                        ,currencies: "' . $this->modx->getOption('msimportexport.export.ym.currencies',null,'') .  '"
                        ,param_fields: ' . $this->modx->toJSON($paramFields) . '
                    }
                }
            });
        });
        // ]]>
        </script>');

    }
    public function getTemplateFile() { return $this->msie->config['templatesPath'].'mgr/export.tpl'; }
    public function getLanguageTopics() {
        return array('msimportexport:default');
    }
}