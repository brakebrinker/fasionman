<div id="msMiniCart" class="{$total_count > 0 ? 'full' : ''}">
  <div id="cart">

    <div class="empty">
      <div class="cart-btn">
        <a href="{$_modx->makeUrl(6)}" class="button adc ms2_total_cost">{$total_cost} {'ms2_frontend_currency' | lexicon}</a>
      </div>
    </div>

    <div class="not_empty">
      <div class="cart-btn">
        <a href="{$_modx->makeUrl(6)}" class="button adc ms2_total_cost">{$total_cost} {'ms2_frontend_currency' | lexicon}</a>
      </div>

      <div class="cart-list">

        <div class="arrow"></div>

        <div class="cart-amount">
          <span>Товаров в вашей корзине - <i class="ms2_total_count">{$total_count}</i></span>
        </div>
        {$_modx->runSnippet('!msCart', [
          'tpl' => '@FILE chunks/productMiniCart.tpl',
        ])}
        <div class="cart-buttons button">
          <a href="{$_modx->makeUrl(6)}" class="view-cart"><span data-hover="View Cart"><span>Посмотреть корзину</span></span></a>
          <a href="{$_modx->makeUrl(8)}" class="checkout"><span data-hover="Checkout">Оформить заказ</span></a>
        </div>
        <div class="clearfix">

        </div>
      </div>

    </div>
  </div>
</div>
