<?php
/**
 * @package msimportexport
 * @subpackage controllers
 */
require_once dirname(__FILE__) . '/model/msimportexport/msie.class.php';

class IndexManagerController extends modExtraManagerController {
    public static function getDefaultController() { return 'index'; }
}


abstract class MsieMainController extends modExtraManagerController {
    /** @var Msie $msie */
    public $msie;
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        $action = call_user_func(array($className,'getDefaultController'));
        if (isset($_REQUEST['a'])) {
            $action = str_replace(array('../','./','.','-','@'),'',$_REQUEST['a']);
        }
        $className = self::getControllerClassName($action,$config['namespace']);
        $classPath = $config['namespace_path'].'controllers/default/'.$action.'.class.php';
        require_once $classPath;
        /** @var modManagerController $controller */
        $controller = new $className($modx,$config);
        return $controller;
    }
    public function initialize() {
        $this->msie = new Msie($this->modx);
        $this->addJavascript($this->msie->config['jsUrl'].'mgr/msie.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Msie.config = '.$this->modx->toJSON($this->msie->config).';
        });
        </script>');
        return parent::initialize();
    }

    public function getLanguageTopics() {
        return array('msimportexport:default');
    }

    public function checkPermissions() { return true;}
}