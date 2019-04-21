<!-- Titlebar ================================================== -->
<section class="titlebar">
  <div class="container">
    <div class="sixteen columns">
      <h1>{$_modx->resource.longtitle ? $_modx->resource.longtitle : $_modx->resource.pagetitle}</h1>
      <nav id="breadcrumbs">
        {$_modx->runSnippet ('pdoCrumbs', [
          'showHome' => '1',
        ])}
      </nav>
    </div>
  </div>
</section>