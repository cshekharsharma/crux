<!DOCTYPE html>
<html lang="en" dir="ltr">
<link rel="icon" href="/ui/Index/imgs/code1.png" type="image/png">
<script type="text/javascript" src="/library/jquery/js/jquery.js"></script>
<script type="text/javascript" src="/library/jquery/js/jqueryui.js"></script>
<link type="text/css" rel="stylesheet" href="/library/jquery/css/jqueryui.css">
<link rel="stylesheet" type="text/css" href="/ui/Index/css/main.css">
<div id="popup-opacity-background"></div>
<div class="header-div" >
    <form name="searchform" class="searchform" action="/search" method="get">
        <span class="header-left-div">
            <span class="main-logo-text"><a href="/">{$APP_NAME}</a></span>
        </span>
        <span class="header-middle-div">
            <input type="text" class="searchbox txtbox ui-widget" autocomplete="off" id="searchbox" name="q" placeholder="Search" value="{$SEARCH_KEY}">
            <input type="button" class="upload-button" onclick="window.location.href='/upload';" value="Upload">
        </span>
        <span class="header-right-div">
            <span class="side-main-link">
                <a id="upper-logout-link" href="#" onclick="getAccountDropdown()">{$smarty.session.loggedin_user_details.user_name}</a>
            </span>
        </span>
    </form>
</div>
<div id="jquery-autocomplete-results">
    
</div>
<script>
    {literal}
    var searchDataSource = '{/literal}{$SEARCH_SUGGESTIONS}{literal}';
    
  
    {/literal}
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
            <input type="hidden" name="chpwd-action-name" id="chpwd-action-name" value="{$CHPWD_ACTION_VALUE}">
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
<script type="text/javascript" src="/ui/Auth/js/auth.js"></script>