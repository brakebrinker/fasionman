{$_modx->runSnippet('pdoMenu', [
  'parents' => '4',
  'level' => '2',
  'tplOuter' => '@INLINE {$wrapper}',
  'tplParentRow' => '@INLINE <div class="one-fourth-column"> <span class="mega-headline">{$menutitle}</span>{$wrapper}</div>',
  'tplInner' => '@INLINE <ul>{$wrapper}</ul>',
	'tplInnerRow' => '@INLINE <li><a href="{$link}">{$menutitle}</a></li>',
  'firstClass' => 'homepage',
  'hereClass' => 'current',
  'includeTVs' => 'categoryImg',
  'tvPrefix' => 'tv.',
])}