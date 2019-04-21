<?php
class msImportExportGetListProcessor extends modObjectGetListProcessor {
    public $languageTopics = array('msimportexport:default');
    public $classKey = 'MsieHeadAlias';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $checkListPermission = true;
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array('key:LIKE' => '%'.$query.'%', 'OR:value:LIKE' => '%'.$query.'%'));
        }
        return $c;
    }
}
return 'msImportExportGetListProcessor';