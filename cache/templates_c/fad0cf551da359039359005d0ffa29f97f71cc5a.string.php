<?php /* Smarty version Smarty-3.1.17, created on 2014-04-30 14:54:47
         compiled from "fad0cf551da359039359005d0ffa29f97f71cc5a" */ ?>
<?php /*%%SmartyHeaderCode:19950442345360c15f056af7-55179430%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fad0cf551da359039359005d0ffa29f97f71cc5a' => 
    array (
      0 => 'fad0cf551da359039359005d0ffa29f97f71cc5a',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '19950442345360c15f056af7-55179430',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'PROGRAM_LIST' => 0,
    'program' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_5360c15f356580_21753439',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5360c15f356580_21753439')) {function content_5360c15f356580_21753439($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/shekhar/workspace/php/code.me/library/smarty/libs/plugins/modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/header.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<head>
  <meta charset="utf-8">
  <title>Code Me</title>
  <link rel="stylesheet" type="text/css" href="/ui/Index/css/index.css">
  <script src="/library/jquery/js/jquery.tablesorter.min.js" type="text/javascript"></script>
  
    <script>
    $(document).ready(function() { 
        $(".listview-table").tablesorter(); 
    });
  
  </script>
</head>
<body>
        <table cellspacing="0" class="listview-table tablesorter">
           <thead>
               <tr>
                    <th scope="col" class="nobg">Language</th>
                    <th scope="col">Category</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Level</th>
                    <th scope="col">Verified</th>
                    <th scope="col">Uploaded By</th>
                    <th scope="col">Uploaded On</th>
                    
               </tr>
           </thead>
           <tbody>
           <?php  $_smarty_tpl->tpl_vars['program'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['program']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['PROGRAM_LIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['program']->key => $_smarty_tpl->tpl_vars['program']->value) {
$_smarty_tpl->tpl_vars['program']->_loop = true;
?>
                <tr>
                   <td class="alt"><a target="_blank" href="/<?php echo $_smarty_tpl->tpl_vars['program']->value['fk_language'];?>
"><?php echo $_smarty_tpl->tpl_vars['program']->value['language_name'];?>
</a></td>
                   <td class="alt"><a target="_blank" href="/<?php echo $_smarty_tpl->tpl_vars['program']->value['fk_language'];?>
/<?php echo $_smarty_tpl->tpl_vars['program']->value['fk_category'];?>
"><?php echo $_smarty_tpl->tpl_vars['program']->value['category_name'];?>
</a></td>
                   <td class="alt"><a target="_blank" href="/<?php echo $_smarty_tpl->tpl_vars['program']->value['fk_language'];?>
/<?php echo $_smarty_tpl->tpl_vars['program']->value['fk_category'];?>
/<?php echo $_smarty_tpl->tpl_vars['program']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['program']->value['title'];?>
<a/></td>
                   <td class="alt"><?php echo nl2br($_smarty_tpl->tpl_vars['program']->value['description']);?>
</td>
                   <td class="alt"><?php echo $_smarty_tpl->tpl_vars['program']->value['level'];?>
</td>
                   <td class="alt">
                        <?php if ($_smarty_tpl->tpl_vars['program']->value['is_verified']=='1') {?>
                            Yes
                        <?php } else { ?>
                            No
                        <?php }?>
                   </td>
                   <td class="alt"><?php echo $_smarty_tpl->tpl_vars['program']->value['created_by'];?>
</td>
                   <td class="alt"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['program']->value['created_on'],"%d %b %Y");?>
</td>
                </tr>
           <?php } ?>
           </tbody>
        </table>
    <?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/footer.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

 </body><?php }} ?>
