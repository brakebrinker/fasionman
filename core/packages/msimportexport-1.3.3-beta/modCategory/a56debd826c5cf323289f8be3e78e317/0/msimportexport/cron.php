<?php

define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';

$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

/** @var Msie $msie */
$msie = $modx->getService('msimportexport', 'Msie', $modx->getOption('msimportexport.core_path', null, $modx->getOption('core_path') . 'components/msimportexport/') . 'model/msimportexport/', array());

if (Msie::isLockFile(Msie::LOCK_FILE_CRON))
    exit;

if (!Msie::lockFile(Msie::LOCK_FILE_CRON))
    throw new Exception("[msImportExport] CRON: Unable to establish a lock on the cron file");

do {
    $loop = false;
    if ($tasks = $msie->getCronTasks()) {
        foreach ($tasks as $task) {
            if (!$task->isDue()) continue;
            $loop = true;
            exec("php cron-task.php {$task->id} 1> /dev/null 2>&1 &");
        }
    }
    if ($loop) sleep($modx->getOption('msimportexport.cron_sleep_time', null, 15));
} while ($loop);

