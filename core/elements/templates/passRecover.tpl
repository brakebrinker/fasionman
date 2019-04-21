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
		[[!ForgotPassword? 
    	&tpl=`AuthFogotPassForm` 
      &errTpl=`AuthFogotPassErr` 
      &sentTpl=`AuthForgotPassSent`
      &emailTpl=`ActivateForgotPassEmail` 
      &emailSubject=`Восстановление доступа к аккаунту Montirovka.by` 
      &resetResourceId=`263`
    ]]
	</div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}