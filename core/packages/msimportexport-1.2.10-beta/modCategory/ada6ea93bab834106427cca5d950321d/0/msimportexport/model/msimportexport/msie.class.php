<?php
require_once(dirname(__FILE__) . '/yandexmarket.class.php');

/**
 * MODx Msie Class
 *
 * @package msimportexport
 */
class Msie
{
    /** @var MsieController $controller */
    public $controller;
    /** @var int $root_category_id */
    private $root_category_id = null;
    /** @var string $siteUrl */
    private $siteUrl;
    /** @var array $arrayFieldsProduct */
    private $arrayFieldsProduct = array();
    /** @var array $treeCategoriesId */
    private $treeCategoriesId = array();
    /** @var array $categoryIds */
    private $categoryIds = array();
    /** @var array $categories */
    private $categories = array();
    /* @var miniShop2 $miniShop2 */
    private $miniShop2 = null;

    /**
     * Creates an instance of the Msie class.
     *
     * @param modX &$modx A reference to the modX instance.
     * @param array $config An array of configuration parameters.
     * @return Msie
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;
        $this->modx->lexicon->load('msimportexport:default');
        $corePath = $this->modx->getOption('msimportexport.core_path', $config, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/msimportexport/');
        $assetsUrl = $this->modx->getOption('msimportexport.assets_url', $config, $this->modx->getOption('assets_url') . 'components/msimportexport/');
        $assetsPath = $this->modx->getOption('msimportexport.assets_path', $config, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/msimportexport/');
        $this->config = array_merge(array(
            'namespace' => 'msimportexport',
            'chunksPath' => $corePath . 'elements/chunks/',
            'controllersPath' => $corePath . 'controllers/',
            'corePath' => $corePath,
            'uploadPath' => $assetsPath . 'upload/',
            'assetsPath' => $assetsPath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'templatesPath' => $corePath . 'elements/templates/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'managerUrl' => MODX_MANAGER_URL,
            'exportUrl' => $assetsUrl . 'export/export.php',
            'importUrl' => $assetsUrl . 'import/import.php',
            'actionUrl' => $assetsUrl . 'action.php',
            'token' => $this->getToken(),
            'manager_url' => $this->modx->config['manager_url'],
            'session_fseek' => $this->modx->getOption('msimportexport.session_fseek', null, 'msie_fseek'),
            'msproductremains' => $this->modx->getOption('mspr_core_path', $config, $this->modx->getOption('core_path') . 'components/msproductremains/model/'),
        ), $config);
        $this->siteUrl = MODX_URL_SCHEME . MODX_HTTP_HOST;
        $this->modx->addPackage('msimportexport', $this->config['modelPath']);
        $memoryLimit = $this->modx->getOption('msimportexport.memory_limit', null, 0);
        set_time_limit($this->modx->getOption('msimportexport.time_limit', null, 600));
        if (!empty($memoryLimit)) ini_set('memory_limit', $memoryLimit . 'M');
    }

    /**
     * Load the appropriate controller
     * @param string $controller
     * @return null|MsieController
     */
    public function loadController($controller)
    {
        if ($this->modx->loadClass('MsieController', $this->config['modelPath'] . 'msimportexport/', true, true)) {
            $classPath = $this->config['controllersPath'] . 'web/' . mb_strtolower($controller) . '.php';
            $className = 'msImportExport' . $controller . 'Controller';
            if (file_exists($classPath)) {
                if (!class_exists($className)) {
                    $className = require_once $classPath;
                }
                if (class_exists($className)) {
                    $this->controller = new $className($this, $this->config);
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[msImportExport] Could not load controller: ' . $className . ' at ' . $classPath);
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[msImportExport] Could not load controller file: ' . $classPath);
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[msImportExport] Could not load MsieController class.');
        }
        return $this->controller;
    }

    /**
     * Loads the Validator class.
     *
     * @access public
     * @param string $type The name to give the service on the msie object
     * @param array $config An array of configuration parameters for the
     * MsieValidator class
     * @return MsieValidator An instance of the MsieValidator class.
     */
    public function loadValidator($type = 'validator', $config = array())
    {
        if (!$this->modx->loadClass('MsieValidator', $this->config['modelPath'], true, true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[msImportExport] Could not load Validator class.');
            return false;
        }
        $this->$type = new MsieValidator($this, $config);
        return $this->$type;
    }

    /**
     * Helper function to get a chunk or tpl by different methods.
     *
     * @access public
     * @param string $name The name of the tpl/chunk.
     * @param array $properties The properties to use for the tpl/chunk.
     * @param string $type The type of tpl/chunk. Can be embedded,
     * modChunk, file, or inline. Defaults to modChunk.
     * @return string The processed tpl/chunk.
     */
    public function getChunk($name, $properties, $type = 'modChunk')
    {
        $output = '';
        switch ($type) {
            case 'embedded':
                if (!$this->modx->user->isAuthenticated($this->modx->context->get('key'))) {
                    $this->modx->setPlaceholders($properties);
                }
                break;
            case 'modChunk':
                $output .= $this->modx->getChunk($name, $properties);
                break;
            case 'file':
                $name = str_replace(array(
                    '{base_path}',
                    '{assets_path}',
                    '{core_path}',
                ), array(
                    $this->modx->getOption('base_path'),
                    $this->modx->getOption('assets_path'),
                    $this->modx->getOption('core_path'),
                ), $name);
                $output .= file_get_contents($name);
                $this->modx->setPlaceholders($properties);
                break;
            case 'inline':
            default:
                /* default is inline, meaning the tpl content was provided directly in the property */
                $chunk = $this->modx->newObject('modChunk');
                $chunk->setContent($name);
                $chunk->setCacheable(false);
                $output .= $chunk->process($properties);
                break;
        }
        return $output;
    }


    /**
     * @return array
     */
    public function getContexts($exclude = array())
    {
        $contexts = array();
        $query = $this->modx->newQuery('modContext');
        $query->select($this->modx->escape('key'));
        if ($exclude) {
            $query->where(array('key:NOT IN' => $exclude));
        }
        if ($query->prepare() && $query->stmt->execute()) {
            $contexts = $query->stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        return $contexts;
    }

    /**
     * @return miniShop2|null
     */
    public function getMiniShop2()
    {
        if (!$this->miniShop2) {
            $this->miniShop2 = $this->modx->getService('miniShop2');
        }
        return $this->miniShop2;
    }

    /**
     * @param string $key
     * @param string $value
     * @param bool $clearCache
     */
    public function setOption($key, $value, $clearCache = true)
    {
        if (!$setting = $this->modx->getObject('modSystemSetting', $key)) {
            $setting = $this->modx->newObject('modSystemSetting');
            $setting->set('namespace', $this->config['namespace']);
        }
        $setting->set('value', $value);
        if ($setting->save()) {
            $this->modx->config[$key] = $value;
            if ($clearCache) {
                $this->modx->cacheManager->refresh(array('system_settings' => array()));
            }
            return true;
        }
        return false;
    }

    /**
     * @param $str
     * @param array $varArr
     * @param string $prefix
     * @param string $suffix
     * @return mixed
     */
    public function parseString($str, $varArr = array(), $prefix = '[[+', $suffix = ']]')
    {
        if (is_array($varArr)) {
            reset($varArr);
            while (list($key, $value) = each($varArr)) {
                $str = str_replace($prefix . $key . $suffix, $value, $str);
            }
        }
        return $str;
    }

    /**
     * @param string $pagetitle
     * @param bool|null $useOnlyRootCatalog
     * @return int
     */
    private function getIdByPegeTitle($pagetitle = '', $useOnlyRootCatalog = null)
    {
        $useOnlyRootCatalog = $useOnlyRootCatalog === null ? filter_var($this->modx->getOption('msimportexport.import.use_only_root_catalog', null, 0), FILTER_VALIDATE_BOOLEAN) : $useOnlyRootCatalog;
        $q = $this->modx->newQuery('modResource');
        $q->where(array(
            'pagetitle:=' => trim($pagetitle),
        ));
        if ($useOnlyRootCatalog) {
            $q->where(array(
                'id:IN' => $this->getChildIds($this->getRootCategoryId()),
            ));
        }

        $r = $this->modx->getObject('modResource', $q);
        return $r ? $r->get('id') : 0;
    }

    /**
     * @param string $name
     * @param bool $cache
     * @param bool|null $useOnlyRootCatalog
     * @return int
     */
    private function getCategoryIdByName($name, $cache = true, $useOnlyRootCatalog = null)
    {
        if ($cache && isset($this->categoryIds[$name])) {
            return $this->categoryIds[$name];
        } else {
            if ($id = $this->getIdByPegeTitle($name, $useOnlyRootCatalog)) {
                $this->categoryIds[$name] = $id;
                return $id;
            }
        }
        return 0;
    }

    /**
     * @param string $name
     * @param int $parentId
     * @return int
     */
    private function checkSubCategoryByName($name, $parentId)
    {
        $q = $this->modx->newQuery('modResource');
        $q->where(array(
            'pagetitle:=' => trim($name),
            'parent:=' => $parentId
        ));
        $r = $this->modx->getObject('modResource', $q);
        return $r ? $r->get('id') : 0;
    }

    /**
     * @param string $name
     * @param bool $cache
     * @param bool|null $useOnlyRootCatalog
     * @return array
     */
    private function getCategoryDataByName($name, $cache = true, $useOnlyRootCatalog = null)
    {
        $useOnlyRootCatalog = $useOnlyRootCatalog === null ? filter_var($this->modx->getOption('msimportexport.import.use_only_root_catalog', null, 0), FILTER_VALIDATE_BOOLEAN) : $useOnlyRootCatalog;
        if ($cache && isset($this->categories[$name])) {
            return $this->categories[$name];
        } else {
            $q = $this->modx->newQuery('modResource');
            $q->where(array(
                'pagetitle:=' => trim($name),
            ));
            if ($useOnlyRootCatalog) {
                $q->where(array(
                    'id:IN' => $this->getChildIds($this->getRootCategoryId()),
                ));
            }
            if ($r = $this->modx->getObject('modResource', $q)) {
                $this->categories[$name] = $r->toArray();
                return $this->categories[$name];
            }
        }
        return array();
    }

    /**
     * @return int
     */
    public function getRootCategoryId()
    {
        if ($this->root_category_id == null) {
            $catalog = $this->modx->getOption('msimportexport.import.root_catalog', null, 0);
            if (empty($catalog)) {
                $q = $this->modx->newQuery('msCategory');
                $q->where(array('class_key:=' => 'msCategory'));
                $q->sortby('id');
                $r = $this->modx->getObject('msCategory', $q);
                $this->root_category_id = $r ? $r->get('id') : 0;
            } else {
                $this->root_category_id = (int)$catalog;
            }
        }
        return $this->root_category_id;
    }

    /**
     * @param int $presetId
     * @param bool $log
     * @return bool
     */
    public function getPresetFields($presetId, $log = true)
    {
        $q = $this->modx->newQuery('MsiePresetsFields');
        $q->where(array('id:=' => $presetId));
        if ($preset = $this->modx->getObject('MsiePresetsFields', $q)) {
            $fields = $preset->get('fields');
            if (empty($fields) && $log)
                $this->modx->log(modX::LOG_LEVEL_ERROR, sprintf($this->modx->lexicon('msimportexport.err.preset_empty_fields'), $presetId));
            return array_map('trim', $fields);
        }
        if ($log) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, sprintf($this->modx->lexicon('msimportexport.err.ns_preset'), $presetId));
        }
        return false;
    }

    /**
     * @param int $presetId
     * @param array $fields
     * @return bool
     */
    public function setPresetFields($presetId, $fields = array())
    {
        $arr = array();
        foreach ($fields as $key => $v) {
            if ($v != -1) {
                $arr[$key] = $v;
            }
        }
        $q = $this->modx->newQuery('MsiePresetsFields');
        $q->where(array('id:=' => $presetId));
        if ($preset = $this->modx->getObject('MsiePresetsFields', $q)) {
            $preset->set('fields', $arr);
            return $preset->save();
        }
        return false;
    }

    /**
     * @param string $name
     * @param int|null $parent
     * @param bool $toArr
     * @return bool|int|array
     */
    private function createCategory($name, $parent = null, $toArr = false)
    {
        $name = trim($name);
        $parent = $parent === null ? $this->getRootCategoryId() : $parent;
        $templateCategory = (int)$this->modx->getOption('msimportexport.import.template_category', null, 0);
        $data = array('pagetitle' => $name, 'parent' => $parent, 'class_key' => 'msCategory');
        if (!empty($templateCategory)) {
            $data['template'] = $templateCategory;
        }
        $response = $this->modx->runProcessor('resource/create', $data);
        if ($response->isError()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err_create_category') . ":\n" . print_r($response->getAllErrors(), 1));
            return false;
        }
        $resource = $response->getObject();
        return $toArr ? $resource : $resource['id'];
    }

    /**
     * @param array $names
     * @return array
     */
    private function getCategoriesByName($names = array())
    {
        $result = array();
        $useOnlyRootCatalog = filter_var($this->modx->getOption('msimportexport.import.use_only_root_catalog', null, 0), FILTER_VALIDATE_BOOLEAN);
        $q = $this->modx->newQuery('modResource');
        $q->where(array(
            'pagetitle:IN' => $names,
        ));
        if ($useOnlyRootCatalog) {
            $q->where(array(
                'id:IN' => $this->getChildIds($this->getRootCategoryId()),
            ));
        }
        $q->select(array(
            'modResource.id',
            'modResource.pagetitle',
        ));
        $s = $q->prepare();
        $s->execute();
        foreach ($s->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $result[$item['pagetitle']] = $item['id'];
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function checkValidityСatalog()
    {
        if (filter_var($this->modx->getOption('import.check_validity_catalog', null, true), FILTER_VALIDATE_BOOLEAN)) {
            if (!$rootId = $this->getRootCategoryId()) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err_nf_root_catalog'));
                return false;
            }

            $class = 'modResource';
            $where = array('class_key:NOT IN' => array('msCategory', 'msProduct'));
            $default = array(
                'class' => $class,
                'fastMode' => false,
                'return' => 'data',
                'parents' => $rootId,
                'limit' => 0,
                'depth' => 10,
                'where' => $this->modx->toJSON($where),
            );

            if (!$this->modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
                return false;
            }

            $pdoFetch = new pdoFetch($this->modx, $default);
            $rows = $pdoFetch->run();

            if (!empty($rows)) {
                $info = '';
                foreach ($rows as $v) {
                    $info .= "ID:{$v['id']}; Name:{$v['pagetitle']}\n";
                }
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err_invalid_setup_catalog') . $info);
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    private function getListHeadAlias()
    {
        $result = array();
        $q = $this->modx->newQuery('MsieHeadAlias');
        $q->select(array(
            'MsieHeadAlias.*',
        ));
        $s = $q->prepare();
        $s->execute();
        foreach ($s->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $result[$item['key']] = $item['value'];
        }
        return $result;
    }

    /**
     * @param array $fields
     * @return array
     */
    private function prepareHeadFields($fields = array())
    {
        $result = array();
        if (!empty($fields)) {
            if ($alias = $this->getListHeadAlias()) {
                foreach ($fields as $field) {
                    if (isset($alias[$field])) {
                        $result[] = $alias[$field];
                    } else {
                        $result[] = $field;
                    }
                }
            }
        }
        return $result ? $result : $fields;
    }


    /**
     * @param array $treeCategories
     * @return array
     */
    private function getFirstNonExistentCategory($treeCategories = array())
    {
        $rTreeCategories = array_reverse($treeCategories);
        $count = count($rTreeCategories);
        if ($categories = $this->getCategoriesByName($rTreeCategories)) {
            foreach ($rTreeCategories as $k => $v) {
                if (isset($categories[$v])) {
                    return array('index' => ($count - $k), 'parent' => $categories[$v]);
                }
            }
        } else {
            return array('index' => 0, 'parent' => $this->getRootCategoryId());
        }
    }

    /**
     * @param string $sCategories
     * @return int
     */
    private function createTreeCategories($categories)
    {
        $delimeter = $this->modx->getOption('msimportexport.import.sub_delimeter', null, '|');
        $aCategories = is_array($categories) ? $categories : array_map('trim', explode($delimeter, $categories));
        if ($aCategories) {
            $key = md5(implode($delimeter, $aCategories));
            $count = count($aCategories);
            if (!isset($this->treeCategoriesId[$key])) {
                $neCategory = $this->getFirstNonExistentCategory($aCategories);
                $parent = $neCategory['parent'];
                for ($i = $neCategory['index']; $i < $count; $i++) {
                    if (!$parent = $this->createCategory($aCategories[$i], $parent)) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, '[createTreeCategories] unable to create category name:' . $aCategories[$i]);
                        return 0;
                    }
                }
                $this->treeCategoriesId[$key] = $parent;
                return $parent;
            } else {
                return $this->treeCategoriesId[$key];
            }
        }
        return 0;
    }

    /**
     * @param int $productId
     * @param string $sCategories
     * @param int $parent
     * @param bool $clear
     * @return bool
     */
    public function addProductToSubCategories($productId, $sCategories, $parent = 0, $clear = true)
    {
        $delimeter = $this->modx->getOption('msimportexport.import.sub_delimeter', null, '|');
        $delimeter2 = $this->modx->getOption('msimportexport.import.sub_delimeter2', null, '%');
        $aCategories = array_map('trim', explode($delimeter, $sCategories));
        if ($aCategories) {
            if ($clear) {
                $this->removeProductFromSubCategories($productId, $parent);
            }
            foreach ($aCategories as $category) {
                $pathCategories = array_map('trim', explode($delimeter2, $category));
                $count = count($pathCategories);
                if ($count == 1) {
                    $categoryId = $this->getCategoryIdByName($category);
                } else {
                    $categoryId = $this->createTreeCategories($pathCategories);
                }
                if ($categoryId) {
                    /* @var msCategoryMember $res */
                    $res = $this->modx->getObject('msCategoryMember', array('category_id' => $categoryId, 'product_id' => $productId));
                    if (!$res) {
                        $res = $this->modx->newObject('msCategoryMember');
                        $res->set('product_id', $productId);
                        $res->set('category_id', $categoryId);
                        $res->save();
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param int $productId
     * @param int $parent
     */
    private function removeProductFromSubCategories($productId, $parent)
    {
        $q = $this->modx->newQuery('msCategoryMember');
        $q->where(array('product_id:=' => $productId, 'category_id:!=' => $parent));
        if ($c = $this->modx->getCollection('msCategoryMember', $q)) {
            foreach ($c as $item) {
                $item->remove();
            }
        }
    }

    /**
     * @param array $excludeIds
     * @param array $params
     * @return array
     */
    private function postAction($excludeIds = array(), $params = array())
    {
        $success = 0;
        $error = 0;
        $total = 0;
        $q = $this->modx->newQuery('modResource');
        $q->where(array('class_key:=' => 'msProduct', 'id:NOT IN' => $excludeIds));
        $q->select(array('modResource.id'));
        $q->prepare();
        $q->stmt->execute();
        if ($ids = $q->stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $total = count($ids);
            foreach ($ids as $id) {
                if ($product = $this->modx->getObject('msProduct', $id)) {
                    $product->fromArray($params);
                    if ($product->save()) {
                        $success++;
                    } else {
                        $error++;
                        $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err_post_action') . $id . "\n" . print_r($params, 1));
                    }
                }
            }
        }
        $out = array('success' => $success, 'error' => $error, 'total' => $total);
        $this->modx->log(modX::LOG_LEVEL_INFO, "Ppost action:\n" . print_r($out, 1));
        return $out;
    }

    private function getFieldsArrayProduct()
    {
        $typeArrayOptions = array('combo-multiple', 'combo-options');
        if (!$this->arrayFieldsProduct) {
            $options = $this->modx->getCollection('msOption');
            if ($options) {
                foreach ($options as $option) {
                    if (in_array($option->type, $typeArrayOptions)) {
                        $this->arrayFieldsProduct['options-' . $option->key] = 'combo';
                    }
                }
            }
            $meta = $this->modx->getFieldMeta('msProductData');
            foreach ($meta as $k => $v) {
                if ($v['phptype'] == 'json') {
                    $this->arrayFieldsProduct[$k] = $v['phptype'];
                }
            }
        }
        return $this->arrayFieldsProduct;
    }

    private function hasFieldArrayProduct($field)
    {
        $fields = $this->getFieldsArrayProduct();
        return isset($fields[$field]);
    }


    /**
     * @param int $parent
     * @return bool
     */
    private function hasParent($parent)
    {
        $q = $this->modx->newQuery('msCategory');
        $q->where(array('id:=' => $parent));
        $q->prepare();
        $q->stmt->execute();
        return $q->stmt->fetchAll(PDO::FETCH_ASSOC) ? true : false;
    }

    /**
     * @param string $name
     * @return int
     */
    private function getVendorIdByName($name)
    {
        $q = $this->modx->newQuery('msVendor');
        $q->select(array('id'));
        $q->where(array('name:=' => $name));
        $q->prepare();
        $q->stmt->execute();
        return $q->stmt->fetchColumn();
    }

    /**
     * @param $name
     * @return int
     */
    private function addVendor($name)
    {
        $vendor = $this->modx->newObject('msVendor');
        $vendor->set('name', $name);
        if ($vendor->save()) {
            return $vendor->get('id');
        } else {
            return 0;
        }
    }

    /**
     * @param string $parents
     * @param int $depth
     * @return array|bool|string
     */
    public function getCategoryChildren($parents = '', $depth = 10, $limit = 0)
    {
        // Default parameters
        $class = 'msCategory'; //modResource
        $where = array('class_key:=' => 'msCategory', 'show_in_tree:=' => 1, 'isfolder:=' => 1);
        $default = array(
            'class' => $class,
            'sortby' => $class . '.id',
            'sortdir' => 'ASC',
            //'groupby' => $class.'.parent',
            'fastMode' => false,
            'return' => 'data',
            'parents' => $parents,
            'limit' => $limit,
            'depth' => $depth,
            'where' => $this->modx->toJSON($where),
        );
        if (!$this->modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
            return false;
        }
        $pdoFetch = new pdoFetch($this->modx, $default);
        $children = $pdoFetch->run();
        unset($default['parents']);
        $where['id:IN'] = array_map('trim', explode(',', $parents));
        $where['id:!='] = $this->getRootCategoryId();
        $default['where'] = $this->modx->toJSON($where);
        $pdoFetch->setConfig($default);
        return array_merge($children, $pdoFetch->run());
    }

    /**
     * @param int $id
     * @param bool $useSelf
     * @param int $depth
     * @param int $limit
     * @return array
     */
    public function getChildIds($id, $useSelf = true, $depth = 100, $limit = 0)
    {
        $class = 'msCategory';
        $where = array('class_key:=' => 'msCategory', 'show_in_tree:=' => 1, 'isfolder:=' => 1);
        $default = array(
            'class' => $class,
            'sortby' => $class . '.menuindex',
            'sortdir' => 'DESC',
            'fastMode' => false,
            'return' => 'ids',
            'parents' => $id,
            'limit' => $limit,
            'depth' => $depth,
            'where' => $this->modx->toJSON($where),
        );
        if (!$this->modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
            return false;
        }
        $pdoFetch = new pdoFetch($this->modx, $default);
        return explode(',', $pdoFetch->run());
    }

    public function getTreeCategories($id = null, $key = '', $depth = 100)
    {
        $class = 'msCategory';
        $default = array();
        $where = array('class_key:=' => 'msCategory', 'show_in_tree:=' => 1, 'isfolder:=' => 1);
        if ($id != null) {
            $default['parents'] = $id;
            if (empty($key)) {
                if ($category = $this->modx->getObject('msCategory', $id)) {
                    $key = $category->pagetitle;
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[getTreeCategories] not find category ID=' . $id);
                }
            }
        } else {
            $where['parent:='] = 0;
        }
        $default = array_merge($default, array(
            'class' => $class,
            'sortby' => $class . '.menuindex',
            'sortdir' => 'ASC',
            'fastMode' => false,
            'select' => 'id,pagetitle,parent',
            'return' => 'data',
            'limit' => 0,
            'depth' => 0,
            'where' => $this->modx->toJSON($where),
        ));
        if (!$this->modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
            return false;
        }
        $pdoFetch = new pdoFetch($this->modx, $default);
        $out = array();
        if (intval($depth) >= 1) {
            if ($children = $pdoFetch->run()) {
                if (!empty($key)) {
                    foreach ($children as $child) {
                        $out[$key][] = $child['pagetitle'];
                    }
                }
                $processDepth = $depth - 1;
                foreach ($children as $child) {
                    if ($c = $this->getTreeCategories($child['id'], $child['pagetitle'], $processDepth)) {
                        $out = array_merge($out, $c);
                    }
                }
            }
        }
        return $out;
    }


    /**
     * @param int $productId
     * @param int $parentId
     * @param string $column
     * @return array
     */
    private function getCategories($productId, $parentId, $column = 'pagetitle')
    {
        $out = array();
        $resourceColumns = array('id', 'pagetitle');
        $q = $this->modx->newQuery('modResource');
        $q->rightJoin('msCategoryMember', 'Member', array('modResource.id = ' . $parentId . ' OR (modResource.id = Member.category_id AND Member.product_id = ' . $productId . ')'));
        $q->select($this->modx->getSelectColumns('modResource', 'modResource', '', $resourceColumns));
        $q->select(array(
            'Member.member' => 'category_id'
        ));
        $q->where(array(
            'show_in_tree' => true
        , 'isfolder' => true
        ));
        $q->groupby($this->modx->getSelectColumns('modResource', 'modResource', '', $resourceColumns), '');
        $q->sortby('modResource.id');
        $s = $q->prepare();
        //$this->modx->log(modX::LOG_LEVEL_ERROR, $q->toSQL());
        $s->execute();
        foreach ($s->fetchAll(PDO::FETCH_ASSOC) as $data) {
            $out[] = $data[$column];
        }
        return $out;
    }

    /**
     * @param int $productId
     * @param array $options
     * @return string
     */
    private function createHashRemain($productId = 0, $options = array())
    {
        $str = '';
        asort($options);
        foreach ($options as $k => $v) {
            $str .= strtolower(trim($k)) . strtolower(trim($v));
        }
        return md5($productId . $str);
    }

    private function getRemains()
    {
        $data = array();
        $q = $this->modx->newQuery('msprRemains');
        $collection = $this->modx->getCollection('msprRemains', $q);
        foreach ($collection as $remain) {
            $hash = $this->createHashRemain($remain->product_id, $this->modx->fromJSON($remain->options));
            $data[$hash] = $remain->id;
        }
        return $data;
    }

    /**
     * @param array $data
     * @param int $remain_id
     * @return bool
     */
    private function setRemain($data = array(), $remain_id = 0)
    {
        if (!$remain_id) {
            $remain = $this->modx->newObject('msprRemains');
        } else {
            $remain = $this->modx->getObject('msprRemains', $remain_id);
        }
        if ($remain) {
            $remain->set('product_id', $data['product_id']);
            $remain->set('options', $this->modx->toJSON($data['options']));
            $remain->set('remains', $data['remain']);
            return $remain->save();
        }
        return false;
    }

    /**
     * @param string $hashKey
     * @param int $productId
     * @return int
     */
    private function getRemainIdByHash($hashKey, $productId = 0)
    {
        $q = $this->modx->newQuery('msprRemains');
        if ($productId) {
            $q->where(array('product_id:=' => $productId));
        }
        $q->sortby('id', 'DESC');
        $collection = $this->modx->getCollection('msprRemains', $q);
        foreach ($collection as $remain) {
            $hash = $this->createHashRemain($remain->product_id, $this->modx->fromJSON($remain->options));
            if ($hash == $hashKey)
                return $remain->id;
        }
        return 0;
    }

    /**
     * @param bool $save
     * @return string
     */
    private function generateExportToken($save = true)
    {
        $token = md5(MODX_HTTP_HOST . time() . microtime(true) . $this->modx->user->generatePassword());
        if ($save) {
            $this->setOption('msimportexport.token', $token);
        }
        return $token;
    }

    /**
     * @param bool $save
     * @return string
     */
    public function getToken()
    {
        $token = $this->modx->getOption('msimportexport.token', null, '');
        if (empty($token)) {
            $token = $this->generateExportToken();
        }
        return $token;
    }


    /**
     * @param string $token
     * @return bool
     */
    public function checkExportToken($token)
    {
        return $token == $this->modx->getOption('msimportexport.token', null, '') ? true : false;
    }

    /**
     * @param array $contexts
     * @param bool|false $ignoreUserAbort
     * @return array
     */
    public function updateUri($contexts = array(), $ignoreUserAbort = false)
    {
        ignore_user_abort($ignoreUserAbort);
        set_time_limit($this->modx->getOption('msimportexport.time_limit', null, 600));
        $before = $this->getDuplicateResources();
        $result = array(
            'total' => 0,
            'success' => 0,
            'failed' => 0,
        );
        if (!empty($before)) {
            $result['total'] = count($before);
            $contexts = empty($contexts) ? $this->getContexts(array('mgr')) : $contexts;
            $table = $this->modx->getTableName('modResource');
            $this->modx->exec("UPDATE {$table} SET uri = '', alias='' WHERE uri=alias");//удаляет uri равное alias
            $this->modx->exec("UPDATE {$table} SET uri = '', alias=''  WHERE alias = CONCAT(uri, '/')");//удаляет uri со слешем на конце равное alias
            //$this->modx->exec("UPDATE {$table} t1, (SELECT uri,alias FROM {$table} GROUP BY uri HAVING COUNT(*) > 1) t2 SET t1.uri = '', t1.alias='' WHERE (t2.uri = t1.uri  || t2.alias = t1.alias)   AND uri_override = 0");//удаляет дубликаты uri на всякий пожарный
            $this->modx->exec("UPDATE {$table} t1, (SELECT uri,alias FROM {$table} GROUP BY uri HAVING COUNT(*) > 1) t2 SET t1.uri = '', t1.alias='' WHERE t2.uri = t1.uri  AND uri_override = 0");//удаляет дубликаты uri на всякий пожарный
            $this->modx->cacheManager->refresh(array('action_map' => array()));
            $q = $this->modx->newQuery('modResource', array(
                'uri' => '', 'context_key:IN' => $contexts
            ));
            $q->select(array(
                'modResource.*',
            ));
            // $q->limit(3000);
            $q->sortby('id');

            $s = $q->prepare();
            //$this->modx->log(modX::LOG_LEVEL_ERROR, $q->toSQL());
            $s->execute();
            foreach ($s->fetchAll(PDO::FETCH_ASSOC) as $data) {
                $this->modx->runProcessor('resource/update', $data);
            }
            $after = $this->getDuplicateResources();
            if (!empty($after)) {
                $result['failed'] = count($after);
                $this->modx->log(modX::LOG_LEVEL_INFO, "Failed to update Uri the resource:\n" . print_r($after, 1));
            } else {
                $this->modx->log(modX::LOG_LEVEL_INFO, 'Successful update Uri');
            }
            $result['success'] = $result['total'] - $result['failed'];
        }
        return $result;
    }


    /**
     * @return array
     */
    private function getDuplicateResources()
    {
        $result = array();
        $table = $this->modx->getTableName('modResource');
        //$sql ="SELECT t1.id, t1.pagetitle, t1.parent,t1.uri,t1.alias  FROM {$table} t1, (SELECT uri,alias FROM {$table} GROUP BY uri HAVING COUNT(*) > 1) t2   WHERE (t2.uri = t1.uri  OR t2.alias = t1.alias)   AND uri_override = 0 AND deleted = 0";
        $sql = "SELECT t1.id, t1.pagetitle, t1.parent,t1.uri,t1.alias  FROM {$table} t1, (SELECT uri,alias FROM {$table} GROUP BY uri HAVING COUNT(*) > 1) t2   WHERE t2.uri = t1.uri   AND uri_override = 0 AND deleted = 0";
        $q = new xPDOCriteria($this->modx, $sql);
        $s = $q->prepare();
        if ($s->execute()) {
            $result = $s->fetchAll(PDO::FETCH_ASSOC);
        }
        return $result;
    }

    private function getLinks($data = array())
    {
        /* @var pdoFetch $pdoFetch */
        if (!$this->modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
            return false;
        }
        $pdoFetch = new pdoFetch($this->modx, $data);
        if (empty($data['class'])) {
            $data['class'] = 'msProductLink';
        }
        $class = $data['class'];
        $where = array();
        $innerJoin = array();
        $leftJoin = array(
            array('class' => 'msProduct', 'alias' => 'Master', 'on' => '`' . $class . '`.`master`=`Master`.`id`'),
            array('class' => 'msProductData', 'alias' => 'Data', 'on' => '`Master`.`id`=`Data`.`id`'),
        );
        // Fields to select
        $select = array(
            $class => $this->modx->getSelectColumns($class, $class, '', array('id'), true),
            'Master' => $this->modx->getSelectColumns('msProduct', 'Master'),
            'Data' => $this->modx->getSelectColumns('msProductData', 'Data', '', array('id'), true),
        );

        // Add custom parameters
        foreach (array('where', 'leftJoin', 'innerJoin', 'select') as $v) {
            if (!empty($data[$v])) {
                $tmp = $this->modx->fromJSON($data[$v]);
                if (is_array($tmp)) {
                    $$v = array_merge($$v, $tmp);
                }
            }
            unset($data[$v]);
        }

        $joinedOptions = array();
        // Add filters by options
        if (!empty($data['optionFilters'])) {
            $filters = $this->modx->fromJSON($data['optionFilters']);
            $opt_where = array();

            foreach ($filters as $key => $value) {
                $key_operator = explode(':', $key);
                $operator = '=';
                $conj = '';
                if ($key_operator && count($key_operator) === 2) {
                    $key = $key_operator[0];
                    $operator = $key_operator[1];
                } elseif ($key_operator && count($key_operator) === 3) {
                    $conj = $key_operator[0];
                    $key = $key_operator[1];
                    $operator = $key_operator[2];
                }

                if (!in_array($key, $joinedOptions)) {
                    $leftJoin[] = array('class' => 'msProductOption', 'alias' => $key, 'on' => "`{$key}`.`product_id`=`Data`.`id` AND `{$key}`.`key`='{$key}'");
                    $joinedOptions[] = $key;
                }

                if (!is_string($value)) {
                    if (!empty($conj)) {
                        $last_where = end($opt_where);
                        if (is_array($last_where)) {
                            $conj = !empty($conj) ? $conj . ':' : '';
                            $opt_where[] = array("{$conj}`{$key}`.`value`:{$operator}" => $value);
                        } else {
                            array_splice($opt_where, -1, 1, $last_where . " {$conj} `{$key}`.`value`{$operator}{$value}");
                        }
                    } else {
                        $opt_where[] = "`{$key}`.`value`{$operator}{$value}";
                    }

                } else {
                    $conj = !empty($conj) ? $conj . ':' : '';
                    $opt_where[] = array("{$conj}`{$key}`.`value`:{$operator}" => $value);
                }


            }
            $where[] = $opt_where;
        }

        // Add sorting by options
        if (!empty($data['sortbyOptions'])) {
            $sorts = explode(',', $data['sortbyOptions']);
            foreach ($sorts as $sort) {
                $sort = explode(':', $sort);
                $option = $sort[0];
                $type = 'string';
                if (isset($sort[1])) {
                    $type = $sort[1];
                }

                switch ($type) {
                    case 'number':
                    case 'decimal':
                        $sortbyOptions = "CAST(`{$option}`.`value` AS DECIMAL(13,3))";
                        break;
                    case 'integer':
                        $sortbyOptions = "CAST(`{$option}`.`value` AS UNSIGNED INTEGER)";
                        break;
                    case 'date':
                    case 'datetime':
                        $sortbyOptions = "CAST(`{$option}`.`value` AS DATETIME)";
                        break;
                    default:
                        $sortbyOptions = "`{$option}`.`value`";
                        break;
                }

                $data['sortby'] = str_replace($option, $sortbyOptions, $data['sortby']);

                if (!in_array($option, $joinedOptions)) {
                    $leftJoin[] = array('class' => 'msProductOption', 'alias' => $option, 'on' => "`{$option}`.`product_id`=`Data`.`id` AND `{$option}`.`key`='{$option}'");
                    $joinedOptions[] = $option;
                }

            }
        }


        // Default parameters
        $default = array(
            'class' => $class,
            'where' => $this->modx->toJSON($where),
            'leftJoin' => $this->modx->toJSON($leftJoin),
            'innerJoin' => $this->modx->toJSON($innerJoin),
            'select' => $this->modx->toJSON($select),
            'sortby' => $class . '.master',
            'sortdir' => 'ASC',
            'groupby' => $class . '.master',
            'fastMode' => false,
            'return' => !empty($returnIds) ? 'ids' : 'data',
            'nestedChunkPrefix' => 'minishop2_',
        );


        // Merge all properties and run!

        $pdoFetch->setConfig(array_merge($default, $data));
        $rows = $pdoFetch->run();

        if (!empty($returnIds)) {
            return $rows;
        }

        $output = array();
        if (!empty($rows) && is_array($rows)) {
            $q = $this->modx->newQuery('modPluginEvent', array('event:IN' => array('msOnGetProductPrice', 'msOnGetProductWeight')));
            $q->innerJoin('modPlugin', 'modPlugin', 'modPlugin.id = modPluginEvent.pluginid');
            $q->where('modPlugin.disabled = 0');

            if ($modificators = $this->modx->getOption('ms2_price_snippet', null, false, true) || $this->modx->getOption('ms2_weight_snippet', null, false, true) || $this->modx->getCount('modPluginEvent', $q)) {
                /* @var msProductData $product */
                $product = $this->modx->newObject('msProductData');
            }
            $pdoFetch->addTime('Checked the active modifiers');

            $opt_time = 0;
            foreach ($rows as $k => $row) {
                if ($modificators) {
                    $product->fromArray($row, '', true, true);
                    $tmp = $row['price'];
                    $row['price'] = $product->getPrice($row);
                    $row['weight'] = $product->getWeight($row);
                    if ($row['price'] != $tmp) {
                        $row['old_price'] = $tmp;
                    }
                }

                $row['idx'] = $pdoFetch->idx++;

                $opt_time_start = microtime(true);
                $options = $this->modx->call('msProductData', 'loadOptions', array(&$this->modx, $row['id']));
                $row = array_merge($row, $options);
                $opt_time += microtime(true) - $opt_time_start;
                $output[] = $row;
                if (!empty($data['isDebug'])) {
                    $default['return'] = 'sql';
                    $pdoFetch->setConfig(array_merge($default, $data));
                    $this->modx->log(modX::LOG_LEVEL_INFO, $pdoFetch->run());
                    $this->modx->log(modX::LOG_LEVEL_INFO, print_r($output, 1));
                    return $output;
                }
            }
            $pdoFetch->addTime('Loaded options for products', $opt_time);
            $pdoFetch->addTime('Returning processed chunks');
        }

        return $output;

    }


    /**
     * @param string $file
     * @return null|MsieReader
     */
    public function getReader($file = '')
    {
        $className = '';
        $ext = mb_strtolower(pathinfo($file, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'csv':
                require_once dirname(__FILE__) . '/reader/msiecsvreader.class.php';
                $className = 'MsieCsvReader';
                break;
            case 'xls':
            case 'xlsx':
                if (extension_loaded('excel')) {
                    require_once dirname(__FILE__) . '/reader/msielibxlreader.class.php';
                    $className = 'MsieLibXlReader';
                } else {
                    require_once dirname(__FILE__) . '/reader/msieexcelreader.class.php';
                    $className = 'MsieExcelReader';
                }
                break;
        }

        if ($className) {
            try {
                return new $className($this->modx);
            } catch (Exception $e) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error create  reader class ' . $className . '  ' . $e->getMessage());
            }
        }

        return null;
    }

    public function testReader($file, $seek = 0)
    {
        $skipLine = false;
        $tstart = microtime(1);
        $startMemory = memory_get_usage();
        $count = 0;
        $limit = $this->modx->getOption('msimportexport.import.step_limit', null, 50);
        if ($reader = $this->getReader($file)) {
            $reader->read(array(
                'file' => $file,
                'seek' => $seek,
            ), function ($reader, $data) use (& $count, $skipLine, $limit) {
                if ($skipLine && $count == 0) {
                    return true;
                }
                $count++;
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Seek=' . $reader->getSeek());
                $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($data, 1));
                return $count <= $limit;//|| $reader->getSeek() > 0;
            });
        }
        $tend = microtime(1);
        $totalTime = ($tend - $tstart);
        $totalTime = sprintf("%2.4f s", $totalTime);
        $totalMemory = memory_get_usage() - $startMemory;
        $this->modx->log(modX::LOG_LEVEL_ERROR, 'totalTime=' . $totalTime);
        $this->modx->log(modX::LOG_LEVEL_ERROR, 'totalMemory=' . $totalMemory);
        echo 'totalTime:' . $totalTime . "\n";
        echo 'totalMemory: ' . $totalMemory . " bytes\n";
    }

    /**
     * @param string|array $productIds
     * @param string $thumb
     * @return array
     */
    private function getProductsGallery($productIds = '', $thumb = '')
    {
        $productIds = is_array($productIds) ? $productIds : explode(',', $productIds);
        // $thumbs =  !empty($thumbs) ? array_map('trim',explode(',',$thumbs)) : array();
        $q = $this->modx->newQuery('msProductFile', array('product_id:IN' => $productIds));
        if (empty($thumb)) {
            $q->where(array('parent:=' => 0));
        } else {
            $q->where(array('parent:!=' => 0, 'path:LIKE' => '%' . $thumb . '%'));
        }
        $q->groupby('product_id');
        $q->select('product_id,url');

        $out = array();
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!isset($out[$row['product_id']])) {
                    $out[$row['product_id']] = array();
                }
                $out[$row['product_id']][] = ltrim($row['url'], '/');
            }

        }
        return $out;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getProducts($data = array())
    {
        /* @var miniShop2 $miniShop2 */
        $miniShop2 = $this->modx->getService('minishop2');
        $miniShop2->initialize($this->modx->context->key);
        $includeContent = true;
        $includeParentTitle = isset($data['fields']) ? in_array('category.pagetitle', $data['fields']) : false;
        // You can set modResource instead of msProduct
        if (empty($data['class'])) {
            $data['class'] = 'msProduct';
        }
        if (empty($data['parents'])) {
            $data['parents'] = $this->getRootCategoryId();
        }
        $class = $data['class'];

        /* @var pdoFetch $pdoFetch */
        if (!$this->modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
            return false;
        }
        $pdoFetch = new pdoFetch($this->modx, $data);

        // Start building "Where" expression
        $where = array('class_key' => 'msProduct');
        // if (empty($data['showZeroPrice'])) {$where['Data.price:>'] = 0;}

        // Joining tables
        $leftJoin = array(
            array('class' => 'msProductData', 'alias' => 'Data', 'on' => '`' . $class . '`.`id`=`Data`.`id`'),
            array('class' => 'msVendor', 'alias' => 'Vendor', 'on' => '`Data`.`vendor`=`Vendor`.`id`'),
        );

        $innerJoin = array();


        // Include Thumbnails
        $thumbsSelect = array();
        if (!empty($data['includeThumbs'])) {
            $thumbs = array_map('trim', explode(',', $data['includeThumbs']));
            if (!empty($thumbs[0])) {
                foreach ($thumbs as $thumb) {
                    $leftJoin[] = array(
                        'class' => 'msProductFile',
                        'alias' => $thumb,
                        'on' => "`$thumb`.`product_id` = `$class`.`id` AND `$thumb`.`parent` != 0 AND `$thumb`.`path` LIKE '%/$thumb/'"
                    );
                    $thumbsSelect[$thumb] = "`$thumb`.`url` as `$thumb`";
                }
            }
        }

        // include Linked products
        if (!empty($link) && !empty($master)) {
            $innerJoin[] = array('class' => 'msProductLink', 'alias' => 'Link', 'on' => '`' . $class . '`.`id` = `Link`.`slave` AND `Link`.`link` = ' . $link);
            $where['Link.master'] = $master;
        } else if (!empty($link) && !empty($slave)) {
            $innerJoin[] = array('class' => 'msProductLink', 'alias' => 'Link', 'on' => '`' . $class . '`.`id` = `Link`.`master` AND `Link`.`link` = ' . $link);
            $where['Link.slave'] = $slave;
        }

        // Fields to select
        $select = array(
            $class => !empty($includeContent) ? $this->modx->getSelectColumns($class, $class) : $this->modx->getSelectColumns($class, $class, '', array('content'), true),
            'Data' => $this->modx->getSelectColumns('msProductData', 'Data', '', array('id'), true),
            'Vendor' => $this->modx->getSelectColumns('msVendor', 'Vendor', 'vendor.', array(), true),
        );

        if ($includeParentTitle) {
            $innerJoin[] = array('class' => 'msCategory', 'alias' => 'Category', 'on' => '`' . $class . '`.`parent` = `Category`.`id`');
            $select['Category'] = $this->modx->getSelectColumns('msCategory', 'Category', 'category.', array('pagetitle'), false);
        }


        if (!empty($thumbsSelect)) {
            $select = array_merge($select, $thumbsSelect);
        }


        // Add custom parameters
        foreach (array('where', 'leftJoin', 'innerJoin', 'select') as $v) {
            if (!empty($data[$v])) {
                $tmp = $this->modx->fromJSON($data[$v]);
                if (is_array($tmp)) {
                    $$v = array_merge($$v, $tmp);
                }
            }
            unset($data[$v]);
        }

        $joinedOptions = array();
        // Add filters by options
        if (!empty($data['optionFilters'])) {
            $filters = $this->modx->fromJSON($data['optionFilters']);
            $opt_where = array();

            foreach ($filters as $key => $value) {
                $key_operator = explode(':', $key);
                $operator = '=';
                $conj = '';
                if ($key_operator && count($key_operator) === 2) {
                    $key = $key_operator[0];
                    $operator = $key_operator[1];
                } elseif ($key_operator && count($key_operator) === 3) {
                    $conj = $key_operator[0];
                    $key = $key_operator[1];
                    $operator = $key_operator[2];
                }

                if (!in_array($key, $joinedOptions)) {
                    $leftJoin[] = array('class' => 'msProductOption', 'alias' => $key, 'on' => "`{$key}`.`product_id`=`Data`.`id` AND `{$key}`.`key`='{$key}'");
                    $joinedOptions[] = $key;
                }

                if (!is_string($value)) {
                    if (!empty($conj)) {
                        $last_where = end($opt_where);
                        if (is_array($last_where)) {
                            $conj = !empty($conj) ? $conj . ':' : '';
                            $opt_where[] = array("{$conj}`{$key}`.`value`:{$operator}" => $value);
                        } else {
                            array_splice($opt_where, -1, 1, $last_where . " {$conj} `{$key}`.`value`{$operator}{$value}");
                        }
                    } else {
                        $opt_where[] = "`{$key}`.`value`{$operator}{$value}";
                    }

                } else {
                    $conj = !empty($conj) ? $conj . ':' : '';
                    $opt_where[] = array("{$conj}`{$key}`.`value`:{$operator}" => $value);
                }


            }
            $where[] = $opt_where;
        }

        // Add sorting by options
        if (!empty($data['sortbyOptions'])) {
            $sorts = explode(',', $data['sortbyOptions']);
            foreach ($sorts as $sort) {
                $sort = explode(':', $sort);
                $option = $sort[0];
                $type = 'string';
                if (isset($sort[1])) {
                    $type = $sort[1];
                }

                switch ($type) {
                    case 'number':
                    case 'decimal':
                        $sortbyOptions = "CAST(`{$option}`.`value` AS DECIMAL(13,3))";
                        break;
                    case 'integer':
                        $sortbyOptions = "CAST(`{$option}`.`value` AS UNSIGNED INTEGER)";
                        break;
                    case 'date':
                    case 'datetime':
                        $sortbyOptions = "CAST(`{$option}`.`value` AS DATETIME)";
                        break;
                    default:
                        $sortbyOptions = "`{$option}`.`value`";
                        break;
                }

                $data['sortby'] = str_replace($option, $sortbyOptions, $data['sortby']);

                if (!in_array($option, $joinedOptions)) {
                    $leftJoin[] = array('class' => 'msProductOption', 'alias' => $option, 'on' => "`{$option}`.`product_id`=`Data`.`id` AND `{$option}`.`key`='{$option}'");
                    $joinedOptions[] = $option;
                }

            }
        }

        // Default parameters
        $default = array(
            'class' => $class,
            'where' => $this->modx->toJSON($where),
            'leftJoin' => $this->modx->toJSON($leftJoin),
            'innerJoin' => $this->modx->toJSON($innerJoin),
            'select' => $this->modx->toJSON($select),
            'sortby' => $class . '.id',
            'sortdir' => 'ASC',
            'groupby' => $class . '.id',
            'fastMode' => false,
            'return' => !empty($returnIds) ? 'ids' : 'data',
            'nestedChunkPrefix' => 'minishop2_',
        );

        if (!empty($data['optionsTv'])) {
            $tvs = array();
            foreach ($data['optionsTv'] as $v) {
                if (preg_match('/^tv\.([^\.][\w\.]+)$/', $v)) {
                    $tvs[] = str_replace("tv.", '', $v);

                }
            }
            if (!empty($tvs)) {
                $default['includeTVs'] = implode(',', $tvs);
                $default['tvPrefix'] = 'tv.';
            }
        }


        if (!empty($data['fields'])) {
            $tvs = array();
            foreach ($data['fields'] as $v) {
                if (preg_match('/^tv\.([^\.][\w\.]+)$/', $v)) {
                    $tvs[] = str_replace("tv.", '', $v);

                }
            }
            if (!empty($tvs)) {
                $default['includeTVs'] = implode(',', $tvs);
                $default['tvPrefix'] = 'tv.';
            }

        }

        /* $pdoFetch->setConfig(array_merge($default, $data, array('return'=>'sql')));
         $this->modx->log(modX::LOG_LEVEL_ERROR, $pdoFetch->run());*/
        // Merge all properties and run!

        $pdoFetch->setConfig(array_merge($default, $data));
        $rows = $pdoFetch->run();


        if (!empty($returnIds)) {
            return $rows;
        }

        $productsGallery = array();
        if (!empty($data['isGallery'])) {
            $pdoFetch->setConfig(array_merge($default, $data, array('return' => 'ids')));
            $ids = $pdoFetch->run();
            $productsGallery = $this->getProductsGallery($ids);
        }


        $output = array();
        if (!empty($rows) && is_array($rows)) {
            $q = $this->modx->newQuery('modPluginEvent', array('event:IN' => array('msOnGetProductPrice', 'msOnGetProductWeight')));
            $q->innerJoin('modPlugin', 'modPlugin', 'modPlugin.id = modPluginEvent.pluginid');
            $q->where('modPlugin.disabled = 0');

            if ($modificators = $this->modx->getOption('ms2_price_snippet', null, false, true) || $this->modx->getOption('ms2_weight_snippet', null, false, true) || $this->modx->getCount('modPluginEvent', $q)) {
                /* @var msProductData $product */
                $product = $this->modx->newObject('msProductData');
            }
            $pdoFetch->addTime('Checked the active modifiers');

            $opt_time = 0;
            foreach ($rows as $k => $row) {
                if ($modificators) {
                    $product->fromArray($row, '', true, true);
                    $tmp = $row['price'];
                    $row['price'] = $product->getPrice($row);
                    $row['weight'] = $product->getWeight($row);
                    if ($row['price'] != $tmp) {
                        $row['old_price'] = $tmp;
                    }
                }
                /*$row['price'] = $miniShop2->formatPrice($row['price']);
                $row['old_price'] = $miniShop2->formatPrice($row['old_price']);
                $row['weight'] = $miniShop2->formatWeight($row['weight']);*/

                $row['idx'] = $pdoFetch->idx++;

                $opt_time_start = microtime(true);
                $options = $this->modx->call('msProductData', 'loadOptions', array(&$this->modx, $row['id']));
                if (!empty($data['isCategories'])) {
                    $row['categories'] = $this->getCategories($row['id'], $row['parent']);
                }

                if (!empty($data['isGallery'])) {
                    $row['gallery'] = (!empty($productsGallery) && isset($productsGallery[$row['id']])) ? $productsGallery[$row['id']] : array();
                }

                $row = array_merge($row, $options);
                $opt_time += microtime(true) - $opt_time_start;
                $output[] = $row;
                if (!empty($data['isDebug'])) {
                    $default['return'] = 'sql';
                    $pdoFetch->setConfig(array_merge($default, $data));
                    $this->modx->log(modX::LOG_LEVEL_INFO, $pdoFetch->run());
                    $this->modx->log(modX::LOG_LEVEL_INFO, print_r($output, 1));
                    return $output;
                }
            }
            $pdoFetch->addTime('Loaded options for products', $opt_time);
            $pdoFetch->addTime('Returning processed chunks');
        }

        return $output;
    }

    /**
     * @param string $filename
     * @param int $preset
     * @param int $seek
     * @return array
     */
    private function importRemain($filename = '', $preset, $seek = 0)
    {
        $this->modx->addPackage('msproductremains', $this->config['msproductremains']);
        $out = array(
            'errors' => 0,
            'create' => 0,
            'update' => 0,
            'seek' => 0,
            'rows' => 0,
        );
        $file = $this->config['uploadPath'] . $filename;
        $stepLimit = (int)$this->modx->getOption('msimportexport.import.step_limit', null, 50);
        $key = $this->modx->getOption('msimportexport.key', null, 'article');
        $isDebug = filter_var($this->modx->getOption('msimportexport.debug', null, 0), FILTER_VALIDATE_BOOLEAN);
        $ignoreFirstLine = filter_var($this->modx->getOption('msimportexport.ignore_first_line', null, false), FILTER_VALIDATE_BOOLEAN);
        $this->modx->setLogLevel($isDebug ? modX::LOG_LEVEL_INFO : modX::LOG_LEVEL_ERROR);
        $this->modx->log(modX::LOG_LEVEL_INFO, 'max_execution_time:' . ini_get('max_execution_time'));
        $this->modx->log(modX::LOG_LEVEL_INFO, 'memory_limit:' . ini_get('memory_limit'));

        if (!$fields = $this->getPresetFields($preset)) {
            $out['errors']++;
            return $out;
        }

        if (!$reader = $this->getReader($file)) {
            $out['errors']++;
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err.reader'));
            return $out;
        }

        $this->modx->log(modX::LOG_LEVEL_INFO, sprintf($this->modx->lexicon('msimportexport.preset_use'), $preset));

        $i = 0;
        $ignoreFirstLine = $ignoreFirstLine && empty($seek);
        $reader->read(array(
            'file' => $file,
            'seek' => $seek,
        ), function ($reader, $csv) use (&$i, &$out, $fields, $key, $ignoreFirstLine, $stepLimit, $isDebug) {
            $i++;
            $out['rows']++;
            $this->modx->error->reset();
            if ($ignoreFirstLine && $i == 1) {
                return true;
            }

            $data = array();
            $remainData = array();
            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('msimportexport.raw_data') . "\n" . print_r($csv, 1));
            foreach ($fields as $k => $v) {

                /*if (!isset($csv[$k])) {
                    $out['errors']++;
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $this->parseString($this->modx->lexicon('msimportexport.err_field'), array('field' => $v, 'index' => $out['rows'])));
                    return $out;
                }*/

                if ($v != 'remain') {
                    $data[$v] = $csv[$k];
                } else {
                    $remainData['remain'] = $csv[$k] ? $csv[$k] : 0;
                }
            }

            $remainData['options'] = $data;
            if (isset($remainData['options'][$key])) {
                unset($remainData['options'][$key]);
            }
            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('msimportexport.importing_remain_data') . "\n" . print_r($remainData, 1));

            // Set default values
            if (empty($data['class_key'])) {
                $data['class_key'] = 'msProduct';
            }

            $q = $this->modx->newQuery($data['class_key']);
            $q->select($data['class_key'] . '.id');
            if (strtolower($data['class_key']) == 'msproduct') {
                $q->innerJoin('msProductData', 'Data', $data['class_key'] . '.id = Data.id');
                $isProduct = true;
            }
            $tmp = $this->modx->getFields($data['class_key']);
            if (isset($tmp[$key])) {
                $q->where(array($key => $data[$key]));
            } elseif ($isProduct) {
                $q->where(array('Data.' . $key => $data[$key]));
            }
            $q->prepare();
            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('msimportexport.find_product') . "\n" . $q->toSql());
            /** @var modResource $exists */
            if ($exists = $this->modx->getObject($data['class_key'], $q)) {
                $remainData['product_id'] = $exists->id;
                $hashKey = $this->createHashRemain($exists->id, $remainData['options']);
                $remainId = $this->getRemainIdByHash($hashKey, $exists->id);
                $action = $remainId ? 'update' : 'create';
                if ($this->setRemain($remainData, $remainId)) {
                    $out[$action]++;
                    $this->modx->log(modX::LOG_LEVEL_INFO, "Successful  set remain: \n" . print_r($remainData, 1) . ' remain ID ' . $remainId);
                } else {
                    $out['errors']++;
                    $this->modx->log(modX::LOG_LEVEL_INFO, "Error  set remain: \n" . print_r($remainData, 1) . ' remain ID ' . $remainId);
                }

            } else {

            }
            if ($isDebug) {
                $timeEnd = number_format(microtime(true) - $this->modx->startTime, 7);
                $this->modx->log(modX::LOG_LEVEL_INFO, $this->parseString($this->modx->lexicon('msimportexport.debug_mode'), array('time' => $timeEnd)));
                return false;
            }

            $seek = $reader->getSeek();
            if ($i >= $stepLimit || $seek < 0) {
                return false;
            } else {
                return true;
            }
        });

        $out['seek'] = $reader->getSeek();
        return $out;
    }

    /**
     * @param string $filename
     * @param int $preset
     * @param int $seek
     * @return array
     */
    private function importLinks($filename = '', $preset, $seek = 0)
    {
        $corePathMiniShop2 = $this->modx->getOption('minishop2.core_path', null, $this->modx->getOption('core_path') . 'components/minishop2/');

        $out = array(
            'errors' => 0,
            'update' => 0,
            'seek' => 0,
            'rows' => 0,
        );
        $file = $this->config['uploadPath'] . $filename;
        $stepLimit = (int)$this->modx->getOption('msimportexport.import.step_limit', null, 50);
        $key = $this->modx->getOption('msimportexport.key', null, 'article');
        $isDebug = filter_var($this->modx->getOption('msimportexport.debug', null, 0), FILTER_VALIDATE_BOOLEAN);
        $ignoreFirstLine = filter_var($this->modx->getOption('msimportexport.ignore_first_line', null, false), FILTER_VALIDATE_BOOLEAN);

        $this->modx->setLogLevel($isDebug ? modX::LOG_LEVEL_INFO : modX::LOG_LEVEL_ERROR);
        $this->modx->log(modX::LOG_LEVEL_INFO, 'max_execution_time:' . ini_get('max_execution_time'));
        $this->modx->log(modX::LOG_LEVEL_INFO, 'memory_limit:' . ini_get('memory_limit'));

        if (!$fields = $this->getPresetFields($preset)) {
            $out['errors']++;
            return $out;
        }

        if (!$reader = $this->getReader($file)) {
            $out['errors']++;
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err.reader'));
            return $out;
        }

        $i = 0;
        $ignoreFirstLine = $ignoreFirstLine && empty($seek);
        $reader->read(array(
            'file' => $file,
            'seek' => $seek,
        ), function ($reader, $csv) use (&$i, &$out, $fields, $key, $ignoreFirstLine, $stepLimit, $corePathMiniShop2, $isDebug) {
            $i++;
            $out['rows']++;
            $this->modx->error->reset();
            if ($ignoreFirstLine && $i == 1) {
                return true;
            }

            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('msimportexport.raw_data') . "\n" . print_r($csv, 1));
            $continue = false;
            foreach ($fields as $k => $v) {

                if (!isset($csv[$k])) {
                    $out['errors']++;
                    $continue = true;
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $this->parseString($this->modx->lexicon('msimportexport.err_field'), array('field' => $v, 'index' => $out['rows'])));
                    continue;
                }
                $data[$v] = $csv[$k];
            }
            if ($continue) return true;
            // Set default values
            if (empty($data['class_key'])) {
                $data['class_key'] = 'msProduct';
            }

            $q = $this->modx->newQuery($data['class_key']);
            $q->select($data['class_key'] . '.id');
            if (strtolower($data['class_key']) == 'msproduct') {
                $q->innerJoin('msProductData', 'Data', $data['class_key'] . '.id = Data.id');
                $isProduct = true;
            }
            $tmp = $this->modx->getFields($data['class_key']);
            if (isset($tmp[$key])) {
                $q->where(array($key => $data[$key]));
            } elseif ($isProduct) {
                $q->where(array('Data.' . $key => $data[$key]));
                unset($data[$key]);
            }
            $q->prepare();
            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('msimportexport.find_product') . "\n" . $q->toSql());
            /** @var modResource $exists */
            if ($exists = $this->modx->getObject($data['class_key'], $q)) {
                $data['master'] = $exists->id;
                $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('msimportexport.import.link_data') . "\n" . print_r($data, 1));
                $response = $this->modx->runProcessor('mgr/product/productlink/create', $data, array('processors_path' => $corePathMiniShop2 . 'processors/'));
                if ($response->isError()) {
                    $out['errors']++;
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err_import_link') . "\n" . print_r($response->getAllErrors(), 1));
                } else {
                    $out['update']++;
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err_nf_product') . "\n" . $q->toSql());
                $out['errors']++;
            }
            if ($isDebug) {
                $timeEnd = number_format(microtime(true) - $this->modx->startTime, 7);
                $this->modx->log(modX::LOG_LEVEL_INFO, $this->parseString($this->modx->lexicon('msimportexport.debug_mode'), array('time' => $timeEnd)));
                return false;
            }
            $seek = $reader->getSeek();
            if ($i >= $stepLimit || $seek < 0) {
                return false;
            } else {
                return true;
            }
        });

        $out['seek'] = $reader->getSeek();
        return $out;
    }

