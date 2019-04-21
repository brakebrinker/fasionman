<?php
/**
 * Star Rating snippet
 *
 * @package star_rating
 */
$snippetPath = $modx->getOption('core_path').'components/star_rating/';
$modx->addPackage('star_rating',$snippetPath.'model/');

$manager = $modx->getManager();
$manager->createObjectContainer('starRating');

$starId = isset($starId) ? $starId : null;
$groupId = isset($groupId) ? $groupId : '';

$c = $modx->newQuery('starRating');
$c->where(array('star_id' => $starId));
if ($groupId != '') $c->where(array('group_id' => $groupId));

$starRating = $modx->getObject('starRating', $c);
if ($starRating == null) {
    $starRating = $modx->newObject('starRating');
    $starRating->set('star_id',$starId);
    $starRating->set('group_id',$groupId);
}
$starRating->initialize();

/* parameters */
$starRating->setConfig($scriptProperties);

/* process star rating */
$starRating->loadTheme();

$groupIdCheck = isset($_GET['group_id']) && $starRating->get('group_id') !== $_GET['group_id'] ? false : true;

if (isset($_GET['vote']) && isset($_GET['star_id']) && $starRating->get('star_id') == $_GET['star_id'] && $groupIdCheck) {
    $starRating->setVote($_GET['vote']);
    $starRating->addVote();
    $modx->sendRedirect($starRating->getRedirectUrl());
}

return $starRating->renderVote();