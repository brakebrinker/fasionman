<?php
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php';

$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

/** @var Msie $msie */
$msie = $modx->getService('msimportexport', 'Msie', $modx->getOption('msimportexport.core_path', null, $modx->getOption('core_path') . 'components/msimportexport/') . 'model/msimportexport/', array());

if(!$msie->checkValidityСatalog()) {
    header('Content-Type: text/html; charset=utf-8');
    echo $modx->lexicon('msimportexport.err_invalid_catalog');
    return;
}

if (isset($_GET['to']) && isset($_GET['token']) && isset($_GET['preset'])) {
    if ($msie->checkExportToken($_GET['token'])) {
        $h = isset($_GET['h']) ? filter_var($_GET['h'], FILTER_VALIDATE_BOOLEAN) : true;
        $type = isset($_GET['type']) ? $_GET['type'] : 'products';
        $categories = isset($_GET['categories']) ? $_GET['categories'] : '';
        $preset = isset($_GET['preset']) ? $_GET['preset'] : 0;
        switch ($_GET['to']) {
            case 'csv':
                echo $msie->exportToCSV($h, $type, $preset, $categories);
                break;
            case 'xls':
            case 'xlsx':
                echo $msie->exportToXLS($h, $_GET['to'], $type, $preset, $categories);
                break;
            case 'ym':
                echo $msie->exportToYmarket($h, $categories);
                break;
            default:
                echo 'Export format is not supported';
        }
    } else {
        echo 'Error incorrect token';
    }
} else {
    echo 'Error incorrect option';
}
@session_write_close();