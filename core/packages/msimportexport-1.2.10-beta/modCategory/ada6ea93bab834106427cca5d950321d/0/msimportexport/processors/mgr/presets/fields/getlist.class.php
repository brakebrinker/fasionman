<?php
class msImportExportGetListProcessor extends modObjectGetListProcessor {
    public $languageTopics = array('msimportexport:default');
    public $classKey = 'MsiePresetsFields';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $checkListPermission = true;

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $type = $this->getProperty('type');
        $act = $this->getProperty('act');
        if (!empty($type)) {
            $c->where(array('type' => $type));
        }
        if (!empty($act)) {
            $c->where(array('act'=>$act));
        }
        return $c;
    }
}
return 'msImportExportGetListProcessor';