<?php /* Smarty version Smarty-3.1.17, created on 2014-05-03 10:59:49
         compiled from "f695a5f16076aea99335fb812fca1cde76be6f1b" */ ?>
<?php /*%%SmartyHeaderCode:193191924553647ecd0640e2-87152780%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f695a5f16076aea99335fb812fca1cde76be6f1b' => 
    array (
      0 => 'f695a5f16076aea99335fb812fca1cde76be6f1b',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '193191924553647ecd0640e2-87152780',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FILE_UPLOAD_ACTION_VALUE' => 0,
    'LANGUAGE_LIST' => 0,
    'language' => 0,
    'CATEGORY_LIST' => 0,
    'category' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_53647ecd0ed8e3_69972068',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53647ecd0ed8e3_69972068')) {function content_53647ecd0ed8e3_69972068($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/header.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<head>
  <title>Upload :: Code.Me</title>
  <link rel="stylesheet" type="text/css" href="/ui/Upload/css/upload.css">
  <script type="text/javascript" src="/ui/Upload/js/upload.js"></script>
</head>
<body>
    <div id="wrapper">
        <div class="form-header-span">Upload Your Code</div>
        <form name="file-upload-form" id="file-upload-form" method="post" enctype="multipart/form-data">
        <div class="innerdiv">
            <input type="hidden" name="file-upload-action-name" id="file-upload-action-name" value="<?php echo $_smarty_tpl->tpl_vars['FILE_UPLOAD_ACTION_VALUE']->value;?>
">
            <div id="upload-msg-container">
                
            </div>
                 <div class="fieldset">   
                    <div>
                        <input class="uploadfile" name="uploadfile" type="file" />
                    </div>
                    <div>
                        <input type="text" name="program_title" id="program_id" placeholder="Write program title here..."/>
                    </div>
                    <div style="padding-bottom:15px;">
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
                         <span class="selectbox-div">
                            <select class="selectbox" name="level" id="level">
                                <option value="Easy">Easy</option>
                                <option value="Average">Average</option>
                                <option value="Difficult">Difficult</option>
                            </select>
                        </span>
                    </div>
                    <div>
                        <textarea name="program_description" id="program_description" placeholder="Write description here..."></textarea>
                    </div>    
                    <input type="button" class="btns" value="Upload" onclick="upload('file-upload-form')">&nbsp;
                    <input type="checkbox" value="is_verified" name="is_verified" id="is_verified" ><label for="is_verified">Is Verified</label>
                </div> 
            </div>   
        </form>
    </div>
    <?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/footer.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</body>
</html>
<?php }} ?>
