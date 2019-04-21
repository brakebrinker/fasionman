<form class="ms2_form" id="msOrder" method="post">
  <div class="eight columns">
    <!-- Billing Details Content -->
    <div class="checkout-section active"><span>1</span> Платежные данные</div>
    <div class="checkout-content">

      <div class="half first input-parent">
        <label class="control-label" for="receiver">ФИО: <span class="required-star">*</span> </label>
        <input type="text" id="receiver" class="form-control{('receiver' in list $errors) ? ' error' : ''}" name="receiver" placeholder="Иванов Иван Иванович" 
        {if $_modx->user.id > 0}
          value="{$_modx->user.fullname}"
        {else}
          value=""
        {/if}
        />
      </div>
      <div class="half input-parent">
        <label>Адрес: <span class="required-star">*</span></label>
        <input type="text" id="street" class="form-control{('street' in list $errors) ? ' error' : ''}" name="street" placeholder="улица, дом, квартира" value="" />
      </div>
      <div class="fullwidth input-parent">
        <label class="billing control-label" for="country">Страна: <span class="required-star">*</span></label>
        <select name="country" class="input-text" id="country" style="height:40px; width:100%">
          [[!FormItCountryOptions? &selected=`{$_modx->user.country}` &prioritized=`BY,RU,KZ,UA`]]
        </select>
      </div>
      <div class="half first input-parent">
        <label class="control-label" for="city">Город / Населенный пункт: <span class="required-star">*</span></label>
        <input type="text" id="city" class="form-control{('city' in list $errors) ? ' error' : ''}" name="city" placeholder="Витебск" value="{$_modx->user.city}" />
      </div>
      <div class="half input-parent">
        <label class="control-label" for="index">Почтовый индекс: <span class="required-star">*</span></label>
        <input type="text" id="index" class="form-control{('index' in list $errors) ? ' error' : ''}" name="index" placeholder="211216" value="{$_modx->user.index}" />
      </div>
      <div class="fullwidth input-parent">
        <label class="control-label" for="region">Область / Регион: <span class="required-star">*</span></label>
        <input type="text" id="region" class="input-text form-control{('region' in list $errors) ? ' error' : ''}" name="region" placeholder="Витебская обл., Лиозненский р-н" value="" />
      </div>

    <!--
      <div class="fullwidth">
        <label>Область / Регион: <abbr>*</abbr></label>
        <select>
          <option value="">Select a state&hellip;</option>
          <option value="AL">Alabama</option>
          <option value="AK">Alaska</option>
          <option value="AZ">Arizona</option>
          <option value="AR">Arkansas</option>
          <option value="CA">California</option>
          <option value="CO">Colorado</option>
          <option value="CT">Connecticut</option>
          <option value="DE">Delaware</option>
          <option value="DC">District Of Columbia</option>
          <option value="FL">Florida</option>
          <option value="GA">Georgia</option>
          <option value="HI">Hawaii</option>
          <option value="ID">Idaho</option>
          <option value="IL">Illinois</option>
          <option value="IN">Indiana</option>
          <option value="IA">Iowa</option>
          <option value="KS">Kansas</option>
          <option value="KY">Kentucky</option>
          <option value="LA">Louisiana</option>
          <option value="ME">Maine</option>
          <option value="MD">Maryland</option>
          <option value="MA">Massachusetts</option>
          <option value="MI">Michigan</option>
          <option value="MN">Minnesota</option>
          <option value="MS">Mississippi</option>
          <option value="MO">Missouri</option>
          <option value="MT">Montana</option>
          <option value="NE">Nebraska</option>
          <option value="NV">Nevada</option>
          <option value="NH">New Hampshire</option>
          <option value="NJ">New Jersey</option>
          <option value="NM">New Mexico</option>
          <option value="NY">New York</option>
          <option value="NC">North Carolina</option>
          <option value="ND">North Dakota</option>
          <option value="OH">Ohio</option>
          <option value="OK">Oklahoma</option>
          <option value="OR">Oregon</option>
          <option value="PA">Pennsylvania</option>
          <option value="RI">Rhode Island</option>
          <option value="SC">South Carolina</option>
          <option value="SD">South Dakota</option>
          <option value="TN">Tennessee</option>
          <option value="TX">Texas</option>
          <option value="UT">Utah</option>
          <option value="VT">Vermont</option>
          <option value="VA">Virginia</option>
          <option value="WA">Washington</option>
          <option value="WV">West Virginia</option>
          <option value="WI">Wisconsin</option>
          <option value="WY">Wyoming</option>
          <option value="AA">Armed Forces (AA)</option>
          <option value="AE">Armed Forces (AE)</option>
          <option value="AP">Armed Forces (AP)</option>
          <option value="AS">American Samoa</option>
          <option value="GU">Guam</option>
          <option value="MP">Northern Mariana Islands</option>
          <option value="PR">Puerto Rico</option>
          <option value="UM">US Minor Outlying Islands</option>
          <option value="VI">US Virgin Islands</option>
        </select>
      </div>
    -->


      <div class="half first input-parent">
        <label class="control-label" for="email">Email: <span class="required-star">*</span></label>
        <input type="text" id="email" class="form-control{('email' in list $errors) ? ' error' : ''}" name="email" placeholder="example@mail.com" value="{$_modx->user.email}" />
      </div>
      <div class="half input-parent">
        <label class="control-label" for="phone">Телефон: <span class="required-star">*</span></label>
        <input type="text" id="phone" class="form-control{('phone' in list $errors) ? ' error' : ''}" name="phone" placeholder="+375 xx xxx-xx-xx " value="{$_modx->user.phone}" />
      </div>


      <div class="clearfix"></div>



    <!--  <span class="shippping-checkbox"><input class="input-checkbox" id="shipping-address"  type="checkbox" name="shipping-address" checked="checked" value="1" />
              <label for="shipping-address" class="checkbox">Платежный адрес такой же как и адрес доставки</label></span>-->

    </div>

    <!-- Billing Details / Enc -->

    <div class="clearfix"></div>
   
    <!-- Delivery -->

    <div class="checkout-section active"><span>2</span> Доставка</div>
    <div id="deliveries" class="checkout-delivery active">
      <div class="form-group">
        <div class="col-sm-6">
          {var $i = 0}
          {foreach $deliveries as $idx => $delivery}
            {var $checked = !$order.delivery && $i == 0 || $delivery.id == $order.delivery}
            {var $i += 1}
            <div class="checkbox">
              <label class="delivery input-parent">
                <input type="radio" name="delivery" value="{$delivery.id}" id="delivery_{$delivery.id}"
                       data-payments="{$delivery.payments | json_encode}"
                        {$checked ? 'checked' : ''}>
                {if $delivery.logo?}
                    <img src="{$delivery.logo}" alt="{$delivery.name}" title="{$delivery.name}"/>
                {else}
                    {$delivery.name}
                {/if}
                {if $delivery.description?}
                    <p class="small">
                      <em>{$delivery.description}</em>
                    </p>
                {/if}
              </label>
            </div>
          {/foreach}
        </div>
      </div>
    </div>

    <!-- Delivery / End -->


    <!-- Payment -->
    <div class="checkout-section active"><span>3</span> Оплата и просмотр заказа</div>
    <div class="checkout-summary">

      <div id="payments" class="eight columns alpha omega">
        <div class="form-group">
          <label class="col-md-4 control-label"><span class="required-star">*</span>
              {'ms2_frontend_payment_select' | lexicon}</label>
          <div class="col-sm-6">
            {foreach $payments as $payment}
              <div class="checkbox">
                <label class="payment input-parent">
                  <input type="radio" name="payment" value="{$payment.id}" id="payment_{$payment.id}"
                    {$payment.id == $order.payment ? 'checked' : ''}>
                  {if $payment.logo?}
                    <img src="{$payment.logo}" alt="{$payment.name}" title="{$payment.name}"/>
                  {else}
                    {$payment.name}
                  {/if}
                  {if $payment.description?}
                    <p class="small">
                      <em>{$payment.description}</em>
                    </p>
                  {/if}
                </label>
              </div>
            {/foreach}
          </div>
        </div>
      </div>
    </div>

    <!-- Payment / Enc -->
    
    <div class="clearfix"></div>
    
    <div class="total-order">{'ms2_frontend_order_cost' | lexicon}:
        <span id="ms2_order_cost">{$order.cost ?: 0}</span>
        {'ms2_frontend_currency' | lexicon}
    </div>  
    
    <div class="margin-top-30"></div>
    {*<div class="warning-message">*}
      {*<img src="{'assets_url' | option}images/warning.png" alt=""><p> <strong>Внимание!</strong> Перед совершением оплаты товара через <strong>"систему ЕРИП"</strong> или <strong>"Банковской картой"</strong> свяжитесь с менеджером по тел. +375 (33) 346-65-39 или воспользовавшись <a href="{$_modx->makeUrl(18)}">формой обратной связи</a>, и уточните наличие товара на складе. *}
      {*Так как при оплате товара через систему ЕРИП возврат денежных средств за отсутствующий товар <strong>невозможен</strong>, вы можете <strong>заморозить свои деньги на 2 - 3 дня</strong>, пока мы не найдем другие способы их вернуть.</p>*}
    {*</div>*}
    <button type="submit" name="ms2_action" value="order/submit" class="continue button color btn btn-default btn-primary ms2_link">
        {'ms2_frontend_order_submit' | lexicon}
    </button>

  </div>
</form>