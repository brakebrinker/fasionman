<div id="msGallery" class="eight columns">
  <div class="slider-padding">
    <div id="product-slider-vertical" class="royalSlider rsDefault">
        {if $files?} 
        {foreach $files as $file}
        {$files}
          <a href="{$file['url']}" class="mfp-gallery" title="{$_modx->resource.pagetitle}">
            <img class="rsImg" src="{$file['560x632']}" data-rsTmb="{$file['120x90']}" alt="{$_modx->resource.pagetitle} - фото" title="{$_modx->resource.pagetitle} - фото">
          </a>
        {/foreach}
        {else}
          <img src="{('assets_url' | option) ~ 'images/no-photo-big.png'}" srcset="{('assets_url' | option) ~ 'images/no-photo-big.png'} 2x" alt="{$_modx->resource.pagetitle} - фото" title="{$_modx->resource.pagetitle} - фото" /> 
        {/if}
    </div>
    <div class="clearfix"></div>
  </div>
</div>
