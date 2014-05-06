<?php /* Smarty version Smarty-3.1.17, created on 2014-05-06 15:39:47
         compiled from "b96f01640b1124f10178c69a85ffa55a54b4de6e" */ ?>
<?php /*%%SmartyHeaderCode:5988087795368b4eb38e8a1-92866433%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b96f01640b1124f10178c69a85ffa55a54b4de6e' => 
    array (
      0 => 'b96f01640b1124f10178c69a85ffa55a54b4de6e',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '5988087795368b4eb38e8a1-92866433',
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
  'unifunc' => 'content_5368b4eb437fb1_85217403',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5368b4eb437fb1_85217403')) {function content_5368b4eb437fb1_85217403($_smarty_tpl) {?> <?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/header.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

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
                                    <div id="msg-container"></div>
                                </td>
                            </tr>
                                <td>
                                    <input type="text" placeholder="Program Title" class="titlebox apply-validation" value="" id="programtitle" name="programtitle">
                                </td>
                           </tr>
                           <tr>
                                <td>
                                    <input type="text" placeholder="File Name" class="titlebox apply-validation" value="" id="filename" name="filename">
                                </td>
                             </tr>
                            <tr>
                                <td>
                                  <span class="selectbox-div">
                                      <select class="selectbox apply-validation" name="language_id" id="language_id">
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
                                        <select class="selectbox apply-validation" name="category_id" id="category_id">
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
                                        <select class="selectbox apply-validation" name="level" id="level">
                                            <option value="Easy">Easy</option>
                                            <option value="Average">Average</option>
                                            <option value="Difficult">Difficult</option>
                                        </select>
                                     </span>  
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <textarea placeholder="Description" class="apply-validation" id="description"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input class="apply-validation" name="verified" id="verified" value="is_verified" type="checkbox">
                                    &nbsp;Verified
                                </td>
    `                        </tr>
                            <tr>
                                <td colspan="2" style="text-align:center">
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
        var editorTheme = '<?php echo $_smarty_tpl->tpl_vars['EDITOR_THEME']->value;?>
';
    </script>
    <script type="text/javascript" src="/ui/Editor/js/editor.js"></script>
    <?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/footer.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  <?php }} ?>
