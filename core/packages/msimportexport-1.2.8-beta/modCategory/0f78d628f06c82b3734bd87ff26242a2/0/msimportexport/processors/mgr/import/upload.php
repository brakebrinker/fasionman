<?php
require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';
$modx->lexicon->load('msimportexport:default');

$steps = 0;
$fields = array();
$msie = new Msie($this->modx);
$msie->clearUploadDir();

$utf8Encode = filter_var($modx->getOption('msimportexport.import.utf8_encode', null, 0), FILTER_VALIDATE_BOOLEAN);

if (!$_FILES['file']) {
    return $modx->error->failure($modx->lexicon('msimportexport.err.ns_file'));
}

$filename = str_replace(' ', '_', $_FILES['file']['name']);
$path = $msie->config['uploadPath'] . $filename;

if (!move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
    return $modx->error->failure($modx->lexicon('msimportexport.err_upload'));
}

$ext = mb_strtolower(pathinfo($path, PATHINFO_EXTENSION));

if ($ext == 'csv' && !empty($utf8Encode)) {
    $content = file_get_contents($path);
    $encoding = mb_detect_encoding($content, array('cp1251', 'utf-8', 'ASCII'));
    if (strtolower($encoding) != 'utf-8') {
        file_put_contents($path, iconv(strtolower($encoding), 'utf-8', $content));
    }
}

if (!$reader = $msie->getReader($path)) {
    return $modx->error->failure($modx->lexicon('msimportexport.err.reader'));
}

$reader->read(array(
    'file' => $path,
    'seek' => $start,
), function ($reader, $data) use (& $fields) {
    $fields = $data;
    return false;
});

if (!empty($fields)) {
    $fields = array_map(strip_tags, $fields);
}

/*if ($rows = $reader->getTotalRows()) {
    $steps = ceil($rows / $modx->getOption('msimportexport.import.step_limit', null, 50));
}*/

return $modx->error->success('', array(
    'filename' => $filename,
    'fields' => $fields,
    'steps' => $steps,
));

