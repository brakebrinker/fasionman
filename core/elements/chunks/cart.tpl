<!--
//массив данных
{$total | print}
{foreach $products as $product}
    {$product | print}
{/foreach}
-->

<div class="container cart">
  {if !count($products)}
        {'ms2_cart_is_empty' | lexicon}
  {else}
  <div class="sixteen columns">
  {*<div class="warning-message">*}
    {*<img src="{'assets_url' | option}images/warning.png" alt=""><p> <strong>Внимание!</strong> Перед совершением оплаты товара через <strong>"систему ЕРИП"</strong> или <strong>"Банковской картой"</strong> свяжитесь с менеджером по тел. +375 (33) 346-65-39 или воспользовавшись <a href="{$_modx->makeUrl(18)}">формой обратной связи</a>, и уточните наличие товара на складе. *}
    {*Так как при оплате товара через систему ЕРИП возврат денежных средств за отсутствующий товар <strong>невозможен</strong>, вы можете <strong>заморозить свои деньги на 2 - 3 дня</strong>, пока мы не найдем другие способы их вернуть.</p>*}
  {*</div>*}
    
    <!-- Cart -->
    <table class="cart-table responsive-table">

      <tr>
        <th>Товар</th>
        <th>{'ms2_cart_title' | lexicon}</th>
        <th>{'ms2_cart_price' | lexicon}</th>
        <th>{'ms2_cart_count' | lexicon}</th>
        <th>{'ms2_cart_size' | lexicon}</th>
        <th>Сумма</th>
        <th></th>
      </tr>
      
      {foreach $products as $product}
      <tr id="{$product.key}">
        <td>
            {if $product.thumb?}
                <img src="{$product.thumb}" alt="{$product.pagetitle}" title="{$product.pagetitle}"/>
            {else}
                <img src="{'assets_url' | option}images/no-photo-thumb.png"
                     srcset="{'assets_url' | option}images/no-photo-thumb.png"
                     alt="{$product.pagetitle}" title="{$product.pagetitle}"/>
            {/if}
        </td>
        <td class="cart-title"><a href="{$product.id | url}">{$product.pagetitle}</a></td>
        <td>
          <span>{$product.price}</span> {'ms2_frontend_currency' | lexicon}
          {if $product.old_price?}
            <span class="old_price">{$product.old_price} {'ms2_frontend_currency' | lexicon}</span>
          {/if}
        </td>
        <td>
          <form method="post" class="ms2_form" role="form">
            <input type="hidden" name="key" value="{$product.key}"/>
            <button class="qtyminus" type="submit" name="ms2_action" value="cart/change"></button>
            <input type='text' name="count" value="{$product.count}" class="qty" />
            <button class="qtyplus" type="submit" name="ms2_action" value="cart/change"></button>
          </form>
        </td>
        <td>{$product.options.size}</td>
        <td class="cart-total">{$product.cost} {'ms2_frontend_currency' | lexicon}</td>
        <td>
          <form method="post" data-action="cart/remove">
    	      <input type="hidden" name="key" value="{$product.key}"/>
            <button class="cart-remove" type="submit" name="ms2_action" value="cart/remove"></button>
          </form>
        </td>
      </tr>
      {/foreach}
    </table>

    <!-- Apply Coupon Code / Buttons -->
    <table class="cart-table bottom">

      <tr>
        <th>
<!--
          <form action="#" method="get" class="apply-coupon">
            <input class="search-field" type="text" placeholder="Coupon Code" value="" />
            <a href="#" class="button gray">Apply Coupon</a>
          </form>
-->

          <div class="cart-btns">
            <a href="{$_modx->makeUrl(8)}" class="button color cart-btns proceed">Оформление заказа</a>
            <a href="{$_modx->makeUrl(6)}" class="button gray cart-btns">Обновить корзину</a>
          </div>
        </th>
      </tr>

    </table>
  </div>


  <!-- Cart Totals -->
  <div class="eight columns cart-totals">
    <h3 class="headline">Всего в корзине</h3><span class="line"></span>
    <div class="clearfix"></div>

    <table class="cart-table margin-top-5">

      <tr>
        <th>Стоимость товаров </th>
        <td><strong>{$total.cost} {'ms2_frontend_currency' | lexicon}</strong></td>
      </tr>

    </table>
    <br>
    <!-- <a href="#" class="calculate-shipping"><i class="fa fa-arrow-circle-down"></i> Calculate Shipping</a> -->
  </div>
  {/if}
</div>