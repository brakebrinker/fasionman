<?php

class msImportExportGetListProcessor extends modObjectGetListProcessor
{
    public $languageTopics = array('msimportexport:default');
    public $classKey = 'MsieCron';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $checkListPermission = true;

    public function beforeQuery()
    {
        $this->setProperty('limit', 0);
        return parent::beforeQuery();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $type = $this->getProperty('type');
        if (!empty($type)) {
            $c->where(array('type' => $type));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        return $this->prepareArray($object->toArray());
    }

    public function prepareArray(array $row)
    {
        $icon = 'x-menu-item-icon icon icon';
        $row['actions'] = array();
        $row['actions'][] = array(
            'cls' => '',
            'icon' => "$icon-edit",
            'title' => $this->modx->lexicon('msimportexport.menu.edit'),
            'action' => 'edit',
            'button' => false,
            'menu' => true,
        );

        if ($row['status'] == MsieCron::STATUS_WAIT) {
            $row['actions'][] = array(
                'cls' => '',
                'icon' => "$icon-play action-green",
                'title' => $this->modx->lexicon('msimportexport.menu.run'),
                'action' => 'run',
                'button' => false,
                'menu' => true,
            );
        }

        if ($row['status'] == MsieCron::STATUS_RUN) {
            $row['actions'][] = array(
                'cls' => '',
                'icon' => "$icon-minus-circle action-yellow",
                'title' => $this->modx->lexicon('msimportexport.menu.abort'),
                'action' => 'abort',
                'button' => false,
                'menu' => true,
            );
        } else {
            $row['actions'][] = array(
                'cls' => '',
                'icon' => "$icon-trash action-red",
                'title' => $this->modx->lexicon('msimportexport.menu.remove'),
                'action' => 'remove',
                'button' => false,
                'menu' => true,
            );
        }

        return $row;

    }
}

return 'msImportExportGetListProcessor';