    /**
     * @param string $filename
     * @param int $seek
     * @return array
     */
    private function importCategories($filename = '', $seek = 0)
    {
        $out = array(
            'errors' => 0,
            'create' => 0,
            'update' => 0,
            'seek' => 0,
            'rows' => 0,
        );
        $file = $this->config['uploadPath'] . $filename;
        $stepLimit = (int)$this->modx->getOption('msimportexport.import.step_limit', null, 50);
        $delimeter = $this->modx->getOption('msimportexport.delimeter', null, ';');
        $subDelimeter = $this->modx->getOption('msimportexport.import.sub_delimeter', null, $delimeter);
        $isDebug = filter_var($this->modx->getOption('msimportexport.debug', null, 0), FILTER_VALIDATE_BOOLEAN);
        $ignoreFirstLine = filter_var($this->modx->getOption('msimportexport.ignore_first_line', null, false), FILTER_VALIDATE_BOOLEAN);
        $this->modx->setLogLevel($isDebug ? modX::LOG_LEVEL_INFO : modX::LOG_LEVEL_ERROR);

        $this->modx->log(modX::LOG_LEVEL_INFO, 'max_execution_time:' . ini_get('max_execution_time'));
        $this->modx->log(modX::LOG_LEVEL_INFO, 'memory_limit:' . ini_get('memory_limit'));

        if (!$reader = $this->getReader($file)) {
            $out['errors']++;
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err.reader'));
            return $out;
        }

        $i = 0;
        $ignoreFirstLine = $ignoreFirstLine && empty($seek);
        $reader->read(array(
            'file' => $file,
            'seek' => $seek,
        ), function ($reader, $csv) use (&$i, &$out, $subDelimeter, $stepLimit, $ignoreFirstLine, $isDebug) {
            $i++;
            $out['rows']++;
            $this->modx->error->reset();
            if ($ignoreFirstLine && $i == 1) {
                return true;
            }

            $categories = array();
            if (isset($csv[1])) {
                $categories = array_map('trim', explode($subDelimeter, $csv[1]));
            }

            if (!$categoryId = $this->getCategoryIdByName($csv[0], true, false)) {
                if ($categoryId = $this->createCategory($csv[0], 0)) $out['create']++;

            }

            if (empty($categoryId)) {
                $out['errors']++;
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[importCategories] error create category ' . $csv[0]);
                return $out;
            }

            if (!empty($categories)) {
                foreach ($categories as $k => $v) {
                    if (!$this->checkSubCategoryByName($v, $categoryId)) {
                        if (!$subCategoryId = $this->createCategory($v, $categoryId)) {
                            $out['errors']++;
                            $this->modx->log(modX::LOG_LEVEL_ERROR, '[importCategories] error create sub category ' . $v . ' for category' . $csv[0]);
                            return $out;
                        } else {
                            $out['create']++;
                            $this->categoryIds[$subCategoryId] = $v;
                        }
                    }
                }
            }

            if ($isDebug) {
                $timeEnd = number_format(microtime(true) - $this->modx->startTime, 7);
                $this->modx->log(modX::LOG_LEVEL_INFO, $this->parseString($this->modx->lexicon('msimportexport.debug_mode'), array('time' => $timeEnd)));
                return false;
            }

            $seek = $reader->getSeek();
            if ($i >= $stepLimit || $seek < 0) {
                return false;
            } else {
                return true;
            }
        });

        $out['seek'] = $reader->getSeek();
        return $out;
    }

