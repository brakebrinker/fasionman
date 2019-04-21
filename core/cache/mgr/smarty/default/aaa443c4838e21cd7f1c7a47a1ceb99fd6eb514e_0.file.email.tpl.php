<?php /* Smarty version 3.1.27, created on 2019-04-20 17:30:03
         compiled from "E:\projects\www\fasionman\manager\templates\default\element\tv\renders\input\email.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:258205cbb3afb590535_57085633%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aaa443c4838e21cd7f1c7a47a1ceb99fd6eb514e' => 
    array (
      0 => 'E:\\projects\\www\\fasionman\\manager\\templates\\default\\element\\tv\\renders\\input\\email.tpl',
      1 => 1480752923,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '258205cbb3afb590535_57085633',
  'variables' => 
  array (
    'tv' => 0,
    'style' => 0,
    'params' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5cbb3afb5b4510_02455311',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5cbb3afb5b4510_02455311')) {
function content_5cbb3afb5b4510_02455311 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '258205cbb3afb590535_57085633';
?>
<input id="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" name="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
"
	type="text" class="textfield"
	value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['tv']->value->get('value'), ENT_QUOTES, 'UTF-8', true);?>
"
	<?php echo $_smarty_tpl->tpl_vars['style']->value;?>

	tvtype="<?php echo $_smarty_tpl->tpl_vars['tv']->value->type;?>
"
/>

<?php echo '<script'; ?>
 type="text/javascript">
// <![CDATA[

Ext.onReady(function() {
    var fld = MODx.load({
    
        xtype: 'textfield'
        ,applyTo: 'tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,width: 400
        ,vtype: 'email'
        ,enableKeyEvents: true
        ,msgTarget: 'under'
        ,allowBlank: <?php if ($_smarty_tpl->tpl_vars['params']->value['allowBlank'] == 1 || $_smarty_tpl->tpl_vars['params']->value['allowBlank'] == 'true') {?>true<?php } else { ?>false<?php }?>
        <?php if ($_smarty_tpl->tpl_vars['params']->value['maxLength'] != '' && $_smarty_tpl->tpl_vars['params']->value['maxLength'] > 0) {
if ($_smarty_tpl->tpl_vars['params']->value['minLength'] != '' && $_smarty_tpl->tpl_vars['params']->value['minLength'] >= 0 && $_smarty_tpl->tpl_vars['params']->value['maxLength'] > $_smarty_tpl->tpl_vars['params']->value['minLength']) {?>,maxLength: <?php echo sprintf("%d",$_smarty_tpl->tpl_vars['params']->value['maxLength']);
}?> <?php }?> 
        <?php if ($_smarty_tpl->tpl_vars['params']->value['minLength'] != '' && $_smarty_tpl->tpl_vars['params']->value['minLength'] >= 0) {?>,minLength: <?php echo sprintf("%d",$_smarty_tpl->tpl_vars['params']->value['minLength']);
}?> 
    
        ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
    });
    MODx.makeDroppable(fld);
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});

// ]]>
<?php echo '</script'; ?>
>
<?php }
}
?>