<?php /* Smarty version Smarty-3.1.17, created on 2014-05-01 01:32:52
         compiled from "04756858df814b6de76b82e75667ac4bb7b3e8b8" */ ?>
<?php /*%%SmartyHeaderCode:207923279536156eca45573-35133576%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '04756858df814b6de76b82e75667ac4bb7b3e8b8' => 
    array (
      0 => '04756858df814b6de76b82e75667ac4bb7b3e8b8',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '207923279536156eca45573-35133576',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_536156eca93d31_14095771',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_536156eca93d31_14095771')) {function content_536156eca93d31_14095771($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/header.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<head>
  <meta charset="utf-8">
  <title>Oops!</title>
  <link rel="stylesheet" type="text/css" href="/ui/Errors/css/errors.css">
</head>
<body>
    <div class="error-msg-holder">
        <span class="bigoops">OOPS! </span><br>
         It's looking like you have taken a wrong turn.
        <br/>Don't worry... it happens to the best of us.
    </div>
<?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/footer.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

 </body><?php }} ?>