    /**
     * @param string $filename
     * @param int $preset
     * @param int $seek
     * @return array
     */

    private function importProducts($filename = '', $preset, $seek = 0)
    {
        $out = array(
            'errors' => 0,
            'create' => 0,
            'update' => 0,
            'seek' => 0,
            'rows' => 0,
        );

        $ids = array();
        $vendorIds = array();
        $tvEnabled = false;
        $file = $this->config['uploadPath'] . $filename;
        $stepLimit = (int)$this->modx->getOption('msimportexport.import.step_limit', null, 50);
        $delimeter = $this->modx->getOption('msimportexport.delimeter', null, ';');
        $subDelimeter = $this->modx->getOption('msimportexport.import.sub_delimeter', null, $delimeter);
        $key = $this->modx->getOption('msimportexport.key', null, 'article');
        $idParentNewProduct = $this->modx->getOption('msimportexport.import.id_parent_new_product', null, 0);
        $isDebug = filter_var($this->modx->getOption('msimportexport.debug', null, 0), FILTER_VALIDATE_BOOLEAN);
        $isSkipEmptyParent = filter_var($this->modx->getOption('msimportexport.skip_empty_parent', null, 1), FILTER_VALIDATE_BOOLEAN);
        $isCreateParent = filter_var($this->modx->getOption('msimportexport.create_parent', null, 1), FILTER_VALIDATE_BOOLEAN);
        $postActionParams = trim($this->modx->getOption('msimportexport.post_action', null, ''));
        $ignoreFirstLine = filter_var($this->modx->getOption('msimportexport.ignore_first_line', null, false), FILTER_VALIDATE_BOOLEAN);
        $ms2Gallery = null;

        if (file_exists(MODX_CORE_PATH . 'components/ms2gallery')) {
            $ms2Gallery = $this->modx->getService('ms2gallery', 'ms2Gallery', MODX_CORE_PATH . 'components/ms2gallery/model/ms2gallery/');
        }

        $this->modx->setLogLevel($isDebug ? modX::LOG_LEVEL_INFO : modX::LOG_LEVEL_ERROR);
        $this->modx->log(modX::LOG_LEVEL_INFO, 'max_execution_time:' . ini_get('max_execution_time'));
        $this->modx->log(modX::LOG_LEVEL_INFO, 'memory_limit:' . ini_get('memory_limit'));
        $this->modx->log(modX::LOG_LEVEL_INFO, sprintf($this->modx->lexicon('msimportexport.preset_use'), $preset));

        if (!$fields = $this->getPresetFields($preset)) {
            $out['errors']++;
            return $out;
        }


        if (!$reader = $this->getReader($file)) {
            $out['errors']++;
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err.reader'));
            return $out;
        }

        foreach ($fields as $v) {
            if (preg_match('/^tv(\d+)$/', $v)) {
                $tvEnabled = true;
                break;
            }
        }

        $i = 0;
        $ignoreFirstLine = $ignoreFirstLine && empty($seek);

        $reader->read(array(
            'file' => $file,
            'seek' => $seek,
        ), function ($reader, $csv) use (&$i, &$out, $fields, $stepLimit, $isDebug, $idParentNewProduct, $vendorIds, $subDelimeter, $tvEnabled, $key, $isSkipEmptyParent, $isCreateParent, $ms2Gallery, $ignoreFirstLine) {
            $i++;
            $out['rows']++;
            $this->modx->error->reset();
            if ($ignoreFirstLine && $i == 1) {
                return true;
            }

            //  $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($csv, 1));

            $data = array();
            $gallery = array();
            $parentOwn = '';
            $continue = false;
            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('msimportexport.raw_data') . "\n" . print_r($csv, 1));
            foreach ($fields as $k => $v) {

                /*if (!isset($csv[$k])) {
                    $out['errors']++;
                    $continue = true;
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $this->parseString($this->modx->lexicon('msimportexport.err_field'), array('field' => $v, 'index' => $out['rows'])));
                    continue;
                }*/

                if ($v == 'gallery') {
                    $val = array_map('trim', explode($subDelimeter, $csv[$k]));
                    $gallery = array_merge($gallery, $val);
                } elseif ($v == 'vendor') {
                    $vendorId = $csv[$k];
                    if (!is_numeric($csv[$k])) {
                        if (empty($vendorIds[$csv[$k]])) {
                            if ($vendorId = $this->getVendorIdByName($csv[$k])) {
                                $vendorIds[$csv[$k]] = $vendorId;
                            } else {
                                $vendorId = $this->addVendor($csv[$k]);
                            }
                        } else {
                            $vendorId = $vendorIds[$csv[$k]];
                        }
                    }
                    if ($vendorId) {
                        $data[$v] = $vendorId;
                    }
                } elseif ($v == 'parent') {
                    $parentOwn = trim($csv[$k]);
                    if (!is_numeric($csv[$k])) {
                        if ($parent = $this->prepareParentProduct($csv[$k])) {
                            $data[$v] = $parent;
                        } else {
                            // $data[$v] = $this->getRootCategoryId();
                            //$this->modx->log(modX::LOG_LEVEL_ERROR, $this->parseString($this->modx->lexicon('msimportexport.err.find_parent'), array('pagetitle' => $csv[$k], 'parent' => $data[$v], 'index' => $out['rows'])));
                        }
                    } else {
                        $data[$v] = trim($csv[$k]);
                    }
                } else if ($this->hasFieldArrayProduct($v)) {
                    if (!isset($data[$v])) {
                        $data[$v] = array();
                    }
                    $val = array_map('trim', explode($subDelimeter, $csv[$k]));
                    $data[$v] = array_merge($data[$v], $val);
                } else {
                    $data[$v] = trim($csv[$k]);
                }
            }
            //--------------------------------------------------------------
            $isProduct = false;
            if ($continue) return true;

            // Set default values
            if (empty($data['class_key'])) {
                $data['class_key'] = 'msProduct';
            }

            $parent = null;
            if (empty($data['context_key'])) {
                if (isset($data['parent']) && $parent = $this->modx->getObject('modResource', $data['parent'])) {
                    $data['context_key'] = $parent->get('context_key');
                } elseif (isset($this->modx->resource) && isset($this->modx->context)) {
                    $data['context_key'] = $this->modx->context->key;
                } else {
                    $data['context_key'] = 'web';
                }
            }
            $data['tvs'] = $tvEnabled;

            // Duplicate check
            $q = $this->modx->newQuery($data['class_key']);
            $q->select($data['class_key'] . '.id');
            if (strtolower($data['class_key']) == 'msproduct') {
                $q->innerJoin('msProductData', 'Data', $data['class_key'] . '.id = Data.id');
                $isProduct = true;
            }
            $tmp = $this->modx->getFields($data['class_key']);
            if (isset($tmp[$key])) {
                $q->where(array($key => $data[$key]));
            } elseif ($isProduct) {
                $q->where(array('Data.' . $key => $data[$key]));
            }
            $q->prepare();
            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('msimportexport.check_duplicate') . "\n" . $q->toSql());

            /** @var modResource $exists */
            if ($exists = $this->modx->getObject($data['class_key'], $q)) {
                $this->modx->log(modX::LOG_LEVEL_INFO, $this->parseString($this->modx->lexicon('msimportexport.key_duplicate'), array('key1' => $key, 'key2' => $data[$key])));
                $action = 'update';
                if (!isset($data['pagetitle'])) {
                    $data['pagetitle'] = $exists->get('pagetitle');
                }
                if (!isset($data['parent'])) {
                    $data['parent'] = $exists->get('parent');
                }
                $data['id'] = $exists->id;
            } else {
                if (!isset($data['parent']) && !empty($idParentNewProduct)) {
                    $data['parent'] = $idParentNewProduct;
                }
                $action = 'create';
            }

            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('msimportexport.importing_data') . "\n" . print_r($data, 1));

