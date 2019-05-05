<!-- Basic Page Needs
================================================== -->
<meta charset="utf-8">

<base href="{$_modx->config['site_url']}" />

<title>{$_modx->runSnippet('!pdoTitle', ['cache' => '1', 'outputSeparator' => ' | '])} | {$_modx->config.site_name}</title>

{if $_modx->resource.seokeywords?}
<meta name="keywords" content="{$_modx->resource.seokeywords}" />
{else}
<meta name="keywords" content="купить {$_modx->resource.pagetitle}, минск, беларусь" />
{/if}
{if $_modx->resource.seodescription?}
<meta name="description" content="{$_modx->resource.seodescription}" />
{else}
<meta name="description" content="Магазин инструментов для строительства и ремонта Montirovka.by - это возможность дешево купить {$_modx->resource.pagetitle} в Минске и во всей Беларуси" />
{/if}

<!-- Mobile Specific Metas
================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<style>
 @import url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&subset=cyrillic');
</style>
<!-- CSS
================================================== -->
<link rel="stylesheet" href="assets/css/style.min.css">
<link rel="stylesheet" href="assets/css/red.css" id="colors">

<link rel="icon" type="image/png" sizes="32x32" href="assets/images/znak.png">

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->