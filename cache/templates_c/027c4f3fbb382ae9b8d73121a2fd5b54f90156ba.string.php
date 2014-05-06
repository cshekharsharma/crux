<?php /* Smarty version Smarty-3.1.17, created on 2014-04-30 14:55:01
         compiled from "027c4f3fbb382ae9b8d73121a2fd5b54f90156ba" */ ?>
<?php /*%%SmartyHeaderCode:9170597185360c16da925b1-84722499%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '027c4f3fbb382ae9b8d73121a2fd5b54f90156ba' => 
    array (
      0 => '027c4f3fbb382ae9b8d73121a2fd5b54f90156ba',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '9170597185360c16da925b1-84722499',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'PROGRAM_DETAILS' => 0,
    'SOURCE_STATS' => 0,
    'SOURCE_CODE' => 0,
    'EDITOR_THEME' => 0,
    'EDITOR_MODE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_5360c16db8be04_45168568',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5360c16db8be04_45168568')) {function content_5360c16db8be04_45168568($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/header.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  <title><?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['title'];?>
</title>
  <script type="text/javascript" src="/library/ace/noconflict-min/ace.js"></script> 
  <link type="text/css" rel="stylesheet" href="/ui/Explorer/css/explorer.css"/>
  
<div class="body-main"
    style="background: #F9F9F9; border: 1px solid #AAA;">
    <div class="program-header-div">
        <div class="program-title-div">
            <b><?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['title'];?>
</b>
            <?php if ($_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['is_verified']=='1') {?>
                <img class="verified-image" src="/ui/Explorer/imgs/verified.png">
            <?php }?>
            <span class="top-side-buttons file-download-link"><a target="_blank" href="/download/<?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['id'];?>
">Download</a></span>
            <span class="top-side-buttons file-download-link"><a target="_blank" href="/editor/<?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['id'];?>
">Edit</a></span>
            <span class="top-side-links">
                <a href="/<?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['fk_language'];?>
"><?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['language_name'];?>
</a> / 
                <a target="_blank" href="/<?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['fk_language'];?>
/<?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['fk_category'];?>
"><?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['category_name'];?>
</a>
            </span>
       </div>
       <small><?php echo $_smarty_tpl->tpl_vars['PROGRAM_DETAILS']->value['description'];?>
</small>
        <div class="program-stats">
            <span style="font-size:13px;float:right"> 
                Size:<?php echo $_smarty_tpl->tpl_vars['SOURCE_STATS']->value['fileSize'];?>
 KBs |
                Chars:<?php echo $_smarty_tpl->tpl_vars['SOURCE_STATS']->value['charCount'];?>
 |
                Words:<?php echo $_smarty_tpl->tpl_vars['SOURCE_STATS']->value['wordCount'];?>
 |
                Lines:<?php echo $_smarty_tpl->tpl_vars['SOURCE_STATS']->value['lineCount'];?>
 
            </span>
        </div>
     </div>
   <pre id="code-editor"><?php echo $_smarty_tpl->tpl_vars['SOURCE_CODE']->value;?>
</pre>
</div>
<script>
    var editor = ace.edit("code-editor");
    editor.setTheme("ace/theme/<?php echo $_smarty_tpl->tpl_vars['EDITOR_THEME']->value;?>
");
    editor.getSession().setMode("ace/mode/<?php echo $_smarty_tpl->tpl_vars['EDITOR_MODE']->value;?>
");
    editor.setReadOnly(true);
</script>
<?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/footer.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
