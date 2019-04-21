<?php
class msImportExportUpdateProcessor extends modObjectUpdateProcessor {
    public $languageTopics = array('msimportexport:default');
    public $classKey = 'MsiePresetsFields';

    public function beforeSet() {
        /*$enable =  $this->getProperty('enable') == true ? 1 : 0;
        $this->setProperty('enable',$enable);*/
        return parent::beforeSave();
    }

}
return 'msImportExportUpdateProcessor';