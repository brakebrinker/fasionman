{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">

	<div class="sixteen columns">
		<section id="not-found">
			<h2>{$_modx->resource.pagetitle} <i class="fa fa-question-circle"></i></h2>
			<p>Мы сожалеем, но страница которую вы запрашиваете не существует.</p>
		</section>
	</div>

</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}