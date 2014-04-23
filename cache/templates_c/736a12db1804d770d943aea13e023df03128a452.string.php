<?php /* Smarty version Smarty-3.1.17, created on 2014-04-23 13:05:35
         compiled from "736a12db1804d770d943aea13e023df03128a452" */ ?>
<?php /*%%SmartyHeaderCode:50462995853576d47065dd6-06986146%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '736a12db1804d770d943aea13e023df03128a452' => 
    array (
      0 => '736a12db1804d770d943aea13e023df03128a452',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '50462995853576d47065dd6-06986146',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'LOGIN_ACTION_VALUE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_53576d470a5494_59028994',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53576d470a5494_59028994')) {function content_53576d470a5494_59028994($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/headerBeforeLogin.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
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
    <?php echo $_smarty_tpl->getSubTemplate ("ui/Index/tpls/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</body>
</html>
<?php }} ?>
