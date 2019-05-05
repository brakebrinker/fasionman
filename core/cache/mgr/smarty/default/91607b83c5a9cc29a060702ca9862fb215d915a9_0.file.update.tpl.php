<?php /* Smarty version 3.1.27, created on 2019-05-04 18:21:55
         compiled from "E:\projects\www\fasionman\manager\templates\default\resource\weblink\update.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:7195ccdbc233ce4c0_94924053%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '91607b83c5a9cc29a060702ca9862fb215d915a9' => 
    array (
      0 => 'E:\\projects\\www\\fasionman\\manager\\templates\\default\\resource\\weblink\\update.tpl',
      1 => 1480752319,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7195ccdbc233ce4c0_94924053',
  'variables' => 
  array (
    'tvOutput' => 0,
    'onDocFormPrerender' => 0,
    'resource' => 0,
    '_config' => 0,
    'onRichTextEditorInit' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5ccdbc2387e2e0_38720743',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ccdbc2387e2e0_38720743')) {
function content_5ccdbc2387e2e0_38720743 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '7195ccdbc233ce4c0_94924053';
?>
<div id="modx-panel-weblink-div"></div>
<div id="modx-resource-tvs-div" class="modx-resource-tab x-form-label-left x-panel"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['tvOutput']->value)===null||$tmp==='' ? '' : $tmp);?>
</div>

<?php echo $_smarty_tpl->tpl_vars['onDocFormPrerender']->value;?>

<?php if ($_smarty_tpl->tpl_vars['resource']->value->richtext && $_smarty_tpl->tpl_vars['_config']->value['use_editor']) {?>
    <?php echo $_smarty_tpl->tpl_vars['onRichTextEditorInit']->value;?>

<?php }

}
}
?>