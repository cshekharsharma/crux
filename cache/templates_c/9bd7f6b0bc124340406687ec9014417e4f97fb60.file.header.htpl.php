<?php /* Smarty version Smarty-3.1.17, created on 2014-04-30 14:54:47
         compiled from "ui/Index/tpls/header.htpl" */ ?>
<?php /*%%SmartyHeaderCode:8204432445360c15f35afa1-79350075%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9bd7f6b0bc124340406687ec9014417e4f97fb60' => 
    array (
      0 => 'ui/Index/tpls/header.htpl',
      1 => 1398566072,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8204432445360c15f35afa1-79350075',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'APP_NAME' => 0,
    'SEARCH_KEY' => 0,
    'SEARCH_SUGGESTIONS' => 0,
    'CHPWD_ACTION_VALUE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_5360c15f3ac922_79430719',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5360c15f3ac922_79430719')) {function content_5360c15f3ac922_79430719($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="icon" href="/ui/Index/imgs/code1.png" type="image/png">
    <script type="text/javascript" src="/library/jquery/js/jquery.js"></script>
    <script type="text/javascript" src="/library/jquery/js/jqueryui.js"></script>
    <link type="text/css" rel="stylesheet" href="/library/jquery/css/jqueryui.css">
    <link rel="stylesheet" type="text/css" href="/ui/Index/css/main.css">
</head>
<div id="popup-opacity-background"></div>
<div class="header-div" >
    <form name="searchform" class="searchform" action="/search" method="get">
        <table class="header-item-table">
            <tr>
                <td class="td1">
                    <span class="main-logo-text"><a href="/"><?php echo $_smarty_tpl->tpl_vars['APP_NAME']->value;?>
</a></span>
                </td>
                <td class="td2">
                    <input type="text" class="searchbox txtbox ui-widget" autocomplete="off" id="searchbox" name="q" placeholder="Search" value="<?php echo $_smarty_tpl->tpl_vars['SEARCH_KEY']->value;?>
">
                </td>
                <td class="td3">
                    <a id="editor-link" class="upper-action-link" href="/editor">Editor</a>
                    <a id="editor-link" class="upper-action-link" href="/upload">Upload</a>
                    <a id="username-link" class="upper-action-link" href="#" onclick="getAccountDropdown()"><?php echo $_SESSION['loggedin_user_details']['user_name'];?>
</a>
                </td>
            </tr>
        </table>
            </span>
        </span>
    </form>
</div>
<div id="jquery-autocomplete-results">
    
</div>
<script>
    
    var searchDataSource = '<?php echo $_smarty_tpl->tpl_vars['SEARCH_SUGGESTIONS']->value;?>
';
    
  
    
</script>

<div class="account-dropdown">
    <ul>
        <li class="change-password-link popup-link"><a href="#">Change Password</a></li>
        <li onclick="window.location.href='/auth/logout/'">Logout</li>
    </ul>
</div>

<div id="popupDiv">
    <div id="wrapper2">
        <div class="form-header-span">Change Password</div>
        <form name="changepassword-form" id="changepassword-form" class="auth-form" method="post">
        <div class="innerdiv">
            <input type="hidden" name="chpwd-action-name" id="chpwd-action-name" value="<?php echo $_smarty_tpl->tpl_vars['CHPWD_ACTION_VALUE']->value;?>
">
            <div id="auth-msg-container">
            </div><br>
                 <div class="fieldset">   
                    <div>
                        <input type="password" name="currentpassword" id="currentpassword" placeholder="Current Password"/>
                    </div>
                    <div>
                        <input type="password" name="newpassword" id="newpassword" placeholder="New Password"/>
                    </div>

                    <input type="button" class="btns" value="Submit" onclick="changePassword('changepassword-form');">
                </div> 
            </div>   
        </form>
    </div>
</div>

<script type="text/javascript" src="/ui/Index/js/header.js"></script>
<script type="text/javascript" src="/ui/Auth/js/auth.js"></script><?php }} ?>
