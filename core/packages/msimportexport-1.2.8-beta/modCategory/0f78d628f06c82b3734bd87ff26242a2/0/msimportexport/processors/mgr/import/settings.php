<?php

require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';
$msie = new Msie($this->modx);

$modx->lexicon->load('msimportexport:default');

if (!empty($scriptProperties['delimeter'])) {
    $msie->setOption('msimportexport.delimeter',$scriptProperties['delimeter'],false);
}

if (!empty($scriptProperties['sub_delimeter'])) {
    $msie->setOption('msimportexport.import.sub_delimeter',$scriptProperties['sub_delimeter'],false);
}

if (!empty($scriptProperties['sub_delimeter2'])) {
    $msie->setOption('msimportexport.import.sub_delimeter2',$scriptProperties['sub_delimeter2'],false);
}


if (!empty($scriptProperties['key'])) {
    $msie->setOption('msimportexport.key',$scriptProperties['key'],false);
}

if (isset($scriptProperties['id_parent_new_product'])) {
    $msie->setOption('msimportexport.import.id_parent_new_product',$scriptProperties['id_parent_new_product'],false);
}

if (!empty($scriptProperties['catalog'])) {
    $msie->setOption('msimportexport.import.root_catalog',$scriptProperties['catalog'],false);
}

if (isset($scriptProperties['use_only_root_catalog'])) {
    $useOnlyRootCatalog = filter_var($scriptProperties['use_only_root_catalog'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.import.use_only_root_catalog',$useOnlyRootCatalog,false);
}

if (isset($scriptProperties['time_limit'])) {
    $msie->setOption('msimportexport.time_limit',(int)$scriptProperties['time_limit'],false);
}

if (isset($scriptProperties['template_category'])) {
    $msie->setOption('msimportexport.import.template_category',(int)$scriptProperties['template_category'],false);
}

if (isset($scriptProperties['step_limit'])) {
    $msie->setOption('msimportexport.import.step_limit',(int)$scriptProperties['step_limit'],false);
}

if (isset($scriptProperties['ignore_first_line'])) {
    $ignore_first_line = filter_var($scriptProperties['ignore_first_line'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.ignore_first_line',$ignore_first_line,false);
}

if (isset($scriptProperties['debug'])) {
    $debug = filter_var($scriptProperties['debug'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.debug',$debug,false);
}

if (isset($scriptProperties['utf8_encode'])) {
    $utf8Encode = filter_var($scriptProperties['utf8_encode'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.import.utf8_encode',$utf8Encode,false);
}

$updateUri = false;
if (isset($scriptProperties['update_uri'])) {
    $updateUri = filter_var($scriptProperties['update_uri'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.update_uri',$updateUri,false);
}

if (isset($scriptProperties['skip_empty_parent'])) {
    $skip_empty_parent = filter_var($scriptProperties['skip_empty_parent'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.skip_empty_parent',$skip_empty_parent,false);
}

if (isset($scriptProperties['create_parent'])) {
    $create_parent = filter_var($scriptProperties['create_parent'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.create_parent',$create_parent,false);
}

if (isset($scriptProperties['post_action'])) {
    $msie->setOption('msimportexport.post_action',$scriptProperties['post_action'],false);
}

if (isset($scriptProperties['cron_log'])) {
    $cron_log = filter_var($scriptProperties['cron_log'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $msie->setOption('msimportexport.import.cron_log',$cron_log,false);
}

if (isset($scriptProperties['cron_file_path'])) {
    $msie->setOption('msimportexport.import.cron_file_path',$scriptProperties['cron_file_path'],false);
}

$fields = isset($scriptProperties['fields']) ? $scriptProperties['fields']  : array();
$preset = isset($scriptProperties['preset']) ? $scriptProperties['preset'] : 0;

if($fields && $preset) {
    $msie->setPresetFields($preset, $fields);
}


$modx->cacheManager->refresh(array('system_settings' => array()));

return $modx->error->success('');

