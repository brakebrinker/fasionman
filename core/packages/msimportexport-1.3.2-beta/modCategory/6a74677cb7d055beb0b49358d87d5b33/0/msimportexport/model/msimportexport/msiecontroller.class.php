<?php
/**
 * @package msimportexport
 * @subpackage request
 */
abstract class MsieController {
    /** @var modX $modx */
    public $modx;
    /** @var Msie $msie */
    public $msie;
    /** @var array $config */
    public $config = array();
    /** @var array $scriptProperties */
    protected $scriptProperties = array();
    /** @var MsieValidator $validator */
    public $validator;
    /** @var MsieDictionary $dictionary */
    public $dictionary;
    /** @var array $placeholders */
    protected $placeholders = array();

    /**
     * @param Msie $msie A reference to the Msie instance
     * @param array $config
     */
    function __construct(Msie &$msie,array $config = array()) {
        $this->msie =& $msie;
        $this->modx =& $msie->modx;
        $this->config = array_merge($this->config,$config);
    }

    public function run($scriptProperties) {
        $this->setProperties($scriptProperties);
        $this->initialize();
        return $this->process();
    }

    abstract public function initialize();
    abstract public function process();

    /**
     * Set the default options for this module
     * @param array $defaults
     * @return void
     */
    protected function setDefaultProperties(array $defaults = array()) {
        $this->scriptProperties = array_merge($defaults,$this->scriptProperties);
    }

    /**
     * Set an option for this module
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setProperty($key,$value) {
        $this->scriptProperties[$key] = $value;
    }
    /**
     * Set an array of options
     * @param array $array
     * @return void
     */
    public function setProperties($array) {
        foreach ($array as $k => $v) {
            $this->setProperty($k,$v);
        }
    }

    /**
     * Return an array of REQUEST options
     * @return array
     */
    public function getProperties() {
        return $this->scriptProperties;
    }

    /**
     * @param $key
     * @param null $default
     * @param string $method
     * @return mixed
     */
    public function getProperty($key,$default = null,$method = '!empty') {
        $v = $default;
        switch ($method) {
            case 'empty':
            case '!empty':
                if (!empty($this->scriptProperties[$key])) {
                    $v = $this->scriptProperties[$key];
                }
                break;
            case 'isset':
            default:
                if (isset($this->scriptProperties[$key])) {
                    $v = $this->scriptProperties[$key];
                }
                break;
        }
        return $v;
    }

    public function setPlaceholder($k,$v) {
        $this->placeholders[$k] = $v;
    }
    public function getPlaceholder($k,$default = null) {
        return isset($this->placeholders[$k]) ? $this->placeholders[$k] : $default;
    }
    public function setPlaceholders($array) {
        foreach ($array as $k => $v) {
            $this->setPlaceholder($k,$v);
        }
    }
    public function getPlaceholders() {
        return $this->placeholders;
    }


    /**
     * Load the Dictionary class
     * @return MsieDictionary
     */
    public function loadDictionary() {
        $classPath = $this->getProperty('dictionaryClassPath',$this->msie->config['modelPath'].'msimportexport/');
        $className = $this->getProperty('dictionaryClassName','MsieDictionary');
        if ($this->modx->loadClass($className,$classPath,true,true)) {
            $this->dictionary = new MsieDictionary($this->msie);
            $this->dictionary->gather();
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Msie] Could not load MsieDictionary class from ');
        }
        return $this->dictionary;
    }

    /**
     * Loads the MsieValidator class.
     *
     * @access public
     * @param array $config An array of configuration parameters for the
     * MsieValidator class
     * @return MsieValidator An instance of the MsieValidator class.
     */
    public function loadValidator($config = array()) {
        if (!$this->modx->loadClass('MsieValidator',$this->config['modelPath'].'msimportexport/',true,true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Msie] Could not load Validator class.');
            return false;
        }
        $this->validator = new MsieValidator($this->msie,$config);
        return $this->validator;
    }


    /**
     * @param string $processor
     * @return mixed|string
     */
    public function runProcessor($processor) {
        $output = '';
        $processor = $this->loadProcessor($processor);
        if (empty($processor)) return $output;

        return $processor->process();
    }

    /**
     * @param $processor
     * @return bool|MsieProcessor
     */
    public function loadProcessor($processor) {
        $processor = strtolower($processor);
        $processorFile = $this->config['processorsPath'].$processor.'.php';
        if (!file_exists($processorFile)) {
            return false;
        }
        try {
            $explode = explode('/',$processor);
            $processor = $explode[(count($explode) -1)];
            $className = 'Msie'.ucfirst($processor).'Processor';
            if (!class_exists($className)) {
                $className = include_once $processorFile;
            }
            /** @var MsieProcessor $processor */
            $processor = new $className($this->msie,$this);
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Msie] '.$e->getMessage());
        }
        return $processor;
    }

}

/**
 * Abstracts processors into a class
 * @package msimportexport
 */
abstract class MsieProcessor {
    /** @var Msie $msie */
    public $msie;
    /** @var MsieController $controller */
    public $controller;
    /** @var MsieDictionary $dictionary */
    public $dictionary;
    /** @var array $config */
    public $config = array();

    /**
     * @param Msie &$msie A reference to the Msie instance
     * @param MsieController &$controller
     * @param array $config
     */
    function __construct(Msie &$msie,MsieController &$controller,array $config = array()) {
        $this->msie =& $msie;
        $this->modx =& $msie->modx;
        $this->controller =& $controller;
        $this->dictionary =& $controller->dictionary;
        $this->config = array_merge($this->config,$config);
    }

    abstract function process();
}