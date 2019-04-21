<div class="item-hover"></div>
<div class="ac-wrapper mse2-ac-item">
  <div class="ac-offers">
    {if $old_price?}
      <span class="product-price-discount">{$old_price} {'ms2_frontend_currency' | lexicon}<i>{$price} {'ms2_frontend_currency' | lexicon}</i></span>
    {else}
      <span class="product-price"><i>{$price} {'ms2_frontend_currency' | lexicon}</i></span>
    {/if}
  </div>
  <div class="ac-details">
    <div class="ac-pagetitle">{$pagetitle}</div>
    <p class="margin-reset">
      {$_modx->runSnippet('!msProductOptions', [
        'tpl' => '@FILE chunks/productOptionsIntro.tpl',
        'groups' => '31,32',
        'product' => $id,
      ])}
    </p>
  </div>
	<div class="ac-image">
    [[!msGallery?
      &tpl=`@FILE:chunks/searchGallery.tpl`
      &product=`{$id}`
      &offset = `0`
    	&limit = `1`
    ]]
  </div>
</div>
