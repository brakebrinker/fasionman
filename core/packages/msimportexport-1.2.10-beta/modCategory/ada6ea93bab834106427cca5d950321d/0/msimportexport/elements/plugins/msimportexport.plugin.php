<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case 'msieOnBeforeImportProduct':
       // $modx->event->params['destData']['price'] = 10;
        $modx->event->returnedValues['destData'] = $modx->event->params['destData'];
        //$modx->log(modX::LOG_LEVEL_ERROR, print_r($modx->event->params, 1));
        //$modx->event->output('Error');
        break;
    case 'msieOnAfterImportProduct':
        //$modx->log(modX::LOG_LEVEL_ERROR, print_r($modx->event->params, 1));
        break;
}