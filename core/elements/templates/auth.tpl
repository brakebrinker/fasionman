{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">
	<div class="six columns centered">
  	<!-- Login -->
		<h3 class="headline">Вход на сайт</h3><span class="line" style="margin-bottom:20px;"></span><div class="clearfix"></div>
    [[!Login? 
      &loginTpl=`AuthLoginForm` 
      &logoutTpl=`lgnLogoutTpl` 
      &errTpl=`lgnErrTpl`
      &loginResourceId=`268`
    ]]
	</div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}