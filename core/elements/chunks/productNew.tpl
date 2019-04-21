
 <li class="ms2_product">
  <form method="post" class="ms2_form">
    <input type="hidden" name="id" value="{$id}">
    <input type="hidden" name="count" value="1">
    <input type="hidden" name="options" value="[]">
    <figure class="product">
      {if $old_price?}
        <div class="product-discount">Скидка</div>
      {/if}
      <div class="mediaholder">
        <a href="{$id | url}">
          [[!msGallery?
              &tpl=`@FILE:chunks/gridGallery.tpl`
              &product=`{$id}`
              &offset = `0`
            	&limit = `1`
            ]]
          <div class="cover">
            [[!msGallery?
              &tpl=`@FILE:chunks/gridGallery.tpl`
              &product=`{$id}`
              &offset = `1`
            	&limit = `1`
            ]]
          </div>
        </a>
        
        <button class="product-button" type="submit" name="ms2_action" value="cart/add">
          <i class="fa fa-shopping-cart"></i> В корзину
        </button>
      </div>

      <a href="{$id | url}">
        <section>
          <span class="product-category">[[!pdoField?&id=`{$parent}`&field=`pagetitle`]]</span>
          <h5>{$pagetitle}</h5>
          
          {if $old_price?}
            <span class="product-price-discount">{$old_price} {'ms2_frontend_currency' | lexicon}<i>{$price} {'ms2_frontend_currency' | lexicon}</i></span>
          {else}
            <span class="product-price">{$price} {'ms2_frontend_currency' | lexicon}</span>
          {/if}
        </section>
      </a>
    </figure>
  </form>
</li>
<!--
 {if $publishedon >= '' | date : 'U' - 2592000}
{/if}-->
