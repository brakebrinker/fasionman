<?php

require_once MODX_CORE_PATH . 'components/msimportexport/model/msimportexport/msie.class.php';
$msie = new Msie($this->modx);

$modx->lexicon->load('msimportexport:default');

$preset  = $scriptProperties['preset'] ? $scriptProperties['preset'] : 0;

if (empty($scriptProperties['cron_file_path']) ) {
    $modx->error->addField('cron_file_path',$modx->lexicon('msimportexport.err.err_ns'));
    return $modx->error->failure();
}

if(empty($preset)) {
    return $modx->error->failure(sprintf($modx->lexicon('msimportexport.err.ns_preset'),$preset));
}

if(!$file = $msie->loadImportFile($scriptProperties['cron_file_path'])) {
    return $modx->error->failure(sprintf($modx->lexicon('msimportexport.err_open_file'),$file));
}

$cron_log = filter_var($scriptProperties['cron_log'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

if(!$msie->checkValidityСatalog()) {
    return $modx->error->failure($modx->lexicon('msimportexport.err_invalid_catalog'));
}

$result = $msie->import($file,$preset,$scriptProperties['import_type'],$cron_log);
$report='';
if($result) {
    $report .= '<h3>'.$modx->lexicon('msimportexport.result.report').'</h3>';
    $report .= '<p>';
    $report .= $modx->lexicon('msimportexport.result.errors').' <a href="/manager/?a=system/event" target="_blank">'.$result['errors'].'</a><br>';
    $report .= $modx->lexicon('msimportexport.result.created').' '.$result['create'].'<br>';
    $report .= $modx->lexicon('msimportexport.result.updated').' '.$result['update'].'<br>';
    $report .= $modx->lexicon('msimportexport.result.rows').' '.$result['rows'];
    $report .='</p>';
    if(isset($result['uri'])) {
        $report .= '<h3>'.$modx->lexicon('msimportexport.result.report.uri').'</h3>';
        $report .= '<p>';
        $report .= $modx->lexicon('msimportexport.result.report.uri.total_duplicate').' '.$result['uri']['total'].'<br>';
        $report .= $modx->lexicon('msimportexport.result.report.uri.success').' '.$result['uri']['success'].'<br>';
        $report .= $modx->lexicon('msimportexport.result.report.uri.failed').' '.$result['uri']['failed'];
        $report .='</p>';
    }
}

return $modx->error->success($report);

