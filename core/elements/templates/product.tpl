{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<div class="container">

  {$_modx->runSnippet('!msGallery', [
    'tpl' => '@FILE chunks/productGallery.tpl',
    'product' => $_modx->resource.id
  ])}


  <!-- Content ================================================== -->
  <div class="eight columns">
    <div class="product-page">
      <form class="ms2_form" method="post">
        <input type="hidden" name="id" value="{$_modx->resource.id}" />
        <!-- Headline -->
        <section class="title">
          <h2>{$_modx->resource.pagetitle}</h2>
          {if $old_price?}
            <span class="product-price-discount">{$old_price} {'ms2_frontend_currency' | lexicon}<i>{$price} {'ms2_frontend_currency' | lexicon}</i></span>
          {else}
            <span class="product-price">{$price} {'ms2_frontend_currency' | lexicon}</span>
          {/if}
        </section>

        <!-- Text Parapgraph -->
        <section>
<!--
          <p class="margin-reset">{$_modx->runSnippet('!msProductOptions', [
          'tpl' => '@FILE chunks/productOptionsIntro.tpl',
          'groups' => '31,32',
        ])}</p>
-->
          <div class="vendor-product">
            {if $_pls['vendor.name']}
              Производитель: {if $_pls['vendor.logo']}<img src="{$_pls['vendor.logo']}" alt="">{else}{$_pls['vendor.name']}{/if}<br>
              {$_pls['vendor.country']} {$_pls['vendor.address']}<br>
              {$_pls['vendor.description']}<br>
            {/if}
            {if $made_in}
              <strong>Страна изготовления: </strong>{$made_in}
            {/if}
          </div>

          <!-- Share Buttons -->
<!--
          <div class="share-buttons">
            <ul>
              <li><a href="#">Share</a></li>
              <li class="share-vk"><a href="#">Vkontakte</a></li>
              <li class="share-twitter"><a href="#">Twitter</a></li>
              <li class="share-facebook"><a href="#">Facebook</a></li>
              <li class="share-gplus"><a href="#">Google Plus</a></li>
            </ul>
          </div>
          <div class="clearfix"></div>
-->

        </section>
        <section class="linking">
            <div class="produrt-size-wrapper">
                {if $_modx->resource.size}
                    <label for="product-size" class="label-size">Размер: </label>
                    <select name="options[size]" id="product_size">
                    {foreach $_modx->resource.size as $size}
                        <option value="{$size}">{$size}</option>
                    {/foreach}
                    </select>
                {/if}
            </div>

            <div class="qtyminus"></div>
            <input type="text" name="count" id="product_price" value="1" class="qty" />
            <div class="qtyplus"></div>
            <button type="submit" class="button adc" name="ms2_action" value="cart/add">В корзину</button>
            <div class="clearfix"></div>
        </section>
      </form>
    </div>
  </div>

</div>


<div class="container">
  <div class="sixteen columns">
    <!-- Tabs Navigation -->
    <ul class="tabs-nav">
      <!--<li class="active"><a href="#tab1">Описание товара</a></li>-->
      <li><a href="#tab1">Описание товара</a></li>
      <li><a href="#tab2">Комментарии <span class="tab-reviews"></span></a></li>
    </ul>

    <!-- Tabs Content -->
    <div class="tabs-container">

      <div class="tab-content" id="tab1">
        <p>{$_modx->resource.description}</p>
      </div>

      {*<div class="tab-content" id="tab1">*}
       {**}
        {*<div class="option-header">Основные</div>*}
        {*{$_modx->runSnippet('!msProductOptions', [*}
          {*'tpl' => '@FILE chunks/productOptions.tpl',*}
          {*'groups' => '31',*}
        {*])}*}
        {*<div class="option-header">Технические характеристики</div>*}
        {*{$_modx->runSnippet('!msProductOptions', [*}
          {*'tpl' => '@FILE chunks/productOptions.tpl',*}
          {*'groups' => '32',*}
        {*])}*}
        {*<div class="option-header">Функциональные особенности</div>*}
        {*{$_modx->runSnippet('!msProductOptions', [*}
          {*'tpl' => '@FILE chunks/productOptions.tpl',*}
          {*'groups' => '33',*}
        {*])}*}
        {*<div class="option-header">Комплектация</div>*}
        {*{$_modx->runSnippet('!msProductOptions', [*}
          {*'tpl' => '@FILE chunks/productOptions.tpl',*}
          {*'groups' => '35',*}
        {*])}*}
        {*<div class="option-header">Аккумулятор</div>*}
        {*{$_modx->runSnippet('!msProductOptions', [*}
          {*'tpl' => '@FILE chunks/productOptions.tpl',*}
          {*'groups' => '36',*}
        {*])}*}
      {**}
      {*</div>*}

      <div class="tab-content" id="tab2">
        <!-- Reviews -->
        <section class="comments">
          <ul>
            {$_modx->runSnippet('!TicketComments', [
              'tplLoginToComment' => '',
              'tplCommentFormGuest' => '',
              'tplCommentForm' => '',
              'tplCommentGuest' => '@FILE chunks/guestCommentShow.tpl',
              'tplComments' => '@FILE chunks/commentsWrapper.tpl',
            ])}
          </ul>

          <a href="#small-dialog" class="popup-with-zoom-anim button color margin-left-0">Добавить комментарий</a>

          <div id="small-dialog" class="zoom-anim-dialog mfp-hide">
            <h3 class="headline">Добавление комментария</h3><span class="line margin-bottom-25"></span>
            <div class="clearfix"></div>
            {$_modx->runSnippet('!TicketComments', [
              'allowGuest' => '1',
              'tplComments' => '@FILE chunks/commentsFormWrapper.tpl',
              'tplCommentFormGuest' => '@FILE chunks/guestCommentForm.tpl',
            ])}
          </div>

        </section>

      </div>

    </div>
  </div>
</div>

<!-- Related Products -->
<div class="container margin-top-5">

  <!-- Headline -->
  <div class="sixteen columns">
    <h3 class="headline">Рекомендованные товары</h3>
    <span class="line margin-bottom-0"></span>
  </div>

  <!-- Products -->
  <div class="products">
    {$_modx->runSnippet('!msProducts', [
      'parents' => $_modx->resource.parent,
      'tpl' => '@FILE chunks/productRelated.tpl',
      'limit' => 4,
    ])}

  </div>
</div>

<div class="margin-top-50"></div>
{/block}