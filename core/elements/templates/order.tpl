{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">

  {$_modx->runSnippet('!msOrder', [
    'tpl' => '@FILE chunks/orderBilling.tpl',
  ])}

  <!-- Checkout Cart -->
  {$_modx->runSnippet('!msCart', [
    'tpl' => '@FILE chunks/orderCart.tpl',
  ])}

  <!-- Checkout Cart / End -->
  {$_modx->runSnippet('!msGetOrder', [
    'tpl' => '@FILE chunks/orderGetOrder.tpl',
  ])}
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}