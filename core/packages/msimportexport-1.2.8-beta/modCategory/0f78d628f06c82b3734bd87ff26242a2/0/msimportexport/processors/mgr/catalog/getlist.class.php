<?php
class msImportExportGetListProcessor extends modObjectGetListProcessor {
    public $languageTopics = array('msimportexport:default');
    public $checkListPermission = true;
    public $classKey = 'msCategory';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        //$c->leftJoin('modResource', 'Parent', array('msCategory.parent = Parent.id'));
        $c->where(array(
            'class_key' => 'msCategory',
            'parent'=> 0
        ));

        return $c;
    }

}
return 'msImportExportGetListProcessor';