<!-- Top Bar
================================================== -->
<div id="top-bar">
  <div class="container">

    <!-- Top Bar Menu -->
    <div class="ten columns">
      <ul class="top-bar-menu">
        <li><i class="fa fa-phone"></i>{$_modx->config.store_phone}</li>
        <li><i class="fa fa-envelope"></i> <a href="mailto:{$_modx->config.store_email}">{$_modx->config.store_email}</a></li>
        {ignore}
<!--        <li><i class="fa fa-envelope"></i> <a href="#"><span class="__cf_email__" data-cfemail="fa979b9396ba9f829b978a969fd4999597">[email&#160;protected]</span><script data-cfhash='f9e31' type="text/javascript">/* <![CDATA[ */!function(t,e,r,n,c,a,p){try{t=document.currentScript||function(){for(t=document.getElementsByTagName('script'),e=t.length;e--;)if(t[e].getAttribute('data-cfhash'))return t[e]}();if(t&&(c=t.previousSibling)){p=t.parentNode;if(a=c.getAttribute('data-cfemail')){for(e='',r='0x'+a.substr(0,2)|0,n=2;a.length-n;n+=2)e+='%'+('0'+('0x'+a.substr(n,2)^r).toString(16)).slice(-2);p.replaceChild(document.createTextNode(decodeURIComponent(e)),c)}p.removeChild(t)}}catch(u){}}()/* ]]> */</script></a></li>-->
        {/ignore}
      </ul>
    </div>

    <!-- Social Icons -->
    <div class="six columns">
      {insert 'file:chunks/socialIcons.tpl'}
    </div>

  </div>
</div>

<div class="clearfix"></div>


<!-- Header
================================================== -->
<div class="container">


  <!-- Logo -->
  <div class="four columns">
    <div id="logo">
      <h1><a href="{$_modx->config['site_url']}"><img src="assets/images/logo_2.png" alt="Montirovka.by" /></a></h1>
    </div>
  </div>


  <!-- Additional Menu -->
  <div class="twelve columns">
    <div id="additional-menu">
      <ul>
        <li><a href="{$_modx->makeUrl(6)}">Корзина товаров</a></li>
<!--        <li><a href="wishlist.html">WishList <span>(2)</span></a></li>-->
        <li><a href="{$_modx->makeUrl(8)}">Оформить заказ</a></li>
        {if $_modx->isAuthenticated()}
          <li><a href="{$_modx->makeUrl(268)}">Мой аккаунт</a></li>
          <li><a href="{$_modx->makeUrl(261)}?service=logout">Выйти</a></li>
        {else}
          <li><a href="{$_modx->makeUrl(261)}">Войти</a></li>
        {/if}
      </ul>
    </div>
  </div>


  <!-- Shopping Cart -->
  <div class="twelve columns">
  {'!msMiniCart' | snippet}
    <!-- Search -->
    <nav class="top-search">
      <a href="#search-block" class="popup-with-fade-anim">
      <form action="#" method="get">
        <button type="submit"><i class="fa fa-search"></i></button>
        <input type="text" class="search-field" placeholder="Поиск на сайте" value="" />
      </form>
      </a>
    </nav>
  </div>

</div>


<!-- Navigation
================================================== -->
<div class="container">
  <div class="sixteen columns">

    <a href="#menu" class="menu-trigger"><i class="fa fa-bars"></i> Меню</a>

    <nav id="navigation">
      {$_modx->runSnippet('pdoMenu', [
        'parents' => '0',
        'level' => '2',
        'tplOuter' => '@INLINE <ul class="menu" id="responsive">{$wrapper}</ul>',
        'tplParentRow' => '@INLINE <li><a href="{$link}">{$menutitle}</a><div class="mega">
						<div class="mega-container">{$wrapper}</div></div><li>',
        'tplInner' => '@FILE chunks/innerHeadMenu.tpl',
				'tplInnerRow' => '<li><a href="{$link}">{$menutitle}</a>{$wrapper}</li>',
        'firstClass' => 'homepage',
        'hereClass' => 'current',
      ])}
    </nav>
  
  </div>
</div>

<!-- Search block
================================================== -->
<div id="search-block" class="mfp-hide white-popup-block">
  <div class="popup-search">
  {$_modx->runSnippet('!mSearchForm', [
    'tplForm' => '@FILE chunks/mSearchForm.tpl',
    'element' => 'msProducts',
    'limit' => '20',
    'tpl' => '@FILE chunks/mSearchAutoCompl.tpl',
    'fields' => 'pagetitle',
  ])}
  </div>
  <div class="popup-search-result">
  </div>
</div>