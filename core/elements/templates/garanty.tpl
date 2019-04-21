{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">
  <div class="four columns">

    <!-- Categories -->
    <div class="widget margin-top-0">
      <h3 class="headline">Категории</h3><span class="line"></span>
      <div class="clearfix"></div>
      {$_modx->runSnippet('pdoMenu', [
        'parents' => '4',
        'level' => '0',
        'tplOuter' => '@INLINE <ul id="categories">{$wrapper}</ul>',
        'tpl' => '@INLINE <li><a href="{$link}">{$menutitle}  <span>({$children})</span></a>{$wrapper}</li>',
        'tplInner' => '@INLINE <ul>{$wrapper}</ul>',
        'countChildren' => '1',
        'cache' => '1',
        'cacheTime' => '3600'
      ])}
      
      <div class="clearfix"></div>
    </div>

  </div>
	<div class="twelve columns">
  	<!-- Login -->
		<h3 class="headline">Гарантия на товар</h3><span class="line margin-bottom-35" ></span><div class="clearfix"></div>
    <!-- Accordion -->
			<div class="accordion">

				<!-- Section 1 -->
				<h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-check"></i> HITACHI KIRK</h3>
				<div>
				  <p>Гарантия на оборудование составляет 1 год, если вы не получили расширенную гарантию.</p>
				  <h4>Расширенная гарантия</h4>
				  <p>Как получить три года гарантии вместо одного? Предложение действует для всей линейки инструмента HITACHI, а также для сварочных аппаратов, 
				  компрессоров и бензогенераторов KIRK. Предложение не распространяется на оснастку, принадлежности, расходные материалы, а так же на аккумуляторы и 
				  зарядные устройства. Список ремонтируемого оборудования: газонокосилки, электро-бензотриммеры, электро-бензопилы, электро-бензоножницы, 
				  электро-бензовоздуходувки, дрели, электролобзики, сабельные пилы, шуруповерты, гайковерты, отбойные молотки, погружные насосы, перфораторы, миксеры, 
				  отрезные машины, штроборезы, пилы циркулярные, пилы торцовочные, полировальные машины, пылесосы, электрорубанки, термофены, фрезеры, 
				  углошлифовальные машины, шлифмашины, электроотвертки, сварочное оборудование, генераторы, компрессоры, мотопомпы, бетоносмесители и пр.</p>
				  <ul class="list-4 color">
            <li>1. Приобретите инструмент в нашем магазине. Не забывайте проверять наличие сертификата качества при приобретении инструментов.</li>
            <li>2. <noindex><a href="http://ekt.by/personal/service/new/" rel="nofollow" target="blank" style="text-decoration: underline">Зарегистрируйте оборудование</a></noindex> на сайте в течении 30 дней с момента покупки. Если в течение 30 дней с момента покупки регистрация не произведена — гарантия на оборудование составляет 1 год.</li>
            <li>3. Проводите плановое ТО оборудования согласно установленному регламенту. Дата ближайшего технического обслуживания по каждому из товаров отображается автоматически в <noindex><a href="http://ekt.by/personal/service/registered/" rel="nofollow" target="blank" style="text-decoration: underline">личном кабинете</a></noindex>.</li>
				  </ul>
				  <p><a href="{'assets_url' | option}docs/warranty_ekt_ru-by.pdf" target="blank">Скачать условия гарантии PDF</a></p>
				</div>

				<!-- Section 2 -->
				<h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-check"></i> BOSCH</h3>
				<div>
				  <p>Гарантия на оборудование составляет 1 год, если гарантия не продлена.</p>
				  <h4>Продление гарантии на 3 года</h4>
				  <p>Мы продлили для вас срок гарантии! Профессиональные электроинструменты Bosch отвечают высшим стандартам качества! Поэтому мы продлеваем срок гарантии. На все профессиональные электрические и измерительные инструменты вы получаете три года гарантии — причём бесплатно!
          <p><strong>За исключением высокочастотных инструментов, промышленных аккумуляторных шуруповёртов и пневмоинструментов, входящих в комплект поставки принадлежностей, а также аккумуляторных блоков и зарядных устройств.</strong></p>
          <p>Для этого вам необходимо:</p>
				  <ul class="list-4 color">
            <li>1. Зарегистрируйтесь в качестве пользователя на <noindex><a href="https://webapp3.bosch.de/warranty/locale.do" rel="nofollow" target="blank" style="text-decoration: underline">этом сайте</a></noindex> в течение 4 недель после покупки. Срок гарантии начинается с даты покупки.</li>
            <li>2. На своей персональной странице регистрации зарегистрируйте электроинструмент для продления его гарантии.</li>
            <li>3. Распечатать гарантийный сертификат в формате PDF и отредактировать пользовательские данные.</li>
				  </ul>
				  <p><em>Гарантийные сертификаты, как правило, действительны для конкретных машин. Это значит, что любой купленный инструмент подлежит отдельной 
				  регистрации. В гарантийном случае представьте нам сертификат вместе с оригинальным товарным чеком. 
				  Даты на обоих документах должны совпадать.</em></p>
				  <p><a href="{'assets_url' | option}docs/warranty_ru-by.pdf" target="blank">Скачать условия гарантии PDF</a></p>
				</div>

				<!-- Section 3 -->
				<h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-check"></i> VERTO GRAPHITE</h3>
				<div>
					<p>Гарантия на оборудование составляет 1 год.</p>
				</div>
				
				<!-- Section 4 -->
				<h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-check"></i> SKIL DREMEL</h3>
				<div>
					<p>Гарантия на оборудование составляет 1 год.</p>
				</div>
				
				<!-- Section 5 -->
				<h3><span class="ui-accordion-header-icon ui-icon ui-accordion-icon"></span><i class="fa fa-check"></i> ФИОЛЕНТ</h3>
				<div>
					<p>Гарантия на оборудование составляет 1 год.</p>
				</div>

			</div>
			<!-- Accordion / End -->
	</div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}