            if (empty($data['parent']) && empty($parentOwn) && $action == 'create' && $isSkipEmptyParent) {
                $out['errors']++;
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err_parent') . print_r($data, 1));
                return true;
            } else if (empty($data['parent']) && !$isSkipEmptyParent) {
                $data['parent'] = $this->getRootCategoryId();
            }

            if ($action == 'create' && empty($data['parent']) && !empty($parentOwn) && $isCreateParent && ($parent == null || !$this->hasParent($data['parent']))) {
                if (!$data['parent'] = $this->createCategory($parentOwn)) {
                    $out['errors']++;
                    return true;
                }
            }

            $res = $this->invokeEvent('msieOnBeforeImportProduct', array(
                'mode' => $action,
                'srcData' => $csv,
                'destData' => $data,
            ));

            if (!$res['success']) {
                $out['errors']++;
                $this->modx->log(modX::LOG_LEVEL_ERROR, $res['message']);
                return true;
            }

            $data = $res['data']['destData'];

            // Create or update resource
            /** @var modProcessorResponse $response */
            $response = $this->modx->runProcessor('resource/' . $action, $data);
            if ($response->isError()) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('msimportexport.err_action') . " $action: \n" . print_r($response->getAllErrors(), 1));
            } else {
                $out[$action]++;
                $resource = $response->getObject();
                $this->invokeEvent('msieOnAfterImportProduct', array(
                    'mode' => $action,
                    'data' => $resource,
                ));
                $ids[] = $resource['id'];
                if (isset($data['categories']) && !empty($data['categories'])) {
                    $this->addProductToSubCategories($resource['id'], $data['categories'], $resource['parent']);
                }
                $this->modx->log(modX::LOG_LEVEL_INFO, "Successful $action: \n" . print_r($resource, 1));
                // Process gallery images, if exists
                if (!empty($gallery)) {
                    $this->modx->log(modX::LOG_LEVEL_INFO, "Importing images: \n" . print_r($gallery, 1));
                    if ($ms2Gallery) {
                        $res = $exists ? $exists : $this->modx->getObject('modResource', $resource['id']);
                        if (!$res->getProperties('ms2gallery')) {
                            $ms2Gallery = null;
                        }
                    }

                    foreach ($gallery as $v) {
                        $this->addImageGallery($resource['id'], $v, $ms2Gallery);
                    }
                }
            }

            unset($exists);
            unset($response);
            unset($data);

            if ($isDebug) {
                $timeEnd = number_format(microtime(true) - $this->modx->startTime, 7);
                $this->modx->log(modX::LOG_LEVEL_INFO, $this->parseString($this->modx->lexicon('msimportexport.debug_mode'), array('time' => $timeEnd)));
                return false;
            }

            $seek = $reader->getSeek();
            if ($i >= $stepLimit || $seek < 0) {
                return false;
            } else {
                return true;
            }
            //----------------------------------------------------------------
        });

        $out['seek'] = $reader->getSeek();

        if (!empty($ids) && !empty($postActionParams)) {
            $this->postAction($ids, $postActionParams);
        }

        unset($ids);
        unset($fields);
        unset($vendorIds);
        unset($ms2Gallery);

        return $out;
    }


    /**
     * Shorthand for original modX::invokeEvent() method with some useful additions.
     *
     * @param $eventName
     * @param array $params
     * @param $glue
     *
     * @return array
     */
    public function invokeEvent($eventName, array $params = array(), $glue = '<br/>')
    {
        if (isset($this->modx->event->returnedValues)) {
            $this->modx->event->returnedValues = null;
        }

        $response = $this->modx->invokeEvent($eventName, $params);
        if (is_array($response) && count($response) > 1) {
            foreach ($response as $k => $v) {
                if (empty($v)) {
                    unset($response[$k]);
                }
            }
        }

        $message = is_array($response) ? implode($glue, $response) : trim((string)$response);
        if (isset($this->modx->event->returnedValues) && is_array($this->modx->event->returnedValues)) {
            $params = array_merge($params, $this->modx->event->returnedValues);
        }

        return array(
            'success' => empty($message),
            'message' => $message,
            'data' => $params,
        );
    }

    /**
     * @param  string $parent
     * @return array|bool|int
     */
    private function prepareParentProduct($parent)
    {
        $subDelimeter = $this->modx->getOption('msimportexport.import.sub_delimeter', null, '|');
        $parent = array_map('trim', explode($subDelimeter, $parent));
        if (count($parent) == 1) {
            if ($parentId = $this->getIdByPegeTitle($parent[0])) {
                return $parentId;
            } else {
                return $this->createCategory($parent[0], $this->getRootCategoryId());
            }
        } else {
            return $this->createTreeCategories($parent);
        }
    }

    /**
     * @param int $resourceId
     * @param string $image
     * @param ms2Gallery|null $ms2Gallery
     * @return bool
     */
    private function addImageGallery($resourceId, $image = '', ms2Gallery &$ms2Gallery = null)
    {

        if (empty($image)) {
            return false;
        }
        if (preg_match('/^http|https:\/\//', $image)) {
            if (!$file = $this->downloadUrlToFile($image)) {
                return false;
            }
        } else {
            $file = str_replace('//', '/', MODX_BASE_PATH . $image);
        }
        if (!file_exists($file)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->parseString($this->modx->lexicon('msimportexport.err_import_image'), array('img' => $image, 'file' => $file)));
            return false;
        }
        $processorsPath = $ms2Gallery ? $ms2Gallery->config['corePath'] . 'processors/mgr/' : MODX_CORE_PATH . 'components/minishop2/processors/mgr/';

        $response = $this->modx->runProcessor('gallery/upload',
            array('id' => $resourceId, 'name' => $image, 'file' => $file),
            array('processors_path' => $processorsPath)
        );

        if ($response->isError()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->parseString($this->modx->lexicon('msimportexport.err.gallery_upload'), array('v' => $image)) . ": \n" . print_r($response->getAllErrors(), 1));
        } else {
            $this->modx->log(modX::LOG_LEVEL_INFO, $this->parseString($this->modx->lexicon('msimportexport.success.gallery_upload'), array('v' => $image)) . ": \n" . print_r($response->getObject(), 1));
            return true;
        }
    }

    /**
     * @param string $path
     * @return bool|string
     */
    public function loadImportFile($path)
    {
        if ($content = @file_get_contents($path)) {
            $name = 'import_cron_data.csv';
            $file = $this->config['uploadPath'] . $name;
            if (file_put_contents($file, $content)) {
                return $name;
            }
        }
        return false;
    }

    /**
     * @param string $url
     * @param string $path
     * @param bool $hashName
     * @return string
     */
    public function downloadUrlToFile($url, $path = '', $hashName = false)
    {
        if (empty($url)) return '';
        $path = $path ? $path : $this->config['uploadPath'];
        $name = preg_replace('/\?.*/', '', pathinfo($url, PATHINFO_BASENAME));
        $name = $hashName ? md5($name) . '.' . pathinfo($name, PATHINFO_EXTENSION) : $name;
        $filename = $path . mb_strtolower($name);
        if ($filedata = file_get_contents($url)) {
            if (!file_put_contents($filename, $filedata)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error save file for url ' . $url . ' to ' . $filename);
            } else {
                return $filename;
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error download file for url ' . $url);
        }
        return '';
    }

    /**
     * @param string $filename
     * @param int $preset
     * @param string $type products|pemains
     * @param bool $logCron
     * @param int $seek
     * @return array
     */
    public function import($filename = '', $preset, $type = 'products', $logCron = false, $seek = 0)
    {
        $out = array();
        $this->modx->cacheManager->refresh(array('system_settings' => array()));
        $this->modx->setLogLevel($logCron ? modX::LOG_LEVEL_INFO : modX::LOG_LEVEL_ERROR);
        if ($logCron) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'Cron start import ' . $type);
        }
        switch ($type) {
            case 'pemains':
                $out = $this->importRemain($filename, $preset, $seek);
                break;
            case 'links':
                $out = $this->importLinks($filename, $preset, $seek);
                break;
            case 'categories':
                $out = $this->importCategories($filename, $seek);
                break;
            default:
                $out = $this->importProducts($filename, $preset, $seek);
        }
        if ($logCron) {
            $this->modx->setLogLevel($logCron ? modX::LOG_LEVEL_INFO : modX::LOG_LEVEL_ERROR);
            $this->modx->log(modX::LOG_LEVEL_INFO, "Cron statistics import:\n" . print_r($out, 1));
            $this->modx->log(modX::LOG_LEVEL_INFO, 'Cron finish import ' . $type);
        }
        return $out;
    }


    /**
     * @param string $key
     * @param array $data
     * @return int|string
     */
    private function prepareValue($key, $data)
    {

        if (isset($data[$key . 'type'])) {
            switch ($data[$key . 'type']) {
                case 'combobox';
                case 'combo-multiple';
                case 'combo-options';
                    return $data[$key . 'value'];
                    break;
                case 'combo-boolean';
                    $v = $data[$key . 'value'];
                    if ($v == 'Yes' || $v = 'Да') {
                        return 1;
                    } else if ($v == 'No' || $v = 'Нет') {
                        return 0;
                    }
                    return $v;
                    break;
                case 'checkbox';
                    return filter_var($data[$key . 'value'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                    break;
            }
        } else {
            return $data[$key];
        }
    }

    /**
     * @param bool $useHeader
     * @param string $type
     * @param int $preset
     * @param string $categories
     * @return string
     */
    public function exportToCSV($useHeader = true, $type = 'products', $preset = 0, $categories = '')
    {
        if ($useHeader) {
            $filename = 'export_' . date('d_m_Y_H_i_s') . '.csv';
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        $timeLimit = $this->modx->getOption('msimportexport.time_limit', null, 600);
        $isDebug = filter_var($this->modx->getOption('msimportexport.export.debug', null, 0), FILTER_VALIDATE_BOOLEAN);
        $isHead = filter_var($this->modx->getOption('msimportexport.export.head', null, 0), FILTER_VALIDATE_BOOLEAN);
        $isAddHost = filter_var($this->modx->getOption('msimportexport.export.add_host', null, 1), FILTER_VALIDATE_BOOLEAN);
        $delimeter = $this->modx->getOption('msimportexport.export.delimeter', null, ';');
        $subDelimeter = $this->modx->getOption('msimportexport.export.sub_delimeter', null, $delimeter);
        $isConvertDate = filter_var($this->modx->getOption('msimportexport.export.convert_date', null, 0), FILTER_VALIDATE_BOOLEAN);
        $formatDate = $this->modx->getOption('msimportexport.export.format_date', null, '%m/%d/%Y %T');
        $this->modx->setLogLevel($isDebug ? modX::LOG_LEVEL_INFO : modX::LOG_LEVEL_ERROR);
        $dateFields = $this->getDateFields();
        if ($type != 'categories' && $type != 'links') {
            if (!$fields = $this->getPresetFields($preset)) {
                return '';
            } else {
                $this->modx->log(modX::LOG_LEVEL_INFO, sprintf($this->modx->lexicon('msimportexport.preset_use'), $preset));
            }
            $data = array();
            $data['limit'] = $this->modx->getOption('msimportexport.export.limit', null, 0);
            $data['depth'] = $this->modx->getOption('msimportexport.export.depth', null, 10);
            $data['parents'] = !empty($categories) ? $categories : $this->modx->getOption('msimportexport.export.parents', null, '');
            $data['where'] = $this->modx->getOption('msimportexport.export.where', null, '');
            $data['fields'] = $fields;
            $data['isCategories'] = array_search('categories', $fields) !== false ? true : false;
            $data['isGallery'] = in_array('gallery', $fields);
        }
        $data['isDebug'] = $isDebug;
        set_time_limit($timeLimit);
        $tmp = $this->parseString($this->modx->lexicon('msimportexport.log.time_limit'), array('limit' => $timeLimit));
        $tmp .= ini_get('max_execution_time') >= $timeLimit ? 'done' : 'error';
        $this->modx->log(modX::LOG_LEVEL_INFO, $tmp);
        $out = '';

        if ($isHead) {
            $out = implode($delimeter, $this->prepareHeadFields($fields)) . "\n";
        }
        switch ($type) {
            case 'products':
                if ($products = $this->getProducts($data)) {
                    foreach ($products as $product) {
                        $row = array();
                        foreach ($fields as $v) {
                            if (isset($product[$v])) {
                                if (is_array($product[$v])) {
                                    $row[] = implode($subDelimeter, array_diff($product[$v], array('')));
                                } else {
                                    if ($isConvertDate && isset($dateFields[$v])) {
                                        $product[$v] = strftime($formatDate, $product[$v]);
                                    }
                                    if ($isAddHost && $this->isImageField($product[$v])) {
                                        $product[$v] = $this->siteUrl . $product[$v];
                                    }
                                    $row[] = $product[$v];
                                }
                            } else {
                                $row[] = '';
                            }
                        }
                        if (!empty($row)) {
                            $out .= implode($delimeter, $row) . "\n";
                        }
                    }
                }
                break;
            case 'links':
                $fields = array('link', 'master', 'slave');
                $data['fields'] = $fields;
                if ($links = $this->getLinks($data)) {
                    foreach ($links as $link) {
                        $row = array();
                        foreach ($fields as $v) {
                            if (isset($link[$v])) {
                                $row[] = $link[$v];
                            } else {
                                $row[] = '';
                            }
                        }
                        if (!empty($row)) {
                            $out .= implode($delimeter, $row) . "\n";
                        }
                    }
                }
                break;
            case 'categories':
                if ($categories = $this->getTreeCategories()) {
                    foreach ($categories as $k => $v) {
                        $out .= $k . $delimeter . implode($subDelimeter, $v) . "\n";
                    }
                }
                break;
        }
        return $out;
    }


    /**
     * @param bool $useHeader
     * @param string $ext
     * @param string $type
     * @param int $preset
     * @param string $categories
     */
    public function exportToXLS($useHeader = true, $ext = 'xlsx', $type = 'products', $preset = 0, $categories = '')
    {
        require_once(dirname(__FILE__) . '/lib/PHPExcel/PHPExcel.php');
        require_once(dirname(__FILE__) . '/lib/PHPExcel/PHPExcel/IOFactory.php');

        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize ' => '256MB');
        //PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip);

        $data = array();
        $date = date('d-m-Y H:i:s');
        $filename = 'export_' . date('d_m_Y_H_i_s') . '.' . $ext;
        $timeLimit = $this->modx->getOption('msimportexport.time_limit', null, 600);
        $delimeter = $this->modx->getOption('msimportexport.export.delimeter', null, ';');
        $subDelimeter = $this->modx->getOption('msimportexport.export.sub_delimeter', null, $delimeter);
        $isDebug = filter_var($this->modx->getOption('msimportexport.export.debug', null, 0), FILTER_VALIDATE_BOOLEAN);
        $isHead = filter_var($this->modx->getOption('msimportexport.export.head', null, 0), FILTER_VALIDATE_BOOLEAN);
        $isConvertDate = filter_var($this->modx->getOption('msimportexport.export.convert_date', null, 0), FILTER_VALIDATE_BOOLEAN);
        $isAddHost = filter_var($this->modx->getOption('msimportexport.export.add_host', null, 1), FILTER_VALIDATE_BOOLEAN);
        $formatDate = $this->modx->getOption('msimportexport.export.format_date', null, '%m/%d/%Y %T');
        $isFormatData = filter_var($this->modx->getOption('msimportexport.export.excel_format_data', null, 0), FILTER_VALIDATE_BOOLEAN);
        $isInsertImg = filter_var($this->modx->getOption('msimportexport.export.excel_insert_img', null, 0), FILTER_VALIDATE_BOOLEAN);
        $heightImg = $this->modx->getOption('msimportexport.export.excel_height_img', null, 50);
        $importKey = $this->modx->getOption('msimportexport.key', null, 'article');
        $cellWidthImg = $heightImg / 37.936267 + 0.5;
        $this->modx->setLogLevel($isDebug ? modX::LOG_LEVEL_INFO : modX::LOG_LEVEL_ERROR);
        if ($type != 'categories' && $type != 'links') {
            if (!$fields = $this->getPresetFields($preset)) {
                return '';
            } else {
                $this->modx->log(modX::LOG_LEVEL_INFO, sprintf($this->modx->lexicon('msimportexport.preset_use'), $preset));
            }

            $data['limit'] = $this->modx->getOption('msimportexport.export.limit', null, 0);
            $data['depth'] = $this->modx->getOption('msimportexport.export.depth', null, 10);
            $data['parents'] = !empty($categories) ? $categories : $this->modx->getOption('msimportexport.export.parents', null, '');
            $data['where'] = $this->modx->getOption('msimportexport.export.where', null, '');
            $data['fields'] = $fields;
            $data['isCategories'] = array_search('categories', $fields) !== false ? true : false;
            $data['isGallery'] = in_array('gallery', $fields);
            $this->modx->log(modX::LOG_LEVEL_INFO, sprintf($this->modx->lexicon('msimportexport.preset_use'), $preset));
        }
        $data['isDebug'] = $isDebug;
        $dateFields = $this->getDateFields();
        set_time_limit($timeLimit);
        $tmp = $this->parseString($this->modx->lexicon('msimportexport.log.time_limit'), array('limit' => $timeLimit));
        $tmp .= ini_get('max_execution_time') >= $timeLimit ? 'done' : 'error';
        $this->modx->log(modX::LOG_LEVEL_INFO, $tmp);

        $sheetIndex = 0;
        $index = 0;

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle('miniShop2 export ' . $date)->setSubject('miniShop2 export ' . $date);
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_STYLE_ALIGNMENT::VERTICAL_TOP);
        $objPHPExcel->setActiveSheetIndex($sheetIndex);
        $objActiveSheet = $objPHPExcel->getActiveSheet();
        $objActiveSheet->setTitle('Export');


        $headStyle = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
            )
        , 'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'CFCFCF'
                )
            ));
        switch ($type) {
            case 'products':
                $items = $this->getProducts($data);
                break;
            case 'links':
                $fields = array('link', 'master', 'slave');
                $data['fields'] = $fields;
                $items = $this->getLinks($data);
                break;
        }

        if ($items) {
            foreach ($items as $i => $item) {

                if ($isHead && $index == 0) {
                    $headFields = $this->prepareHeadFields($fields);
                    foreach ($headFields as $k => $v) {
                        $objActiveSheet->setCellValueByColumnAndRow($k, 1, $v);
                        $objActiveSheet->getStyleByColumnAndRow($k, 1)->applyFromArray($headStyle);
                        $objActiveSheet->getColumnDimensionByColumn($k)->setAutoSize(true);
                    }
                    $index = 1;
                }

                $index++;

                foreach ($fields as $k => $v) {

                    $value = isset($item[$v]) ? $item[$v] : '';
                    if (is_array($value)) {
                        $value = implode($subDelimeter, array_diff($value, array('')));
                    }

                    if ($isConvertDate && isset($dateFields[$v])) {
                        $value = strftime($formatDate, $value);
                    }

                    if ($isInsertImg && $this->isImageField($value)) {
                        $imagePath = $image = str_replace('//', '/', MODX_BASE_PATH . $value);
                        if (file_exists($imagePath)) {
                            $objActiveSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($k))->setWidth($cellWidthImg);
                            $objActiveSheet->getRowDimension($index)->setRowHeight($heightImg);
                            $img = new PHPExcel_Worksheet_Drawing();
                            $cell = PHPExcel_Cell::stringFromColumnIndex($k) . $index;
                            /*$cell = $objActiveSheet->getCellByColumnAndRow($k, $index);*/
                            $img->setPath($imagePath);
                            $img->setOffsetX(0);
                            $img->setOffsetY(0);
                            $img->setHeight($heightImg);
                            $img->setCoordinates($cell);
                            //$img->setCoordinates($cell->getCoordinate());

                            $img->setWorksheet($objActiveSheet);
                        }
                    } else {
                        if ($isAddHost && $this->isImageField($value)) {
                            $value = $this->siteUrl . $value;
                        }

                        $сell = $objActiveSheet->setCellValueByColumnAndRow($k, $index, $value, true);

                        if ($v == $importKey) {
                            $сell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);
                        }

                        if ($isFormatData) {
                            if (is_float($value)) {
                                $objActiveSheet->getStyleByColumnAndRow($k, $index)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
                            } else if (is_numeric($value)) {
                                $objActiveSheet->getStyleByColumnAndRow($k, $index)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
                            } else if ($this->isDate($value)) {
                                $objActiveSheet->getStyleByColumnAndRow($k, $index)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME);
                            }
                        }
                        $objActiveSheet->getColumnDimensionByColumn($k)->setAutoSize(true);
                    }
                }
            }
        }


        PHPExcel_Shared_File::setUseUploadTempDirectory(true);
        if ($ext == 'xlsx') {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else if ($ext == 'xls') {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        }

        if ($useHeader) {
            if ($ext == 'xlsx') {
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            } else if ($ext == 'xls') {
                header('Content-Type: application/vnd.ms-excel');
            }
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objWriter->save('php://output');
        } else {
            $objWriter->save($this->config['uploadPath'] . $filename);
        }
    }

    /**
     * @param bool $useHeader
     * @param string $categories
     * @return string
     */
    public function exportToYmarket($useHeader = true, $categories = '')
    {
        if ($useHeader) {
            header('Content-Type: application/xml');
        }

        $timeLimit = $this->modx->getOption('msimportexport.time_limit', null, 600);
        $isDebug = filter_var($this->modx->getOption('msimportexport.export.debug', null, 0), FILTER_VALIDATE_BOOLEAN);
        $this->modx->setLogLevel($isDebug ? modX::LOG_LEVEL_INFO : modX::LOG_LEVEL_ERROR);
        $data = array();
        $data['limit'] = $this->modx->getOption('msimportexport.export.limit', null, 0);
        $data['depth'] = $this->modx->getOption('msimportexport.export.depth', null, 10);
        $data['parents'] = !empty($categories) ? $categories : $this->modx->getOption('msimportexport.export.parents', null, '');
        $data['where'] = $this->modx->getOption('msimportexport.export.where', null, '');
        $data['isDebug'] = $isDebug;
        $data['isCategories'] = false;

        set_time_limit($timeLimit);
        $tmp = $this->parseString($this->modx->lexicon('msimportexport.log.time_limit'), array('limit' => $timeLimit));
        $tmp .= ini_get('max_execution_time') >= $timeLimit ? 'done' : 'error';
        $this->modx->log(modX::LOG_LEVEL_INFO, $tmp);

        $ym = new YandexMarket($this, $data);
        return $ym->getYml();
    }


    /***
     * @param string $name
     * @return array
     */
    public function strOption2Arr($name)
    {
        $data = array();
        if ($name) {
            $paramsFields = array_map('trim', explode(',', $this->modx->getOption($name, null, '')));
            if (!empty($paramsFields)) {
                foreach ($paramsFields as $v) {
                    $arr = explode('=', $v);
                    $data[$arr[0]] = $arr[1];
                }
            }
        }
        return $data;
    }

    /***
     * @param array $data
     * @return string
     */
    public function arrOption2Str($data)
    {
        $str = '';
        if ($data && is_array($data)) {
            //  $data = array_diff($data, array(''));
            foreach ($data as $k => $v) {
                if (!empty($k)) {
                    $str .= $str ? ',' : '';
                    $str .= trim($k) . '=' . trim($v);
                }
            }
        }
        return $str;
    }

    /**
     * @return bool
     */
    public function clearUploadDir()
    {
        return $this->modx->cacheManager->deleteTree($this->config['uploadPath'], array('deleteTop' => false, 'skipDirs' => false, 'extensions' => array()));
    }

    /**
     * @param $value
     * @return bool
     */
    private function isDate($value)
    {
        $ts = false;
        if (!empty($value)) {
            $ts = strtotime($value);
        }
        if ($ts === false || empty($value)) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    private function getDateFields()
    {
        $out = array();
        if ($meta = array_merge($this->modx->getFieldMeta('msProductData'), $this->modx->getFieldMeta('modResource'))) {
            foreach ($meta as $key => $v) {
                if (($v['dbtype'] == 'int' || $v['dbtype'] == 'integer') && ($v['phptype'] == 'timestamp' || $v['phptype'] == 'datetime' || $v['phptype'] == 'date')) {
                    $out[$key] = $key;
                }
            }
        }
        return $out;
    }


    private function isImageField($val)
    {
        return preg_match('/^\/[\w\-\.\?\/+@&#;`~=%!]+(?:jpg|jpeg|png|gif)$/', trim($val));
    }

}