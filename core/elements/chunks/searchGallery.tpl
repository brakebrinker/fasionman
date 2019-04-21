{if $files?} 
{foreach $files as $file}
    <img class="rsImg" src="{$file['170x']}" alt="" title="">
{/foreach}
{else}
  <img src="{('assets_url' | option) ~ 'images/no-photo-big.png'}" alt="" title="" /> 
{/if}