<?php /* Smarty version 3.1.27, created on 2019-04-20 11:52:21
         compiled from "E:\projects\www\fasionman\manager\templates\default\welcome.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:214085cbaebd5e6cd22_87668019%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5c3728749338fdb767f52b8005f1b2cf656160fc' => 
    array (
      0 => 'E:\\projects\\www\\fasionman\\manager\\templates\\default\\welcome.tpl',
      1 => 1480751022,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '214085cbaebd5e6cd22_87668019',
  'variables' => 
  array (
    'dashboard' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5cbaebd5e6ea94_86068116',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5cbaebd5e6ea94_86068116')) {
function content_5cbaebd5e6ea94_86068116 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '214085cbaebd5e6cd22_87668019';
?>
<div id="modx-panel-welcome-div"></div>

<div id="modx-dashboard" class="dashboard">
<?php echo $_smarty_tpl->tpl_vars['dashboard']->value;?>

</div><?php }
}
?>