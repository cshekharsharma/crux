<?php /* Smarty version Smarty-3.1.17, created on 2014-04-30 14:56:53
         compiled from "ui/Index/tpls/headerBeforeLogin.htpl" */ ?>
<?php /*%%SmartyHeaderCode:18203174005360c1dd94e613-97741288%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5898a4f232d197fd86f8a3f5b9dc44b350e3d09b' => 
    array (
      0 => 'ui/Index/tpls/headerBeforeLogin.htpl',
      1 => 1398238078,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18203174005360c1dd94e613-97741288',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'APP_NAME' => 0,
    'SEARCH_KEY' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_5360c1dd9649e6_42310636',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5360c1dd9649e6_42310636')) {function content_5360c1dd9649e6_42310636($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en" dir="ltr">
<script type="text/javascript" src="/library/jquery/js/jquery.js"></script>
<script type="text/javascript" src="/library/jquery/js/jqueryui.js"></script>
<link type="text/css" rel="stylesheet" href="/library/jquery/css/jqueryui.css">
<link rel="stylesheet" type="text/css" href="/ui/Index/css/main.css">

<div class="header-div" >
    <br>
    <form name="searchform" class="searchform" action="/search" method="get">
        <span class="header-left-div">
            <span class="main-logo-text"><a href="/"><?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
</a></span>
        </span>
    <!--    <span class="header-middle-div">
            <input type="text" class="searchbox txtbox ui-widget" autocomplete="off" id="searchbox" name="q" placeholder="Search" value="<?php echo $_smarty_tpl->tpl_vars['SEARCH_KEY']->value;?>
">
            <input type="button" class="upload-button" onclick="window.location.href='/upload';" value="Upload">
        </span>
        <span class="header-right-div">
            <span class="side-main-link">
                <a id="upper-logout-link" href="/auth/logout">Logout</a>
            </span>
        </span> -->
    </form>
</div>
<div id="jquery-autocomplete-results">
    
</div>
<script>
    
    var searchDataSource = {
        Category : ['Strings', 'Arrays', 'Basic', 'Hashing', 'Linked List', 'Tree', 'Stack', 'Queue', 'Sorting', 'Searching', 'Graph', 'Mathematics'],
        Language : ['C Language', 'C++', 'Java', 'PHP', 'Python', 'JavaScript', 'Ruby', 'Scala', 'Microsoft C#'],
        User     : ['shekhar', 'shubham']    
    };
    
    var searchSuggestions = [];
    for (type in searchDataSource) {
        for (key in searchDataSource[type]) {
            searchSuggestions.push(type + ':' + searchDataSource[type][key]);
        }
    }
    searchSuggestions.sort();
    $( "#searchbox" ).autocomplete({
      source: searchSuggestions,
      appendTo : '#jquery-autocomplete-results',
      position: {my:'right top', at:'center'},
      select: function( event, ui ) {
        $('.searchform').submit();
      }
    });
    
</script>
<script type="text/javascript" src="/ui/Index/js/header.js"></script><?php }} ?>
