<?php
require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';

class msImportExportGetListProcessor extends modObjectGetListProcessor {
    public $languageTopics = array('msimportexport:default');
    public $checkListPermission = true;
    /** @var Msie $msie */
    private $msie;
    public function initialize() {
        $this->msie = new Msie($this->modx);
        return parent::initialize();
    }
    public function iterate(array $data = array()) {
        $fields = array(
            $this->modx->getOption('msimportexport.key',null,'article')
            ,'link'
            //,'master'
            ,'slave'
        );
        $alias = array(
            'article' => $this->modx->lexicon('msimportexport.combo.field_article')
            ,'link' => $this->modx->lexicon('msimportexport.combo.field_link')
            ,'master' => $this->modx->lexicon('msimportexport.combo.field_master')
            ,'slave' => $this->modx->lexicon('msimportexport.combo.field_slave')
        );
        $list = array();
        $list[] = array(
            'name' => $this->modx->lexicon('msimportexport.combo.field_ignore'),
            'val' => -1
        );
        if(!empty($fields)) {
            foreach ($fields as $k => $v) {
                $list[] = array(
                    'name' => isset($alias[$v]) ? ($alias[$v] .' - ' . $v) : $v,
                    'val' => $v
                );
            }
        }
        return $list;
    }
    public function process() {
        $list = $this->iterate();
        $total = count($list);
        return $this->outputArray($list,$total);
    }

}
return 'msImportExportGetListProcessor';