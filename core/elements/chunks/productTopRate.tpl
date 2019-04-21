<li class="ms2_product">
  <a href="{$id | url}">
    {if $thumb?}
      <img src="{$thumb}" alt="{$longtitle ? $longtitle : $pagetitle} - фото" title="{$longtitle ? $longtitle : $pagetitle} - фото"/>
    {else}
      <img src="{'assets_url' | option}images/no-photo-thumb.png"
         srcset="{'assets_url' | option}images/no-photo-thumb.png"
         alt="{$longtitle ? $longtitle : $pagetitle} - фото" title="{$longtitle ? $longtitle : $pagetitle} - фото"/>
    {/if}
    <div class="product-list-desc with-rating">{$pagetitle} <i>{$price} {'ms2_frontend_currency' | lexicon}</i>
      <div class="rating five-stars">
        <div class="star-rating"></div>
        <div class="star-bg"></div>
      </div>
    </div>
  </a>
</li>