{extends 'file:templates/_base.tpl'}

{block 'content'}

<section class="parallax-titlebar fullwidth-element" data-background="#000" data-opacity="0.45" data-height="160">

  <img src="assets/images/titlebar_bg_03.jpg" alt="" />
  <div class="parallax-overlay"></div>


  <div class="parallax-content">
    <h1>{$_modx->resource.longtitle ? $_modx->resource.longtitle : $_modx->resource.pagetitle} <span>Доставка по всей Беларуси</span></h1>

    <nav id="breadcrumbs">
      {$_modx->runSnippet ('pdoCrumbs', [
        'showHome' => '1',
      ])}
    </nav>
  </div>
  
</section>


<div class="container">

  <!-- Sidebar
================================================== -->

  {$_modx->runSnippet('!mFilter2', [
    'class' => 'msProduct',
    'element' => 'msProducts',
    'paginator' => 'pdoPage',
    'resources' => ($_modx->runSnippet('!mSearch2', ['returnIds' => 1, 'limit' => 0]) ?: 999999),
    'filters' => '
      ms|vendor:vendors,
      msoption|drel_type,
      msoption|drel_workmodes,
      msoption|cartridgetype,
      msoption|feedmode,
      msoption|powerfull:number,
      ms|price:number',
    'tplOuter' => '@FILE chunks/filters/mFilterProduct.tpl',
    'tplFilter.outer.default' => '@FILE chunks/filters/filter2Outer.tpl',
    'tplFilter.row.ms|vendor' => '@FILE chunks/filters/typeFilterCheckbox.tpl',
    'tplFilter.row.msoption|drel_type' => '@FILE chunks/filters/typeFilterCheckbox.tpl',
    'tplFilter.row.msoption|drel_workmodes' => '@FILE chunks/filters/typeFilterCheckbox.tpl',
    'tplFilter.row.msoption|cartridgetype' => '@FILE chunks/filters/typeFilterCheckbox.tpl',
    'tplFilter.row.msoption|feedmode' => '@FILE chunks/filters/typeFilterCheckbox.tpl',
    'tplFilter.outer.msoption|powerfull' => '@FILE chunks/filters/priceFilterSlider.tpl',
    'tplFilter.row.msoption|powerfull' => '@FILE chunks/filters/priceFilterNumber.tpl',
    'tplFilter.outer.ms|price' => '@FILE chunks/filters/priceFilterSlider.tpl',
    'tplFilter.row.ms|price' => '@FILE chunks/filters/priceFilterNumber.tpl',
    'tpl' => '@FILE chunks/productGrid.tpl',
    'limit' => '12',
    'tplPageActive' => '@INLINE <li><a href="{$href}" class="current-page">{$pageNo}</a></li>',
      'tplPageWrapper' => '@INLINE <nav class="pagination"><ul>{$pages}</ul></nav><nav class="pagination-next-prev"><ul>{$prev}{$next}</ul></nav>',
      'tplPagePrev' => '@INLINE <li><a href="{$href}" class="prev"></a></li>',
      'tplPageNext' => '@INLINE <li><a href="{$href}" class="next"></a></li>',
      'tplPagePrevEmpty' => '@INLINE <li class="disabled"><span></span></li>',
      'tplPageNextEmpty' => '@INLINE <li class="disabled"><span></span></li>',
  ])}

</div>

<div class="margin-top-15"></div>
{/block}