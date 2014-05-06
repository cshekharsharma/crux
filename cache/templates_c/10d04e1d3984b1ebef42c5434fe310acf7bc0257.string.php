<?php /* Smarty version Smarty-3.1.17, created on 2014-04-30 14:56:53
         compiled from "10d04e1d3984b1ebef42c5434fe310acf7bc0257" */ ?>
<?php /*%%SmartyHeaderCode:16138679705360c1dd90d2e3-52998892%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '10d04e1d3984b1ebef42c5434fe310acf7bc0257' => 
    array (
      0 => '10d04e1d3984b1ebef42c5434fe310acf7bc0257',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '16138679705360c1dd90d2e3-52998892',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'LOGIN_ACTION_VALUE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_5360c1dd94a5e3_12905066',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5360c1dd94a5e3_12905066')) {function content_5360c1dd94a5e3_12905066($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/headerBeforeLogin.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<!DOCTYPE html>
<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="/ui/Auth/css/auth.css">
  <script type="text/javascript" src="/ui/Auth/js/auth.js"></script>
</head>
    <div id="wrapper1">
        <div class="form-header-span">Please verify your identity</div>
        <form name="login-form" id="login-form" class="auth-form" method="post">
        <div class="innerdiv">
            <input type="hidden" name="login-action-name" id="login-action-name" value="<?php echo $_smarty_tpl->tpl_vars['LOGIN_ACTION_VALUE']->value;?>
">
            <div id="auth-msg-container">
                
            </div><br>
                 <div class="fieldset">   
                    <div>
                        <input type="text" name="username" id="username" placeholder="User Name"/>
                    </div>

                    <div>
                        <input type="password" name="password" id="password" placeholder="Password"/>
                    </div>
                    <span style="margin-right:40px;">
                        <input type="checkbox" name="remember" id="remember" value="remember" checked> Remember me
                    </span>
                    <input type="button" class="btns" value="Login" onclick="doLogin('login-form');">
                </div> 
            </div>   
        </form>
    </div>
    <?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/footer.htpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</body>
</html>
<?php }} ?>
