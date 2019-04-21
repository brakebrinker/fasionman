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
        $query = $this->getProperty('query','');
        if(empty($query)) {
            $option = array();
            $exclude =  array_map('trim', explode(',', $this->modx->getOption('msimportexport.import.exclude_fields','')));
            $alias = array(
                'pagetitle' => $this->modx->lexicon('msimportexport.combo.field_pagetitle')
                ,'description' => $this->modx->lexicon('msimportexport.combo.field_description')
                , 'longtitle' => $this->modx->lexicon('msimportexport.combo.field_longtitle')
                , 'introtext' => $this->modx->lexicon('msimportexport.combo.field_introtext')
                , 'content' => $this->modx->lexicon('msimportexport.combo.field_content')
                , 'price' => $this->modx->lexicon('msimportexport.combo.field_price')
                , 'old_price' => $this->modx->lexicon('msimportexport.combo.field_old_price')
                , 'weight' => $this->modx->lexicon('msimportexport.combo.field_weight')
                , 'article' => $this->modx->lexicon('msimportexport.combo.field_article')
                , 'alias' => $this->modx->lexicon('msimportexport.combo.field_alias')
                , 'publishedon' => $this->modx->lexicon('msimportexport.combo.field_publishedon')
                , 'pub_date' => $this->modx->lexicon('msimportexport.combo.field_pub_date')
                , 'unpub_date' => $this->modx->lexicon('msimportexport.combo.field_unpub_date')
                , 'color' => $this->modx->lexicon('msimportexport.combo.field_color')
                , 'size' => $this->modx->lexicon('msimportexport.combo.field_size')
                , 'vendor' => $this->modx->lexicon('msimportexport.combo.field_vendor')
                , 'new' => $this->modx->lexicon('msimportexport.combo.field_new')
                , 'popular' => $this->modx->lexicon('msimportexport.combo.field_popular')
                , 'favorite' => $this->modx->lexicon('msimportexport.combo.field_favorite')
                , 'tags' => $this->modx->lexicon('msimportexport.combo.field_tags')
                , 'made_in' => $this->modx->lexicon('msimportexport.combo.field_made_in')
                , 'parent' => $this->modx->lexicon('msimportexport.combo.field_parent')
                , 'categories' => $this->modx->lexicon('msimportexport.combo.field_сategories')
                , 'remain' => $this->modx->lexicon('msimportexport.combo.field_remain')
                , 'ignore' => $this->modx->lexicon('msimportexport.combo.field_ignore')
                , 'gallery' => $this->modx->lexicon('msimportexport.combo.field_gallery')
                , 'searchable' => $this->modx->lexicon('msimportexport.combo.field_searchable')
                , 'deleted' => $this->modx->lexicon('msimportexport.combo.field_deleted')
                , 'pub_date' => $this->modx->lexicon('msimportexport.combo.field_pub_date')
                , 'unpub_date' => $this->modx->lexicon('msimportexport.combo.field_unpub_date')
                , 'template' => $this->modx->lexicon('msimportexport.combo.field_template')
                , 'published' => $this->modx->lexicon('msimportexport.combo.field_published')
            );
            if ($this->getProperty('type',false) == 'pemains') {
                $fields = array_map('trim', explode(',', $this->modx->getOption('mspr_options',null,'')));
                $fields[] = $this->modx->getOption('msimportexport.key',null,'article');
                $fields[] = 'remain';

            } else {
                if ($collection = $this->modx->getCollection('msOption')) {
                    foreach ($collection as $resourceId => $resource) {
                       /* $option[] = $resource->get('key');
                        $alias[$resource->get('key')] = $resource->get('caption') . $this->modx->lexicon('msimportexport.combo.field_caption_posfix');*/

                        $option[] = 'options-'.$resource->get('key');
                        $alias['options-'.$resource->get('key')] = $resource->get('caption') . $this->modx->lexicon('msimportexport.combo.field_caption_posfix');
                    }
                }
                $fields = array_diff(array_merge(
                    array_keys($this->modx->getFields('msProduct')),
                    array_keys($this->modx->getFields('msProductData')),
                    $option
                ), $exclude);
                $fields[] = 'categories';
                $fields[] = 'gallery';
            }
        } else {
            $fields[$query] = $query;
        }


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