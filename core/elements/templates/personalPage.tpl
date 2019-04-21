{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">
  <div class="sixteen columns">
    {if $_modx->isAuthenticated()}
	    [[!Profile]]
	    
      <div class="four columns">
        <p class="form-row form-row-wide">
  				<label for="fullname">ФИО: <span><strong>[[+fullname]]</strong></span></label>
  				<label for="email">Email: <span>[[+email]]</span></label>
  				[[+phone:notempty=`<label for="phone">Телефон: <span>[[+phone]]</span></label>`]]
    			[[+mobilephone:notempty=`<label for="mobilephone">Мобильный телефон: <span>[[+mobilephone]]</span></label>`]]
    			[[+country:notempty=`<label for="country">Страна: <span>[[+country:default=`Беларусь`]]</span></label>`]]
    			[[+city:notempty=`<label for="city">Город: <span>[[+city]]</span></label>`]]
    			[[+address:notempty=`<label for="address">Адрес: <span>[[+address]]</span></label>`]]
			    [[+zip:notempty=`<label for="zip">Почтовый индекс: <span>[[+zip]]</span></label>`]]
          [[+website:notempty=`<label for="website">Сайт: <span>[[+website:notempty=`<a href="http://[[+website]]">[[+website]]</a>`]]</span></label>`]]
  			</p>
			</div>
			<div class="edit-links three columns">
        <p>
          <a href="[[~270]]">Редактировать профиль</a><br>
          <a href="[[~269]]">Изменить пароль</a>
        </p>
      </div>
	  {else}
	    <script type="text/javascript">
        setTimeout('document.location.href="[[~261]]"', 0);
      </script>
  	{/if}
  </div>
  <div class="clearfix"></div>
	<div class="sixteen columns">
	  <div class="container cart" id="msCart">
      <table class="cart-table responsive-table">
        <tr>
          <th>Изображение</th>
          <th>{'ms2_cart_title' | lexicon}</th>
          <th>Номер заказа</th>
          <th>Дата заказа</th>
          <th>{'ms2_cart_count' | lexicon}</th>
          <th>{'ms2_cart_weight' | lexicon}</th>
          <th>Цена</th>
          <th>{'ms2_cart_cost' | lexicon}</th>
          <th>Доставка</th>
          <th>Сумма</th>
          <th>Статус</th>
        </tr>
      {$_modx->runSnippet('!getOrders')}
      </table>
    </div>
	</div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}