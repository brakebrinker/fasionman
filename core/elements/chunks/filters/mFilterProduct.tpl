<div class="row msearch2" id="mse2_mfilter">
  <div class="four columns">

    <form action="{$_modx->makeUrl($_modx->resource.id)}" method="post" id="mse2_filters">

      {$filters}
      <br/>
      <div class="result-container">
        <div class="res-count">Найдено:<span id="mse2_total"> {$total ?: '0'}</span></div>
        [[+filters:isnot=``:then=`
        <div class="reset-button">
          <button type="reset" class="button color hidden">{$_modx->lexicon('mse2_reset')}</button>
        </div>
        `]]
      </div>
    </form>
  </div>

  <div class="twelve columns products">

    <div class="row">
      <!--<div id="mse2_sort" class="span5 col-md-5">-->
      <!--  {$_modx->lexicon('mse2_sort')}-->
      <!--  <a href="#" data-sort="resource|publishedon" data-dir="[[+mse2_sort:is=`resource|publishedon:desc`:then=`desc`]]" data-default="desc" class="sort">{$_modx->lexicon('mse2_sort_publishedon')} <span></span></a>-->
      <!--</div>-->

      [[+tpls:notempty=`
      <div id="mse2_tpl" class="span4 col-md-4">
        <a href="#" data-tpl="0" class="{$tpl0}">{$_modx->lexicon('mse2_chunk_default')}</a> /
        <a href="#" data-tpl="1" class="{$tpl1}">{$_modx->lexicon('mse2_chunk_alternate')}</a>
      </div>
      `]]
      
      <div id="filters" class="filters-dropdown headline"><span></span>
    			<ul id="mse2_selected" class="option-set" data-option-key="filter">
    			</ul>
      </div>

    </div>

    <div id="mse2_results">
      {$results}
    </div>
    
    <div class="clearfix"></div>

    <div class="mse2_pagination pagination-container">
      [[!+page.nav]]
    </div>

  </div>
</div>