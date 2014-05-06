<?php /* Smarty version Smarty-3.1.17, created on 2014-04-30 14:57:01
         compiled from "88d14d511a94f7020ca083a35b9dfa4936810a5f" */ ?>
<?php /*%%SmartyHeaderCode:15710280605360c1e5e9a7c9-00386615%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '88d14d511a94f7020ca083a35b9dfa4936810a5f' => 
    array (
      0 => '88d14d511a94f7020ca083a35b9dfa4936810a5f',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '15710280605360c1e5e9a7c9-00386615',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SEARCH_KEY' => 0,
    'totalResults' => 0,
    'RESULT_SET' => 0,
    'result' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_5360c1e6027669_88948544',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5360c1e6027669_88948544')) {function content_5360c1e6027669_88948544($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/header.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<head>
   <title>Search - <?php echo $_smarty_tpl->tpl_vars['SEARCH_KEY']->value;?>
</title>
  <link rel="stylesheet" type="text/css" href="/ui/Search/css/search.css">
</head>
<body>
  <table cellspacing="0" class="listview-table">
           <thead>
               <tr>
                    <th>
                        Showing results for <i>"<?php echo $_smarty_tpl->tpl_vars['SEARCH_KEY']->value;?>
"</i>
                        <span style="float:right">Total <?php echo $_smarty_tpl->tpl_vars['totalResults']->value;?>
 results found</span>
                    </th>
               </tr>
           </thead>
           <tbody>
           <?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['result']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['RESULT_SET']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value) {
$_smarty_tpl->tpl_vars['result']->_loop = true;
?>
                <tr>
                    <td>
                        <table class="search-result-div">
                            <tr>
                                <td class="first-col">
                                    <span class='title'>
                                        <a target="_blank" href="/<?php echo $_smarty_tpl->tpl_vars['result']->value['fk_language'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['fk_category'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['title'];?>
</a>
                                   </title>
                                </td>
                                <td class="second-col">
                                    <a href="/<?php echo $_smarty_tpl->tpl_vars['result']->value['fk_language'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['language_name'];?>
</a> / 
                                    <a href="/<?php echo $_smarty_tpl->tpl_vars['result']->value['fk_language'];?>
/<?php echo $_smarty_tpl->tpl_vars['result']->value['fk_category'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['category_name'];?>
</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo $_smarty_tpl->tpl_vars['result']->value['description'];?>

                                </td>
                                <td>
                                    <span class="feded-text">Uploaded By:</span> 
                                        <a target="_blank" href="/search?q=users:<?php echo $_smarty_tpl->tpl_vars['result']->value['user_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['result']->value['user_name'];?>
</a>
                                    <br>
                                    <span class="feded-text">On</span> <?php echo $_smarty_tpl->tpl_vars['result']->value['created_on'];?>

                                </td>
                            </tr>
                         </table>
                    </td>
                </tr>
           <?php } ?>
           </tbody>
        </table>
       <?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/footer.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

 </body>
 </html><?php }} ?>
