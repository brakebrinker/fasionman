<li class="ms2_product">
  <a href="{$id | url}">
    {if $thumb?}
      <img src="{$thumb}" alt="{$longtitle ? $longtitle : $pagetitle} - фото" title="{$longtitle ? $longtitle : $pagetitle} - фото"/>
    {else}
      <img src="{'assets_url' | option}images/no-photo-thumb.png"
         srcset="{'assets_url' | option}images/no-photo-thumb.png"
         alt="{$longtitle ? $longtitle : $pagetitle} - фото" title="{$longtitle ? $longtitle : $pagetitle} - фото"/>
    {/if}
    <div class="product-list-desc">{$pagetitle} 
      <i>{$price} {'ms2_frontend_currency' | lexicon}
        <b>
          {if $old_price?}
            {$old_price} {'ms2_frontend_currency' | lexicon}
          {/if}
        </b>
      </i>
    </div>
  </a>
</li>