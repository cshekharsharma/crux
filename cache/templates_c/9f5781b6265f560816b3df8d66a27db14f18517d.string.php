<?php /* Smarty version Smarty-3.1.17, created on 2014-05-01 01:28:21
         compiled from "9f5781b6265f560816b3df8d66a27db14f18517d" */ ?>
<?php /*%%SmartyHeaderCode:2038042052536155dd377b51-74989779%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9f5781b6265f560816b3df8d66a27db14f18517d' => 
    array (
      0 => '9f5781b6265f560816b3df8d66a27db14f18517d',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '2038042052536155dd377b51-74989779',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'IS_UPDATE_REQ' => 0,
    'PROGRAM_CURRENT_ID' => 0,
    'EDIT_ACTION_NAME' => 0,
    'EDIT_ACTION_VALUE' => 0,
    'LANGUAGE_LIST' => 0,
    'language' => 0,
    'CATEGORY_LIST' => 0,
    'category' => 0,
    'EDITOR_THEME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_536155dd41d7c6_30392326',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_536155dd41d7c6_30392326')) {function content_536155dd41d7c6_30392326($_smarty_tpl) {?> <?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/header.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  <head>
    <title>Editor :: Code Me</title>
    <link rel="stylesheet" href="/ui/Editor/css/editor.css" type="text/css">
    <script type="text/javascript" src="/library/ace/noconflict-min/ace.js"></script> 
  </head>
  <body>
    <div class="body-main"
        style="background: #F9F9F9; border: 1px solid #AAA;">
        <form method="post" name="editor-form" id="editor-form">
            <table class="form-table">
                <tr>
                    <td  style="padding:10px;" colspan="2">
                        <input type="hidden" id="isupdate" name="isupdate" value="<?php echo $_smarty_tpl->tpl_vars['IS_UPDATE_REQ']->value;?>
">
                        <input type="hidden" id="prgoramid" name="programid" value="<?php echo $_smarty_tpl->tpl_vars['PROGRAM_CURRENT_ID']->value;?>
">
                        <input type="hidden" id="<?php echo $_smarty_tpl->tpl_vars['EDIT_ACTION_NAME']->value;?>
" name="<?php echo $_smarty_tpl->tpl_vars['EDIT_ACTION_NAME']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['EDIT_ACTION_VALUE']->value;?>
">
                    </td>
                </tr>
                <tr>
                    <td class="td1">
                        <pre id="code-editor"></pre>
                    </td>
                    <td class="td2">
                        <table class="inner-table">
                            <tr>
                                <td>
                                    <input type="text" placeholder="Program Title" class="titlebox" value="" id="programtitle" name="programtitle">
                                </td>
                           </tr>
                           <tr>
                                <td>
                                    <input type="text" placeholder="File Name" class="titlebox" value="" id="filename" name="filename">
                                </td>
                             </tr>
                            <tr>
                                <td>
                                  <span class="selectbox-div">
                                      <select class="selectbox" name="language_id" id="language_id">
                                        <?php  $_smarty_tpl->tpl_vars['language'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['language']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LANGUAGE_LIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['language']->key => $_smarty_tpl->tpl_vars['language']->value) {
$_smarty_tpl->tpl_vars['language']->_loop = true;
?>
                                          <option value="<?php echo $_smarty_tpl->tpl_vars['language']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['language']->value['name'];?>
</option>
                                        <?php } ?>
                                      </select>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="selectbox-div">
                                        <select class="selectbox" name="category_id" id="category_id">
                                            <?php  $_smarty_tpl->tpl_vars['category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['CATEGORY_LIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['category']->key => $_smarty_tpl->tpl_vars['category']->value) {
$_smarty_tpl->tpl_vars['category']->_loop = true;
?>
                                                <option value="<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['category']->value['name'];?>
</option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </td>
                            </tr>
                             <tr>
                                <td>
                                     <span class="selectbox-div">
                                        <select class="selectbox" name="level" id="level">
                                            <option value="Easy">Easy</option>
                                            <option value="Average">Average</option>
                                            <option value="Difficult">Difficult</option>
                                        </select>
                                     </span>  
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Description
                                </td>
                                <td>
                                    <textarea class="" id="description"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Verified</td>
                                <td><input name="verified" id="verified" value="is_verified" type="checkbox"></td>
    `                        </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="button" value="Submit Code" class="css-button" id="submit_code">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
         </form>
    </div>
    <script>
        var editor = ace.edit("code-editor");
        editor.setTheme("ace/theme/<?php echo $_smarty_tpl->tpl_vars['EDITOR_THEME']->value;?>
");
        editor.getSession().setMode("ace/mode/c_cpp");
    </script>
    <script type="text/javascript" src="/ui/Editor/js/editor.js"></script>
    <?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/footer.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  <?php }} ?>
