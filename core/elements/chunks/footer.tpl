<!-- Footer
================================================== -->
<div id="footer">

  <!-- Container -->
  <div class="container">

    <div class="four columns">
      <img src="assets/images/mens_fashion_2.jpg" alt="" class="margin-top-10" />
      <p class="margin-top-15">{$_modx->config.store_info}</p>
    </div>

    <div class="four columns">

      <!-- Headline -->
      <h3 class="headline footer">Покупателям</h3>
      <span class="line"></span>
      <div class="clearfix"></div>

      <ul class="footer-links">
        <li><a href="{$_modx->makeUrl(24)}">Методы оплаты</a></li>
        <li><a href="{$_modx->makeUrl(23)}">Доставка и возврат</a></li>
        <li><a href="{$_modx->makeUrl(272)}">Процедура заказа</a></li>
        <li><a href="{$_modx->makeUrl(450)}">Гарантия</a></li>
        <li><a href="{$_modx->makeUrl(18)}">Контакты</a></li>
      </ul>

    </div>

    <div class="four columns">

      <!-- Headline -->
      <h3 class="headline footer">Мой аккаунт</h3>
      <span class="line"></span>
      <div class="clearfix"></div>

      <ul class="footer-links">
        {if $_modx->isAuthenticated()}
          <li><a href="{$_modx->makeUrl(268)}">Мой аккаунт</a></li>
        {else}
          <li><a href="{$_modx->makeUrl(261)}">Мой аккаунт</a></li>
        {/if}
        <li><a href="#">История заказов</a></li>
<!--        <li><a href="#">Wish List</a></li>-->
      </ul>

    </div>

    <div class="four columns">

      <!-- Headline -->
      <h3 class="headline footer">Новостная подписка</h3>
      <span class="line"></span>
      <div class="clearfix"></div>
      <p>Подпишитесь, чтобы получать оповещения о новых поступлениях продукта, специальных акциях, продажах и многое другое.</p>

      {$_modx->runSnippet('!Sendex', [
        'id' => '1',
      ])}
      
      <p class="margin-top-15">{$_modx->config.store_schedule}</p>
    </div>

  </div>
  <!-- Container / End -->

</div>
<!-- Footer / End -->

<!-- Footer Bottom / Start -->
<div id="footer-bottom">

  <!-- Container -->
  <div class="container">

    <div class="eight columns">{$_modx->config.store_copyright}</div>
    <div class="eight columns">
      <ul class="payment-icons">
        {*<li><img src="assets/images/logous-bw.png" alt="" style="height:28px"/></li>*}
<!--         <li><img src="assets/images/verified-by-visa.png" alt="" /></li>
<li><img src="assets/images/mastercard.png" alt="" /></li>
<li><img src="assets/images/mastercard-securecode.png" alt="" /></li>
<li><img src="assets/images/belcard.png" alt="" /></li>
<li><img src="assets/images/sistema-erip.png" alt="" /></li> -->
<!--
        <li><img src="assets/images/skrill.png" alt="" /></li>
        <li><img src="assets/images/moneybookers.png" alt="" /></li>
        <li><img src="assets/images/paypal.png" alt="" /></li>
-->
      </ul>
    </div>

  </div>
  <!-- Container / End -->

</div>
<!-- Footer Bottom / End -->

<!-- Back To Top Button -->
<div id="backtotop">
  <a href="#"></a>
</div>