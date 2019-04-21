{set $rows = json_decode($_modx->resource.sliderhome, true)}
{foreach $rows as $row}
<li data-transition="{$row.slstyle ? $row.slstyle : 'fade'}" data-slotamount="7" data-masterspeed="1500">
  <img src="{$row.image}" alt="{$row.alt}" data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
  <div class="caption {$row.color} sfb fadeout" data-x="{$row.xposition ? $row.xposition : '145'}" data-y="170" data-speed="400" data-start="800" data-easing="Power4.easeOut">
    <h2>{$row.alt}</h2>
    <h3>{$row.description}</h3>
    <a href="{$row.url ? $row.url : $_modx->makeUrl(4)}" class="caption-btn">Посмотреть предложение</a>
  </div>
</li>
{/foreach}