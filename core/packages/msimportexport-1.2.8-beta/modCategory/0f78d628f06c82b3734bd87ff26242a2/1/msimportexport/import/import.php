<?php
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php';

$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

/** @var Msie $msie */
$msie = $modx->getService('msimportexport', 'Msie', $modx->getOption('msimportexport.core_path', null, $modx->getOption('core_path') . 'components/msimportexport/') . 'model/msimportexport/', array());

if (!$msie->checkValidityСatalog()) {
    echo $modx->lexicon('msimportexport.err_invalid_catalog');
    return;
}

if (isset($_GET['token']) && $msie->checkExportToken($_GET['token'])) {
    $path = !empty($_GET['path']) ? $_GET['path'] : $modx->getOption('msimportexport.import.cron_file_path', null, '');
    $type = !empty($_GET['type']) ? $_GET['type'] : '';
    $seek = !empty($_GET['seek']) ? $_GET['seek'] : 0;
    $preset = !empty($_GET['preset']) ? $_GET['preset'] : 0;

    $log = filter_var($modx->getOption('msimportexport.import.cron_log', null, 0), FILTER_VALIDATE_BOOLEAN);
    $utf8Encode = filter_var($modx->getOption('msimportexport.import.utf8_encode', null, 0), FILTER_VALIDATE_BOOLEAN);

    if (!$file = $msie->loadImportFile($path)) {
        echo $modx->log(modX::LOG_LEVEL_ERROR, sprintf($modx->lexicon('msimportexport.err_open_file'), $file));
        return;
    }
    if (empty($preset) && $type != 'categories') {
        echo $modx->log(modX::LOG_LEVEL_ERROR, sprintf($modx->lexicon('msimportexport.err.ns_preset'), $preset));
        return;
    }

    if (empty($seek)) {
        if ($ext == 'csv' && !empty($utf8Encode)) {
            $content = file_get_contents($file);
            $encoding = mb_detect_encoding($content, array('cp1251', 'utf-8', 'ASCII'));
            if (strtolower($encoding) != 'utf-8') {
                file_put_contents($file, iconv(strtolower($encoding), 'utf-8', $content));
            }
        }
    }

    $msie->import($file, $preset, $type, $log, $seek);

} else {
    echo $this->modx->log(modX::LOG_LEVEL_ERROR, 'Error incorrect token:' . $_GET['token']);
}
@session_write_close();