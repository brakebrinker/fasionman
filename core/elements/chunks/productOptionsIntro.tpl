{foreach $options as $option}
  {if $option.value is array}
    {if $option.measure_unit?}
      {$option.value | join : ', '} {$option.measure_unit};
    {else} 
      {$option.value | join : ', '};
    {/if}
  {else} 
    {if $option.measure_unit?}
      {$option.value} {$option.measure_unit};
    {else} 
      {$option.value};
    {/if}
  {/if}
{/foreach}