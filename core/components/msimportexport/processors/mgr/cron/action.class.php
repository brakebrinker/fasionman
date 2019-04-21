<?php

class msImportExportGetProcessor extends modObjectGetProcessor {
	public $languageTopics = array('msimportexport:default');
	public $classKey = 'MsieCron';

    /** {@inheritDoc} */
    public function cleanup() {
        switch ($this->getProperty('method')) {
            case 'run':
                if (!$this->object->isRun()) {
                    $this->object->set(MsieCron::STATUS_WAIT);
                    $this->object->save();
                }
                break;
            case 'abort':
                $this->object->abort();
                break;

        }
        $array = $this->object->toArray('', true);
        return $this->success('', $array);
    }

}
return 'msImportExportGetProcessor';