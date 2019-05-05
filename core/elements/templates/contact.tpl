{extends 'file:templates/_base.tpl'} 
{block 'content'}
<!-- Content
================================================== -->

<!-- Container -->
<div class="container fullwidth-element">

  <!-- Google Maps -->
  <section class="google-map-container">
    <div id="googlemaps" class="google-map google-map-full">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2349.89229256864!2d27.576958015998596!3d53.915889839597845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46dbcfcbc31fa473%3A0x9788ceafe91de01a!2zIk1lbidzIEZhc2hpb24iINCc0LDQs9Cw0LfQuNC9INC80YPQttGB0LrQvtC5INC-0LTQtdC20LTRiyDQuCDQvtCx0YPQstC4!5e0!3m2!1sru!2sby!4v1557080814217!5m2!1sru!2sby" width="100%" height="245" frameborder="0" style="border:0" allowfullscreen></iframe>
      {*<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=eg0iLlQxAr7q5eV8HZK78l30SD4xaYrs&amp;width=100%&amp;height=100%&amp;lang=ru_RU&amp;sourceType=constructor&amp;scroll=false"></script>*}
    </div>
  </section>
  <!-- Google Maps / End -->

</div>
<!-- Container / End -->


<!-- Container -->
<div class="container">
  <div class="four columns">

    <!-- Information -->
    <div class="widget margin-top-10">
      <div class="accordion">

        <!-- Section 1 -->
        <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-file-text"></i> Информация</h3>
        <div>
          <p>{$_modx->resource.contact_info}</p>
        </div>

        <!-- Section 2 -->
        <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-thumb-tack"></i> Адрес</h3>
        <div>
          <ul class="contact-informations second">
            <li><p>{$_modx->resource.contact_address}</p></li>
            <li><p>{$_modx->resource.contact_city}</p></li>
          </ul>
        </div>

        <!-- Section 3 -->
        <h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-user"></i> Контакты</h3>
        <div>
          <ul class="contact-informations second">
            <li><i class="fa fa-phone"></i>
              <p>{$_modx->config.store_phone}</p>
            </li>
            <li><i class="fa fa-envelope"></i>
              <p>{$_modx->config.store_email}</p>
            </li>
            <li><i class="fa fa-globe"></i>
              <p><a href="{$_modx->config.store_site}">{$_modx->config.store_site}</a></p>
            </li>
          </ul>
        </div>

      </div>
    </div>

    <!-- Social -->
    <div class="widget">
      <h3 class="headline">Мы в соцсетях</h3><span class="line margin-bottom-25"></span>
      <div class="clearfix"></div>
      {insert 'file:chunks/socialIcons.tpl'}
      <div class="clearfix"></div>
      <br>
    </div>

  </div>

  <!-- Contact Form -->
  <div class="twelve columns">
    <div class="extra-padding left">
      <h3 class="headline">Обратная связь</h3><span class="line margin-bottom-25"></span>
      <div class="clearfix"></div>

      <!-- Contact Form -->
      <section id="contact">

        <!-- Success Message -->
        <mark id="message"></mark>

        <!-- Form -->      
        [[!FormIt?
           &hooks=`spam,email`
           &emailTpl=`sendEmailTpl`
           &emailTo=`info@montirovka.by`
           &emailSubject=`Письмо с сайта [[++site_url]]`
           &validate=`name:required,email:email:required,text:required:stripTags`
        ]]
        [[!+fi.error_message:notempty=`<p>[[!+fi.error_message]]</p>`]]
        <form action="[[~[[*id]]]]" method="post">
            <input type="hidden" name="nospam:blank" value="" />
            <fieldset>
              <div>
                <label for="name">Имя: <span>*</span></label>
                <span class="error">[[!+fi.error.name]]</span>
                <input type="text" name="name" id="name" value="[[!+fi.name]]" />
              </div>

              <div>
                <label for="email">Email: <span class="required">*</span></label>
                <span class="error">[[!+fi.error.email]]</span>
                {ignore}
                <input type="text" name="email" id="email" pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$" value="[[!+fi.email]]" />
                {/ignore}
              </div>

              <div>
                <label for="text">Сообщение: <span class="required">*</span></label>
                <span class="error">[[!+fi.error.text]]</span>
                <textarea name="text" id="text" cols="40" rows="3" 
                   value="[[!+fi.text]]" spellcheck="true">[[!+fi.text]]</textarea>
              </div>
            </fieldset>
            <div id="result"></div>
            <input type="submit" class="submit" id="submit" value="Отправить" />
            
            <div class="clearfix"></div>
        </form>
      </section>
      <!-- Contact Form / End -->
    </div>
  </div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>

{/block}
