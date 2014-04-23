{include file="ui/Index/tpls/headerBeforeLogin.tpl"}<!DOCTYPE html>
<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="/ui/Auth/css/auth.css">
  <script type="text/javascript" src="/ui/Auth/js/auth.js"></script>
</head>
    <div id="wrapper1">
        <div class="form-header-span">Please verify your identity</div>
        <form name="login-form" id="login-form" class="auth-form" method="post">
        <div class="innerdiv">
            <input type="hidden" name="login-action-name" id="login-action-name" value="{$LOGIN_ACTION_VALUE}">
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
    {include file="ui/Index/tpls/footer.tpl"}
</body>
</html>
