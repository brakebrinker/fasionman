<?php

require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';
$msie = new Msie($this->modx);

$modx->lexicon->load('msimportexport:default');

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

if (isset($scriptProperties['depth'])) {
    $msie->setOption('msimportexport.export.depth',(int)$scriptProperties['depth'],false);
}

if (isset($scriptProperties['limit'])) {
    $msie->setOption('msimportexport.export.limit',(int)$scriptProperties['limit'],false);
}

if (isset($scriptProperties['debug'])) {
    $debug = filter_var($scriptProperties['debug'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.export.debug',$debug,false);
}

if (isset($scriptProperties['where'])) {
    $msie->setOption('msimportexport.export.where',$scriptProperties['where'],false);
}


/**
 * CSV Файл
 */

if (!empty($scriptProperties['delimeter'])) {
    $msie->setOption('msimportexport.export.delimeter',$scriptProperties['delimeter'],false);
}

if (!empty($scriptProperties['sub_delimeter'])) {
    $msie->setOption('msimportexport.export.sub_delimeter',$scriptProperties['sub_delimeter'],false);
}

if (isset($scriptProperties['head'])) {
    $head = filter_var($scriptProperties['head'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.export.head',$head,false);
}

if (isset($scriptProperties['convert_date'])) {
    $convert_date = filter_var($scriptProperties['convert_date'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.export.convert_date',$convert_date,false);
}

if (isset($scriptProperties['format_date'])) {
    $msie->setOption('msimportexport.export.format_date',$scriptProperties['format_date'],false);
}

/**
 * Excel Файл
 */

if (isset($scriptProperties['excel_format_data'])) {
    $excel_format_data = filter_var($scriptProperties['excel_format_data'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.export.excel_format_data',$excel_format_data,false);
}

if (isset($scriptProperties['excel_insert_img'])) {
    $excel_insert_img = filter_var($scriptProperties['excel_insert_img'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.export.excel_insert_img',$excel_insert_img,false);
}

if (isset($scriptProperties['excel_height_img'])) {
    $msie->setOption('msimportexport.export.excel_height_img',$scriptProperties['excel_height_img'],false);
}


$fields = isset($scriptProperties['fields']) ? $scriptProperties['fields']  : array();
$preset = isset($scriptProperties['preset']) ? $scriptProperties['preset'] : 0;

if($fields && $preset) {
    $msie->setPresetFields($preset, $fields);
}

/**
 * Яндекс.Маркет
 */
if (isset($scriptProperties['ym_pickup_field'])) {
    $msie->setOption('msimportexport.export.ym.pickup_field',$scriptProperties['ym_pickup_field'],false);
}
if (isset($scriptProperties['ym_in_stock_field'])) {
    $msie->setOption('msimportexport.export.ym.in_stock_field',$scriptProperties['ym_in_stock_field'],false);
}

if (isset($scriptProperties['ym_delivery_field'])) {
    $msie->setOption('msimportexport.export.ym.delivery_field',$scriptProperties['ym_delivery_field'],false);
}

if (isset($scriptProperties['ym_currency_rate'])) {
    $msie->setOption('msimportexport.export.ym.currency_rate',$scriptProperties['ym_currency_rate'],false);
}

if (isset($scriptProperties['ym_default_currency'])) {
    $msie->setOption('msimportexport.export.ym.default_currency',$scriptProperties['ym_default_currency'],false);
}

if (isset($scriptProperties['ym_currencies'])) {
    $msie->setOption('msimportexport.export.ym.currencies',$scriptProperties['ym_currencies'],false);
}

if (isset($scriptProperties['ym_company'])) {
    $msie->setOption('msimportexport.export.ym.company',$scriptProperties['ym_company'],false);
}

if (isset($scriptProperties['ym_name'])) {
    $msie->setOption('msimportexport.export.ym.name',$scriptProperties['ym_name'],false);
}

$modx->cacheManager->refresh(array('system_settings' => array()));

//return $modx->error->success($modx->lexicon('msimportexport.mess.settings_save_success'));
return $modx->error->success('');

