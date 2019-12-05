<?php
/* Smarty version 3.1.33, created on 2019-10-31 07:45:42
  from 'E:\xampp\htdocs\parser\www\templates\test.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5dba8316e321c5_54624043',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '520dde9f8d3694f5d4ec0f87d04a637bd4c96caf' => 
    array (
      0 => 'E:\\xampp\\htdocs\\parser\\www\\templates\\test.tpl',
      1 => 1572418813,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5dba8316e321c5_54624043 (Smarty_Internal_Template $_smarty_tpl) {
?><pre>
	<?php echo $_SERVER['REQUEST_SCHEME'];?>
://<?php echo $_SERVER['HTTP_HOST'];
echo $_SERVER['REQUEST_URI'];?>

</pre>
<pre>
	<?php echo date('Y-m-d',time()+2592000);?>

</pre>
<pre>
	<?php echo print_r($_SERVER);?>

</pre><?php }
}
