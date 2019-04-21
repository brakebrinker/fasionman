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
  <div class="four columns">

    <!-- Categories -->
    <div class="widget margin-top-0">
      <h3 class="headline">Категории</h3><span class="line"></span>
      <div class="clearfix"></div>
      <!--TODO: [x]vivesti categorii-->
      {$_modx->runSnippet('pdoMenu', [
        'parents' => '4',
        'level' => '0',
        'tplOuter' => '@INLINE <ul id="categories">{$wrapper}</ul>',
        'tpl' => '@INLINE <li><a href="{$link}" {$classes}>{$menutitle}  <span>({$children})</span></a>{$wrapper}</li>',
        'tplInner' => '@INLINE [[*id:is=`[[+id]]`:then=`<ul style="display:block;">{$wrapper}</ul>`:else=`<ul>{$wrapper}</ul>`]]',
        'countChildren' => '1',
        'hereClass' => 'active',
        'cache' => '1',
        'cacheTime' => '3600'
      ])}
      
      <div class="clearfix"></div>
    </div>

  </div>


  <!-- Content
	================================================== -->
<!--
  <div class="twelve columns">

     Ordering 
    <select class="orderby">
      <option>Default Sorting</option>
      <option>Sort by Popularity</option>
      <option>Sort by Newness</option>
    </select>

  </div>
-->

  <!-- Products -->
  <div class="twelve columns products">
    {$_modx->runSnippet('!pdoPage', [
      'element' => 'msProducts',
      'tpl' => '@FILE chunks/productGrid.tpl',
      'limit' => '12',
      'tplPageActive' => '@INLINE <li><a href="{$href}" class="current-page">{$pageNo}</a></li>',
      'tplPageWrapper' => '@INLINE <nav class="pagination"><ul>{$pages}</ul></nav><nav class="pagination-next-prev"><ul>{$prev}{$next}</ul></nav>',
      'tplPagePrev' => '@INLINE <li><a href="{$href}" class="prev"></a></li>',
      'tplPageNext' => '@INLINE <li><a href="{$href}" class="next"></a></li>',
      'tplPagePrevEmpty' => '@INLINE <li class="disabled"><span></span></li>',
      'tplPageNextEmpty' => '@INLINE <li class="disabled"><span></span></li>',
    ])}
    
    <div class="clearfix"></div>

    <!-- Pagination -->
    <div class="pagination-container">
<!--    TODO: [x]Sdelat normalno paginaciy-->
      [[!+page.nav]]
    </div>

  </div>

</div>

<div class="margin-top-15"></div>
{/block}