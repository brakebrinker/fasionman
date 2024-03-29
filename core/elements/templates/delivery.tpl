{extends 'file:templates/_base.tpl'} 
{block 'content'} 
{insert 'file:chunks/titlebar.tpl'}

<div class="container">
  <div class="eleven columns">
    <h3 class="headline">Доставка</h3><span class="line margin-bottom-35"></span>
    <div class="clearfix"></div>
    <!-- Accordion -->
    <p>После размещения Вашего заказа в нашем интернет-магазине мы обрабатываем его в течении 1-3 рабочих дней.</p>
    <div class="accordion">
      <!-- Section 1 -->
      <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-child"></i> Доставка курьером</h3>
      <div>
        <p>Осуществляется только по городу Витебск в рабочие дни с 8:00 до 22:00. Бесплатно.</p>
      </div>

      <!-- Section 2 -->
      <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-smile-o"></i> Самовывоз</h3>
      <div>
        <p>Возможен вариант самостоятельно забрать товар (не раньше, чем на следующий день после заказа). Для этого при оформлении заказа в нашем интернет-магазине в поле "Способ доставки" указывайте вариант "Самовывоз". <strong>ВНИМАНИЕ! Физического магазина у нас нет.</strong> <strong>ОБЯЗАТЕЛЬНО!</strong> Звонить ПРЕДВАРИТЕЛЬНО перед тем, как соберетесь ехать за заказом, <strong>Вы сэкономите свое время. +375 33 3466539</strong></p>
      </div>

      <!-- Section 3 -->
      <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-envelope"></i> Почтовая пересылка</h3>
      <div>
        <p>Может быть как с наложенным платежом так и без него, с предоплатой.</p>
        <p>Если Вы выбрали этот вариант доставки, то мы отправляем заказ по указанному Вами адресу. 
        На Ваш e-mail мы высылаем номер посылки, для того, чтобы вы могли самостоятельно отследить ее местонахождение непосредственно на сайте 
        РУП "Белпочта". Обычная посылка по Беларуси идет в течении 3-5 рабочих дней с момента ее отправления. 
        Если по каким-то причинам вы не получили Ваш заказ, пожалуйста, свяжитесь с нами для решения этого вопроса. 
        После отправки Вашего заказа мы сообщим Вам по электронной почте стоимость заказа с учетом доставки, чтобы Вы знали сумму наложенного платежа 
        перед тем, как отправиться за посылкой на почту.</p>
        <p>Стоимость доставки расчитывается автоматически при оформлении заказа.</p>
        
      </div>

      <!-- Section 4 -->
      <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-space-shuttle"></i> EMS-Belpost</h3>
      <div>
        <p>В случае выбора вида доставки EMS-Belpost (ускоренная почта) мы отправляем Ваш заказ по указанному адресу. Данный вид пересылки позволяет получить посылку по Беларуси на следующий за отправленным рабочий день. Если по каким-то причинам Вы не получили заказ, пожалуйста, свяжитесь с нами для решения этого вопроса.</p>
        <p>Стоимость услуг по доставке заказов рассчитывается согласно тарифам EMS РУБ "Белпочта" для юридических лиц. Вид отправления - "Товары"</p>
        <p><strong>ВНИМАНИЕ! Данный вид доставки возможет только после внесения 100% предоплаты за Ваш заказ!</strong></p>
      </div>
    </div>
    <!-- Accordion / End -->
    <div class="margin-top-30"></div>
    <h3 class="headline">Возврат</h3><span class="line margin-bottom-35"></span>
    <div class="clearfix"></div>
    <div>
      <p>Уважаемые покупатели! Все вопросы касательно обмена и возврата приобретенного товара осуществляются между Продавцом и Покупателем посредством 
      переговоров и регулируются Правилами осуществления розничной торговли по образцам, Законом «О защите прав потребителей», Законом «О рекламе». 
      В случае, если Покупатель не забрал свой товар на почте и он вернулся к Продавцу, Покупатель компенсирует стоимость, затраченную на пересылку товара.</p>
      <div class="accordion">
      <!-- Section 1 -->
        <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-archive"></i> Возврат товара</h3>
        <div>
          <p>Вы можете вернуть приобретенный вами товар в случае:</p>
          <ul class="list-4 color">
            <li>- Если товар имеет заводской брак (гарантийный случай). Товар отправляется на экспертизу либо диагностику в ремонтную организацию для определения неисправности.</li>
            <li>- Если изделие (не упаковка или коробка) повреждено при транспортировке. Внимание! Перед отправкой, все товары проверяются на наличие механических повреждений.</li>
          </ul>
          <p><strong>Обращаем ваше внимание. Возврат товара осуществляется за ваши средства.</strong></p>
          <p><em>С условиями гарантии на товар вы можете ознакомиться на <a href="{$_modx->makeUrl(450)}">этой странице</a></em></p>
        </div>
        <!-- Section 2 -->
        <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-money"></i> Возврат денег</h3>
        <div>
          <ul class="list-4 color">
            <li>- Если товар был оплачен картой через сайт, то возврат осуществляется на карту, с которой была произведена оплата, а срок поступления денежных средств на карту - от 3 до 30 дней с момента осуществления возврата Продавцом. </li>
            <li>- Если товар был оплачен наличными через курьера, то возврат осуществляется так же курьером по предварительной договоренности в течении 2-3 дней. </li>
            <li>- Если товар был оплачен наложенным платежом, то возврат денег осуществляется почтовым переводом, через почтовое отделение. </li>
          </ul>
        </div>
        <!-- Section 3 -->
        <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-exchange"></i> Отмена заказа и возврат денег</h3>
        <div>
          <p>Вы имеете возможность отменить заказ и вернуть свои средства предложенными выше способами, если товар еще не был отправлен в службу доставки.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="five columns">
    <h3 class="headline">Карта самовывоза</h3><span class="line margin-bottom-35"></span>
        <div id="googlemaps" class="google-map google-map-full">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2349.89229256864!2d27.576958015998596!3d53.915889839597845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46dbcfcbc31fa473%3A0x9788ceafe91de01a!2zIk1lbidzIEZhc2hpb24iINCc0LDQs9Cw0LfQuNC9INC80YPQttGB0LrQvtC5INC-0LTQtdC20LTRiyDQuCDQvtCx0YPQstC4!5e0!3m2!1sru!2sby!4v1557080814217!5m2!1sru!2sby" width="100%" height="245" frameborder="0" style="border:0" allowfullscreen></iframe>
          {*<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=BOpEznPmo2J7nkZi3RDEFGsmhqKejJsj&amp;width=100%&amp;height=100%&amp;lang=ru_RU&amp;sourceType=constructor&amp;scroll=true"></script>*}
        </div>

    <div class="clearfix"></div>
  </div>
</div>
<div class="margin-top-50"></div>
{/block}