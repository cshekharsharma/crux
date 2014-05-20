var USER_PREF_CONSTANTS = {
    getUserPrefUIPath : '/content/userpref',
    userPrefPopupFlag : 'userPref'
};

var UpCssSelectors = {
    userprefLink : '.user-preference-link',
    userPrefContainer : '#userpref-container'
};

$(UpCssSelectors.userprefLink)
        .click(
                function() {
                    attachPopupEvents(cssSelectors.popupBg,
                            UpCssSelectors.userPrefContainer,
                            USER_PREF_CONSTANTS.userPrefPopupFlag,
                            getUserPreferenceUI);
                });

function getUserPreferenceUI() {
    formData = new FormData();
    formData.append('time', 100000);
    AJAX.onreadystatechange = function() {
        if (AJAX.readyState == 4 && AJAX.status == 200) {
            var response = JSON.parse(AJAX.responseText);
            if (response.code == APP_CONSTANTS.SUCCESS_CODE) {
                $(cssSelectors.chpwdContainer).html(response.detail);
            } else if (response.code == APP_CONSTANTS.ERROR_CODE) {
                // msgContainer.className = "error-msg-div";
            }
        }
    };
    AJAX.open("POST", USER_PREF_CONSTANTS.getUserPrefUIPath);
    AJAX.setRequestHeader('HTTP_X_REQUESTED_WITH', 'XMLHttpRequest');
    AJAX.send(formData);
}