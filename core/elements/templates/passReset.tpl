{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">
	<div class="six columns centered">
  	<!-- Login -->
		<h3 class="headline">Восстановление пароля</h3><span class="line" style="margin-bottom:20px;"></span><div class="clearfix"></div>
    [[!ResetPassword:empty=`<div class="alert alert-warning">
      <p>Вы не заказывали сброс пароля. Возможно вы ошиблись адресом. Вы можете перейти на <a href="[[~1]]">Главную</a> страницу сайта или воспользоваться меню выше.</div>`? 
      &tpl=`AuthForgotPassReset`
    ]]</p>
	</div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}