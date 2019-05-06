{if $files?}
  {foreach $files as $file}
    <img src="{$file['282x376']}" alt="{$longtitle ? $longtitle : $pagetitle}" title="{$longtitle ? $longtitle : $pagetitle}"/>
  {/foreach}
{else}
  <img src="{'assets_url' | option}images/no-photo-available.jpg"
    srcset="{'assets_url' | option}images/no-photo-available.jpg"
    alt="{$longtitle ? $longtitle : $pagetitle}" title="{$longtitle ? $longtitle : $pagetitle}"/>
{/if}