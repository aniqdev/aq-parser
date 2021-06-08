<?php
/* Smarty version 3.1.36, created on 2020-12-14 20:24:34
  from 'F:\xampp\htdocs\parser\www\volkano\Demo\html\page-1.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_5fd7bbf20c2e77_27884722',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '972e07fd894b8079ae424055dfe2c0e77dbf5174' => 
    array (
      0 => 'F:\\xampp\\htdocs\\parser\\www\\volkano\\Demo\\html\\page-1.tpl',
      1 => 1607973867,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5fd7bbf20c2e77_27884722 (Smarty_Internal_Template $_smarty_tpl) {
?><html>
<body>

<table cellpadding="0" cellspacing="0">
    <tr>
        <td class="title-td">
            <h1 class="title">Gutschein</h1>
        </td>
        <td>
            <img class="logo" src="https://funkyimg.com/i/39nuY.png" alt="VulcanoVet">
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0">
    <tr>
        <td class="banner-td">
            <img class="banner" src="https://funkyimg.com/i/39nmM.jpg" alt="">
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th>Name des Beschenkten:</th>
        <td><?php if ($_smarty_tpl->tpl_vars['personalization']->value) {
echo $_smarty_tpl->tpl_vars['personalization']->value->getPresenteeName();
}?></td>
    </tr>
    <tr>
        <th>Grußbotschaft:</th>
        <td><?php if ($_smarty_tpl->tpl_vars['personalization']->value) {
echo $_smarty_tpl->tpl_vars['personalization']->value->getPresenteeMessage();
}?></td>
    </tr>
    <tr>
        <th>Name des Schenkers:</th>
        <td><?php if ($_smarty_tpl->tpl_vars['personalization']->value) {
echo $_smarty_tpl->tpl_vars['personalization']->value->getDonorName();
}?></td>
    </tr>
    <tr>
        <th>Gutscheincode:</th>
        <td><?php echo $_smarty_tpl->tpl_vars['code']->value;?>
</td>
    </tr>
    <tr>
        <th>Wert:</th>
        <td><?php if ($_smarty_tpl->tpl_vars['percental']->value) {
echo $_smarty_tpl->tpl_vars['value']->value;?>
%<?php } else {
echo $_smarty_tpl->tpl_vars['value']->value;
}?></td>
    </tr>
</table>

</body>
</html>
<?php }
}
