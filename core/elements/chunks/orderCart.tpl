<div class="eight columns">
  {if !count($products)}
        {'ms2_cart_is_empty' | lexicon}
  {else}
  <div class="checkout-section cart">Ваш заказ</div>
  <!-- Cart -->
  <table class="checkout cart-table responsive-table">

    <tr>
      <th class="hide-on-mobile">Товар</th>
      <th></th>
      <th>{'ms2_cart_price' | lexicon}</th>
      <th>Кол.</th>
      <th>Раз.</th>
      <th>Сумма</th>
    </tr>

    <!-- Item #1 -->
    {foreach $products as $product}
    <tr id="{$product.key}">
      <td class="hide-on-mobile">
        {if $product.thumb?}
            <img src="{$product.thumb}" alt="{$product.pagetitle}" title="{$product.pagetitle}"/>
        {else}
            <img src="{'assets_url' | option}images/no-photo-thumb.png"
                 srcset="{'assets_url' | option}images/no-photo-thumb.png"
                 alt="{$product.pagetitle}" title="{$product.pagetitle}"/>
        {/if}
      </td>
      <td class="cart-title"><a href="{$product.id | url}">{$product.pagetitle}</a></td>
      <td><span>{$product.price}</span> {'ms2_frontend_currency' | lexicon}</td>
      <td class="qty-checkout">{$product.count}</td>
      <td class="qty-checkout">{$product.options.size}</td>
      <td class="cart-total">{$product.cost} {'ms2_frontend_currency' | lexicon}</td>
    </tr>
    {/foreach}
  </table>

  <!-- Apply Coupon Code / Buttons -->
  <table class="cart-table bottom">

    <tr>
      <th class="checkout-totals">
        <div class="checkout-subtotal">
          Сумма: <span>{$total.cost} {'ms2_frontend_currency' | lexicon}</span>
        </div>
      </th>
    </tr>

  </table>
  {/if}
</div>