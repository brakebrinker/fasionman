<?php
require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';
$modx->lexicon->load('msimportexport:default');

$steps = 0;
$fields = array();

$msie = new Msie($this->modx);
$msie->clearUploadDir();


if (!$_FILES['file']) {
    return $modx->error->failure($modx->lexicon('msimportexport.err.ns_file'));
}

$filename = str_replace(' ', '_', $_FILES['file']['name']);
$file = $msie->config['uploadPath'] . $filename;

if (!move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
    return $modx->error->failure($modx->lexicon('msimportexport.err_upload'));
}

$msie->cp1251ToUtf8($file);

if ($scriptProperties['type'] != 'categories') {

    if (!$reader = $msie->getReader($file)) {
        return $modx->error->failure($modx->lexicon('msimportexport.err.reader'));
    }

    $reader->read(array(
        'file' => $file,
        'seek' => $start,
    ), function ($reader, $data) use (& $fields) {
        $fields = $data;
        return false;
    });

    if (!empty($fields)) {
        $fields = array_map(strip_tags, $fields);
    }
}


return $modx->error->success('', array(
    'filename' => $filename,
    'fields' => $fields,
    'steps' => $steps,
    'memory_limit' => ini_get('memory_limit'),
    'timeout' => ini_get('max_execution_time'),
    'phpversion' => phpversion(),
));

