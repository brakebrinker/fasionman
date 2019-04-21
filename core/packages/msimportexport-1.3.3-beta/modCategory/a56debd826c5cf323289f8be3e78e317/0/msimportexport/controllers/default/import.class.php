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


class ControllersMgrImportManagerController extends MsieMainController
{
    public static function getDefaultController()
    {
        return 'import';
    }
}


class msImportExportImportManagerController extends MsieMainController
{
    public function getPageTitle()
    {
        return $this->modx->lexicon('msimportexport.page_title_import');
    }

    public function loadCustomCssJs()
    {
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/vendor/highstock/highcharts.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/vendor/highstock/modules/exporting.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/cron.status.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/cron.grid.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/cron.panel.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/chart.panel.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/fields.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/import.type.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/catalog.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/presets.grid.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/presets.window.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/presets.combo.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/resource.duplicate.grid.js');
        $this->addJavascript($this->msie->config['jsUrl'] . 'mgr/widgets/import.panel.js');
        $this->addLastJavascript($this->msie->config['jsUrl'] . 'mgr/sections/import.js');
        $this->addCss($this->msie->config['cssUrl'] . 'mgr/main.css');
        $isPemain = $this->modx->getOption('mspr_options', null, '') ? 1 : 0;
        $memoryLimit = filter_var(ini_get('memory_limit'), FILTER_SANITIZE_NUMBER_INT);

        $this->addHtml('<script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "msie-page-import"
                ,is_pemain: ' . $isPemain . '
                ,options: {
                    delimeter: "' . $this->modx->getOption('msimportexport.delimeter', null, ';') . '"
                    ,sub_delimeter: "' . $this->modx->getOption('msimportexport.import.sub_delimeter', null, '|') . '"
                    ,sub_delimeter2: "' . $this->modx->getOption('msimportexport.import.sub_delimeter2', null, '%') . '"
                    ,time_limit: "' . (int)$this->modx->getOption('msimportexport.time_limit', null, 600) . '"
                    ,memory_limit: "' . (int)$this->modx->getOption('msimportexport.memory_limit', null, $memoryLimit, true) . '"
                    ,step_limit: "' . (int)$this->modx->getOption('msimportexport.import.step_limit', null, 50) . '"
                    ,ignore_first_line: "' . (int)$this->modx->getOption('msimportexport.ignore_first_line', null, 0) . '"
                    ,template_category: "' . (int)$this->modx->getOption('msimportexport.import.template_category', null, 0) . '"
                    ,id_parent_new_product: "' . (int)$this->modx->getOption('msimportexport.import.id_parent_new_product', null, 0) . '"
                    ,key: "' . $this->modx->getOption('msimportexport.key', null, 'article') . '"
                    ,catalog: "' . $this->modx->getOption('msimportexport.import.root_catalog', null, '') . '"
                    ,use_only_root_catalog: "' . $this->modx->getOption('msimportexport.import.use_only_root_catalog', null, 0) . '"
                    ,update_uri: "' . $this->modx->getOption('msimportexport.update_uri', null, 0) . '"
                    ,debug: "' . (int)$this->modx->getOption('msimportexport.debug', null, 0) . '"
                    ,chartShow: "' . (int)$this->modx->getOption('msimportexport.import.report_chart', null, 1) . '"
                    ,utf8_encode: "' . (int)$this->modx->getOption('msimportexport.import.utf8_encode', null, 0) . '"
                    ,skip_empty_parent: "' . (int)$this->modx->getOption('msimportexport.skip_empty_parent', null, 1) . '"
                    ,create_parent: "' . (int)$this->modx->getOption('msimportexport.create_parent', null, 1) . '"
                    ,cron_log: "' . (int)$this->modx->getOption('msimportexport.import.cron_log', null, 0) . '"
                    ,cron_file_path: "' . $this->msie->config['cronUrl'] . '"
                }
            });
        });
        // ]]>
        </script>');

    }

    public function getTemplateFile()
    {
        return $this->msie->config['templatesPath'] . 'mgr/import.tpl';
    }

    public function getLanguageTopics()
    {
        return array('msimportexport:default');
    }
}