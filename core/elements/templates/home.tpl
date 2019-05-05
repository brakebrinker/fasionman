{extends 'file:templates/_base.tpl'} 

{block 'content'}
<!-- Slider
================================================== -->
<div class="container fullwidth-element home-slider">

  <div class="tp-banner-container">
    <div class="tp-banner">
      <ul>
        
      {insert 'file:chunks/sliderHome.tpl'} 

      </ul>
    </div>
  </div>
</div>


<!-- Featured
================================================== -->
<div class="container">
  {$_modx->runSnippet('!pdoResources', [
    'parents' => '27',
    'depth' => '0',
    'tpl' => '@INLINE 
    <div class="one-third column">
      <a href="{$_modx->makeUrl($id)}" class="img-caption">
        <figure>
          [[+tv.categoryImg:!empty=`<img src="[[+tv.categoryImg]]" alt="{$longtitle ? $longtitle : $pagetitle} - фото" title="{$longtitle ? $longtitle : $pagetitle} - фото"/>`]]
          <figcaption>
            <h3>{$longtitle ? $longtitle : $pagetitle}</h3>
            <span>{$introtext}</span>
          </figcaption>
        </figure>
      </a>
    </div>
    ',
    'includeTVs' => 'categoryImg',
  ])}
  
</div>
<div class="clearfix"></div>


<!-- New Arrivals
================================================== -->
<div class="container">

  <!-- Headline -->
  <div class="sixteen columns">
    <h3 class="headline">Новые поступления</h3>
    <span class="line margin-bottom-0"></span>
  </div>

  <!-- Carousel -->
  <div id="new-arrivals" class="showbiz-container sixteen columns">

    <!-- Navigation -->
    <div class="showbiz-navigation">
      <div id="showbiz_left_1" class="sb-navigation-left"><i class="fa fa-angle-left"></i></div>
      <div id="showbiz_right_1" class="sb-navigation-right"><i class="fa fa-angle-right"></i></div>
    </div>
    <div class="clearfix"></div>

    <!-- Products -->
    <div class="showbiz" data-left="#showbiz_left_1" data-right="#showbiz_right_1" data-play="#showbiz_play_1">
      <div class="overflowholder">
        <ul>
        {$_modx->runSnippet('!msProducts', [
          'parents' => '{$_modx->resource.parent}',
          'tpl' => '@FILE chunks/productNew.tpl',
          'optionFilters' => '{"Data.new":"1"}',
        ])}
        </ul>
        <div class="clearfix"></div>

      </div>
      <div class="clearfix"></div>
    </div>
  </div>

</div>


<!-- Parallax Banner
================================================== -->
<div class="parallax-banner fullwidth-element" data-background="#000" data-opacity="0.45" data-height="200">
  <img src="{$_modx->resource.main_banner_image}" alt="" />
  <div class="parallax-overlay"></div>
  <div class="parallax-title">{$_modx->resource.main_banner_title} <span>{$_modx->resource.main_banner_text}</span></div>
</div>


<!-- Product Lists
================================================== -->
<div class="container margin-bottom-25">

  <!-- Best Sellers -->
  <div class="one-third column">

    <!-- Headline -->
    <h3 class="headline">Продаваемые</h3>
    <span class="line margin-bottom-0"></span>
    <div class="clearfix"></div>

    <ul class="product-list">
      {$_modx->runSnippet('!msProducts', [
        'parents' => '{$_modx->resource.parent}',
        'tpl' => '@FILE chunks/productBestSell.tpl',
        'optionFilters' => '{"Data.favorite":"1"}',
      ])}

      <li>
        <div class="clearfix"></div>
      </li>

    </ul>

  </div>


  <!-- Top Rated -->
  <div class="one-third column">

    <!-- Headline -->
    <h3 class="headline">Популярные</h3>
    <span class="line margin-bottom-0"></span>
    <div class="clearfix"></div>

    <ul class="product-list top-rated">
      {$_modx->runSnippet('!msProducts', [
        'parents' => '{$_modx->resource.parent}',
        'tpl' => '@FILE chunks/productTopRate.tpl',
        'optionFilters' => '{"Data.popular":"1"}',
      ])}
      
      <li>
        <div class="clearfix"></div>
      </li>

    </ul>

  </div>


  <!-- Weekly Sales -->
  <div class="one-third column">

    <!-- Headline -->
    <h3 class="headline">Недельные скидки</h3>
    <span class="line margin-bottom-0"></span>
    <div class="clearfix"></div>

    <ul class="product-list discount">
      {$_modx->runSnippet('!msProducts', [
        'parents' => '{$_modx->resource.parent}',
        'tpl' => '@FILE chunks/productWeekSales.tpl',
        'optionFilters' => '{"Data.old_price:>":"0"}',
        'limit' => '3',
      ])}

      <li>
        <div class="clearfix"></div>
      </li>

    </ul>

  </div>

</div>
<div class="clearfix"></div>

<div class="margin-top-50"></div>
{/block}