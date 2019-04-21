<?php
require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';

class msImportExportGetListExportProcessor extends modObjectGetListProcessor {
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
            $exclude =  array_map('trim', explode(',', $this->modx->getOption('msimportexport.export.exclude_fields','')));
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
                , 'category.pagetitle' => $this->modx->lexicon('msimportexport.combo.field_parent_title')
                , 'remain' => $this->modx->lexicon('msimportexport.combo.field_remain')
                , 'ignore' => $this->modx->lexicon('msimportexport.combo.field_ignore')
                , 'categories' => $this->modx->lexicon('msimportexport.combo.field_сategories')
                , 'searchable' => $this->modx->lexicon('msimportexport.combo.field_searchable')
                , 'deleted' => $this->modx->lexicon('msimportexport.combo.field_deleted')
                , 'pub_date' => $this->modx->lexicon('msimportexport.combo.field_pub_date')
                , 'unpub_date' => $this->modx->lexicon('msimportexport.combo.field_unpub_date')
                , 'template' => $this->modx->lexicon('msimportexport.combo.field_template')
                , 'published' => $this->modx->lexicon('msimportexport.combo.field_published')
                , 'gallery' => $this->modx->lexicon('msimportexport.combo.field_gallery')
            );
            if ($collection = $this->modx->getCollection('msOption')) {
                foreach ($collection as $resourceId => $resource) {
                    $option[] = $resource->get('key');
                    $alias[$resource->get('key')] = $resource->get('caption') . $this->modx->lexicon('msimportexport.combo.field_caption_posfix');
                }
            }
            $tvs = array();
            if ($tvArr = $this->msie->getTVs()) {
                foreach ($tvArr as $tv) {
                    $key = 'tv.' . $tv['name'];
                    $tvs[] = $key;
                    $alias[$key] = $tv['name'] . (!empty($tv['caption']) ? $tv['caption'] : '');
                }
            }

            $fields = array_diff(array_merge(
                array_keys($this->modx->getFields('msProduct')),
                array_keys($this->modx->getFields('msProductData')),
                $option,
                $tvs
            ), $exclude);

            if ($this->getProperty('pemain',false) == 'true') {
                $fields[] = 'remain';
            }
            $fields[] = 'gallery';
            $fields[] = 'categories';
            $fields[] = 'category.pagetitle';

        } else {
            $fields[$query] = $query;
        }

        $list = array();
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
return 'msImportExportGetListExportProcessor';