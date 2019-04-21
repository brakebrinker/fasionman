<table class="basic-table">
  {foreach $options as $option}
  <tr>
    <th>{$option.caption}</th>

    {if $option.value is array}
      <td>
      {$option.value | join : ', '} {$option.measure_unit}
      </td>
    {else} 
      <td>{$option.value} {$option.measure_unit}</td>
    {/if}
  </tr>
  {/foreach}
</table>