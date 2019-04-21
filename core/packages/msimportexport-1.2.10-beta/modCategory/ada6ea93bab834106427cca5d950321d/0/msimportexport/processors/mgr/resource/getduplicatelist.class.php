<?php
require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';

class msImportExportGetDuplicateListProcessor extends modObjectGetListProcessor {
    public $languageTopics = array('msimportexport:default');
    public $checkListPermission = true;
    /** @var Msie $msie */
    private $msie;
    public function initialize() {
        $this->msie = new Msie($this->modx);
        return parent::initialize();
    }
    public function iterate() {
        return $this->msie->getDuplicateResources();
    }
    public function process() {
        $list = $this->iterate();
        $total = count($list);
        return $this->outputArray($list,$total);
    }
}
return 'msImportExportGetDuplicateListProcessor';