{extends 'file:templates/_base.tpl'}

{block 'content'}

<section class="parallax-titlebar fullwidth-element" data-background="#000" data-opacity="0.45" data-height="160">

  <img src="assets/images/titlebar_bg_03.jpg" alt="" />
  <div class="parallax-overlay"></div>


  <div class="parallax-content">
<!--TODO: [x]Sdelat PageTitle-->
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
    'filters' => '
      ms|price:number',
    'tplOuter' => '@FILE chunks/filters/mFilterProduct.tpl',
    'tplFilter.outer.default' => '@FILE chunks/filters/filter2Outer.tpl',
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