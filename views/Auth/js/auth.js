AUTH_CONSTANTS = {
    loginFormId : 'login-form',
    changePwdFormId : 'changepassword-form',
    changePwdPopupFlag : 'chpwd',
    authLoginPath : '/auth/login',
    authChangePwdPath : '/auth/changePassword',
    chpwdContentPath : '/content/changePassword',
    SUCCESS_CODE : 'success',
    ERROR_CODE : 'error',
    msgContaineId : 'auth-msg-container',
    emptyCredentials : "Username / Password can't be left empty",
    invalidUserName : "Only alphbates and '_' are allowed in User Name",
    emptyPasswords : "Password fields can't be left empty"
};

var cssSelectors = {
    popupBg : '.popup-opacity-background',
    chpwdContainer : '#chpwd-container',
    chpwdLink : '.change-password-link'
};

addEvent(window, 'load', function() {
    var forms = document.getElementsByClassName('auth-form');
    for ( var index = 0; index < forms.length; index++) {
        var form = forms[index];
        for ( var i = 0; i < form.length; i++) {
            if (form[i].nodeName === "INPUT") {
                var el = form[i];
                addEvent(el, 'keypress', function(e) {
                    if (e.keyCode == 13) {
                        if (form.id == AUTH_CONSTANTS.loginFormId) {
                            doLogin(form.id);
                        } else if (form.id == AUTH_CONSTANTS.changePwdFormId) {
                            changePassword(form.id);
                        }
                    }
                });
            }
        }
    }
});

function doLogin(formId) {
    var formElem = document.getElementById(formId);
    var formInputFields = [ formElem.username, formElem.password ];
    if (validate(formElem, formInputFields, AUTH_CONSTANTS.emptyCredentials)) {
        var formData = populateLoginFormData(formElem);
        AJAX.onreadystatechange = function() {
            if (AJAX.readyState == 4 && AJAX.status == 200) {
                var response = JSON.parse(AJAX.responseText);
                var msgContainerId = AUTH_CONSTANTS.msgContaineId;
                var msgContainer = document.getElementById(msgContainerId);
                if (response.code == AUTH_CONSTANTS.SUCCESS_CODE) {
                    msgContainer.className = "success-msg-div";
                    document.getElementById(formId).reset();
                    window.location.reload();
                } else if (response.code == AUTH_CONSTANTS.ERROR_CODE) {
                    msgContainer.className = "error-msg-div";
                }
                msgContainer.innerHTML = response.msg;
                msgContainer.style.display = "block";
            }
        };
        AJAX.open("POST", AUTH_CONSTANTS.authLoginPath);
        AJAX.setRequestHeader('X_REQUESTED_WITH', 'XMLHttpRequest');
        AJAX.send(formData);
    }
}

function changePassword(formId) {
    var formElem = document.getElementById(formId);
    var formInputFields = [ formElem.currentpassword, formElem.newpassword ];
    if (validate(formElem, formInputFields, AUTH_CONSTANTS.emptyPasswords)) {
        var formData = populateChpwdFormData(formElem);
        AJAX.onreadystatechange = function() {
            if (AJAX.readyState == 4 && AJAX.status == 200) {
                var response = JSON.parse(AJAX.responseText);
                var msgContainerId = AUTH_CONSTANTS.msgContaineId;
                var msgContainer = document.getElementById(msgContainerId);
                if (response.code == AUTH_CONSTANTS.SUCCESS_CODE) {
                    msgContainer.className = "success-msg-div";
                    document.getElementById(formId).reset();
                    setTimeout(function() {
                        closePopup();
                    }, 2000);
                } else if (response.code == AUTH_CONSTANTS.ERROR_CODE) {
                    msgContainer.className = "error-msg-div";
                }
                msgContainer.innerHTML = response.msg;
                msgContainer.style.display = "block";
            }
        };
        AJAX.open("POST", AUTH_CONSTANTS.authChangePwdPath);
        AJAX.setRequestHeader('HTTP_X_REQUESTED_WITH', 'XMLHttpRequest');
        AJAX.send(formData);
    }
}

function populateLoginFormData(formElem) {
    var formData = new FormData();
    formData.append('username', formElem.username.value);
    formData.append('password', formElem.password.value);
    formData.append('remember', formElem.remember.checked);
    formData.append('login-action-name', document
            .getElementById('login-action-name').value);
    return formData;
}

function populateChpwdFormData(formElem) {
    var formData = new FormData();
    formData.append('currentpassword', formElem.currentpassword.value);
    formData.append('newpassword', formElem.newpassword.value);
    formData.append('chpwd-action-name', document
            .getElementById('chpwd-action-name').value);
    return formData;
}

function validate(formElem, credential, errMsg) {
    var isError = false;
    var errorMsgElem = document.getElementById('auth-msg-container');
    resetErrors(credential);
    for (index in credential) {
        if (credential[index].value == 0) {
            errorMsgElem.innerHTML = errMsg;
            errorMsgElem.className = "error-msg-div";
            errorMsgElem.style.display = "block";
            credential[index].className += " error-field";
            isError = true;
        }
    }
    return (!isError);
}

function resetErrors(credential) {
    for (index in credential) {
        removeClass(credential[index], "error-field");
    }
}

$(cssSelectors.chpwdLink).click(
        function() {
            attachPopupEvents(cssSelectors.popupBg,
                    cssSelectors.chpwdContainer,
                    AUTH_CONSTANTS.changePwdPopupFlag, getChangePasswordUI);
        });

function getChangePasswordUI() {
    formData = new FormData();
    formData.append('time', 100000);
    AJAX.onreadystatechange = function() {
        if (AJAX.readyState == 4 && AJAX.status == 200) {
            var response = JSON.parse(AJAX.responseText);
            if (response.code == AUTH_CONSTANTS.SUCCESS_CODE) {
                $(cssSelectors.chpwdContainer).html(response.detail);
            } else if (response.code == AUTH_CONSTANTS.ERROR_CODE) {
                $(cssSelectors.chpwdContainer).html(response.msg);
            }
        }
    };
    AJAX.open("POST", AUTH_CONSTANTS.chpwdContentPath);
    AJAX.setRequestHeader('HTTP_X_REQUESTED_WITH', 'XMLHttpRequest');
    AJAX.send(formData);
}