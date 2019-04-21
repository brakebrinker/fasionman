<?php

class  msImportExportCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = 'MsieCron';
    public $languageTopics = array('msimportexport:default');

    public function beforeSet()
    {
        $this->setCheckbox('active');
        $this->setProperty('pid_key', md5('pid_' . uniqid(mt_rand(), true)));
        return parent::beforeSet();
    }
}

return 'msImportExportCreateProcessor';