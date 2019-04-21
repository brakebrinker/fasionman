<?php
$xpdo_meta_map['starRating']= array (
  'package' => 'star_rating',
  'table' => 'star_rating',
  'fields' => 
  array (
    'star_id' => '',
    'group_id' => '',
    'vote_total' => '0',
    'vote_count' => '0',
  ),
  'fieldMeta' => 
  array (
    'star_id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'group_id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'vote_total' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'int',
      'null' => 'false',
      'default' => '0',
    ),
    'vote_count' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'int',
      'null' => 'false',
      'default' => '0',
    ),
  ),
);
$xpdo_meta_map['starrating']= & $xpdo_meta_map['starRating'];
