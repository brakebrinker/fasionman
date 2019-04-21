<ul>
{foreach $products as $product}
  <li id="{$product.key}">
    <a href="{$product.id | url}">
      {if $product.thumb?}
          <img src="{$product.thumb}" alt="{$product.pagetitle}" title="{$product.pagetitle}"/>
      {else}
          <img src="{'assets_url' | option}images/no-photo-thumb.png"
               srcset="{'assets_url' | option}images/no-photo-thumb.png"
               alt="{$product.pagetitle}" title="{$product.pagetitle}"/>
      {/if}
    </a>
    <a href="{$product.id | url}">{$product.pagetitle}</a>
    <span>{$product.count} x {$product.price} {'ms2_frontend_currency' | lexicon}</span>
    <div class="clearfix"></div>
  </li>
{/foreach}
</ul>