var USER_PREF_CONSTANTS = {
    saveUserPrefPath : '/userPreferences/save',
    getUserPrefUIPath : '/content/userPreferences',
    userPrefPopupFlag : 'userPref'
};

var UpCssSelectors = {
    userprefLink : '.user-preference-link',
    userPrefContainer : '#userpref-container'
};

function saveUserPreference(formId) {
    var formData = new FormData();
    formData.append('code_editor_theme', $('#code_editor_theme').val());
    formData.append('show_invisible_chars', $('#show_invisibles')
            .is(':checked'));
    formData.append('paginator_limit', $('#paginator_limit').val());
    formData.append(APP_CONSTANTS.CSRF_TOKEN_NAME,
            $('#'+APP_CONSTANTS.CSRF_TOKEN_NAME).val());
    AJAX.onreadystatechange = function() {
        if (AJAX.readyState == 4 && AJAX.status == 200) {
            var msgContainer = $('.msg-container');
            var response = JSON.parse(AJAX.responseText);
            if (response.code == APP_CONSTANTS.SUCCESS_CODE) {
                msgContainer[0].className = "success-msg-div";
            } else {
                msgContainer[0].className = "error-msg-div";
            }
            msgContainer.html(response.msg);
        }
    };
    AJAX.open("POST", USER_PREF_CONSTANTS.saveUserPrefPath);
    AJAX.setRequestHeader('X-REQUESTED-WITH', 'XMLHttpRequest');
    AJAX.send(formData);
}

$(UpCssSelectors.userprefLink)
        .click(
                function() {
                    attachPopupEvents(APP_CONSTANTS.cssSelectors.popupBg,
                            UpCssSelectors.userPrefContainer,
                            USER_PREF_CONSTANTS.userPrefPopupFlag,
                            getUserPreferenceUI);
                    $(APP_CONSTANTS.cssSelectors.accountDD).slideToggle();
                });

function getUserPreferenceUI() {
    formData = new FormData();
    formData.append('time', 100000);
    AJAX.onreadystatechange = function() {
        if (AJAX.readyState == 4 && AJAX.status == 200) {
            var response = JSON.parse(AJAX.responseText);
            if (response.code == APP_CONSTANTS.SUCCESS_CODE) {
                $(UpCssSelectors.userPrefContainer).html(response.detail);
            } else {
                $(UpCssSelectors.userPrefContainer).html(response.msg);
            }
        }
    };
    AJAX.open("GET", USER_PREF_CONSTANTS.getUserPrefUIPath);
    AJAX.setRequestHeader('X-REQUESTED-WITH', 'XMLHttpRequest');
    AJAX.send(formData);
}
