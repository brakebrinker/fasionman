{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">
	<div class="six columns centered">
  	<!-- Login -->
		<h3 class="headline">Редактирование данных</h3><span class="line" style="margin-bottom:20px;"></span><div class="clearfix"></div>
    [[!UpdateProfile]]
    <form class="form-horizontal well callme" action="[[~[[*id]]]]" method="post">
    [[+login.update_success:eq=`1`:then=`<div style="width: 617px;
        padding: 12px 12px 1px; background: #e9ffe9; color: #030; margin-left: 0;
        margin-bottom: 15px;"><p>[[%login.profile_updated? &namespace=`login` &topic=`updateprofile`]]</p>
        <p>Вы будете перенаправлены в <a href="[[~268]]">личный кабинет</a> <span id="timer_inp">через <b>2</b> секунд</span></p>
        <script type="text/javascript">
          setTimeout('document.getElementById("timer_inp").innerHTML = "через <b>1</b> секунды"', 1000);
          setTimeout('document.getElementById("timer_inp").innerHTML = "<b>прямо сейчас</b>"', 2000);
          setTimeout('document.location.href="[[~268]]"', 2000);
        </script>
    </div>`:else=``]]
      [[+message]]

      <p class="form-row form-row-wide">
      	<label for="fullname">ФИО:</label>
      	<input type="text" class="input-text" name="fullname" id="fullname" value="[[+fullname]]" />
      </p>

      <p class="form-row form-row-wide">
      	<label for="phone">Телефон:</label>
      	<input type="text" class="input-text" name="phone" id="phone" value="[[+phone]]" />
      </p>
      
      <p class="form-row form-row-wide">
      	<label for="mobilephone">Мобильный (второй) телефон:</label>
      	<input type="text" class="input-text" name="mobilephone" id="mobilephone" value="[[+mobilephone]]" />
      </p>

      <p class="form-row form-row-wide">
      	<label for="country">Страна:</label>
      	<select name="country" class="input-text" id="country" style="height:40px; width:100%">
          [[!FormItCountryOptions? &selected=`[[+country]]` &prioritized=`BY,RU,KZ,UA`]]
        </select>
      </p>
      
      <p class="form-row form-row-wide">
      	<label for="city">Город:</label>
      	<input type="text" class="input-text" name="city" id="city" value="[[+city]]" />
      </p>
      
      <p class="form-row form-row-wide">
      	<label for="address">Адрес:</label>
      	<input type="text" class="input-text" name="address" id="address" value="[[+address]]" />
      </p>
      
      <p class="form-row form-row-wide">
      	<label for="zip">Почтовый индекс:</label>
      	<input type="text" class="input-text" name="zip" id="zip" value="[[+zip]]" />
      </p>
      
      <p class="form-row form-row-wide">
      	<label for="website">Сайт:</label>
      	<input type="text" class="input-text" name="website" id="website" value="[[+website]]" />
      </p>

      <p class="form-row">
				<input type="submit" class="button btn primary" value="Сохранить" />
			</p>
			
    </form>
	</div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}