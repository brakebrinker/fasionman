<?php

require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';
$msie = new Msie($this->modx);

$modx->lexicon->load('msimportexport:default');

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

if (isset($scriptProperties['depth'])) {
    $msie->setOption('msimportexport.export.depth',(int)$scriptProperties['depth']);
}

if (isset($scriptProperties['limit'])) {
    $msie->setOption('msimportexport.export.limit',(int)$scriptProperties['limit']);
}

if (isset($scriptProperties['debug'])) {
    $debug = filter_var($scriptProperties['debug'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.export.debug',$debug);
}

/**
 * CSV Файл
 */

if (!empty($scriptProperties['delimeter'])) {
    $msie->setOption('msimportexport.export.delimeter',$scriptProperties['delimeter']);
}

if (!empty($scriptProperties['sub_delimeter'])) {
    $msie->setOption('msimportexport.export.sub_delimeter',$scriptProperties['sub_delimeter']);
}

/**
 * Яндекс.Маркет
 */
if (isset($scriptProperties['ym_pickup_field'])) {
    $msie->setOption('msimportexport.export.ym.pickup_field',$scriptProperties['ym_pickup_field']);
}
if (isset($scriptProperties['ym_in_stock_field'])) {
    $msie->setOption('msimportexport.export.ym.in_stock_field',$scriptProperties['ym_in_stock_field']);
}

if (isset($scriptProperties['ym_delivery_field'])) {
    $msie->setOption('msimportexport.export.ym.delivery_field',$scriptProperties['ym_delivery_field']);
}

if (isset($scriptProperties['ym_currency_rate'])) {
    $msie->setOption('msimportexport.export.ym.currency_rate',$scriptProperties['ym_currency_rate']);
}

if (isset($scriptProperties['ym_default_currency'])) {
    $msie->setOption('msimportexport.export.ym.default_currency',(int)$scriptProperties['ym_default_currency']);
}

if (isset($scriptProperties['ym_currencies'])) {
    $msie->setOption('msimportexport.export.ym.currencies',$scriptProperties['ym_currencies']);
}

if (isset($scriptProperties['ym_company'])) {
    $msie->setOption('msimportexport.export.ym.company',$scriptProperties['ym_company']);
}

if (isset($scriptProperties['ym_name'])) {
    $msie->setOption('msimportexport.export.ym.name',$scriptProperties['ym_name']);
}

$preset = isset($scriptProperties['preset']) ? $scriptProperties['preset'] : 0;

if(empty($preset)) {
    return $modx->error->failure(sprintf($this->modx->lexicon('msimportexport.err.ns_preset'), $preset));
}


/*if($scriptProperties['format'] == 'csv') {
    $msie->exportToCSV($preset);
} else {
    $msie->exportToYmarket();
}*/

return $modx->error->success('',$result);

