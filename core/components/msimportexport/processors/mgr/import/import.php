<?php

require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';
$msie = new Msie($this->modx);

$modx->lexicon->load('msimportexport:default');

if (empty($scriptProperties['filename'])) {
    $modx->error->addField('filename', $modx->lexicon('msimportexport.err.err_ns'));
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$fields = isset($scriptProperties['fields']) ? $scriptProperties['fields'] : array();
$preset = isset($scriptProperties['preset']) ? $scriptProperties['preset'] : 0;
$seek = isset($scriptProperties['seek']) ? $scriptProperties['seek'] : 0;
$offset = isset($scriptProperties['offset']) ? $scriptProperties['offset'] : 0;
$steps = isset($scriptProperties['steps']) ? $scriptProperties['steps'] : 0;
$type = isset($scriptProperties['import_type']) ? $scriptProperties['import_type'] : 'products';


if (($fields && $preset) || $type == 'categories') {
    $msie->setPresetFields($preset, $fields);
} else {
    return $modx->error->failure($modx->lexicon('msimportexport.err.import_empty_fields'));
}

if (!$msie->checkValidityСatalog()) {
    return $modx->error->failure($modx->lexicon('msimportexport.err_invalid_catalog'));
}

$result = $msie->import($scriptProperties['filename'], $preset, $type, $seek);


$modx->cacheManager->refresh(array('system_settings' => array()));

return $modx->error->success('', $result);

