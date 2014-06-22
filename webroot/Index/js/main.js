/**
 * Generic javascript file for all modules
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 */

APP_CONSTANTS = {
    SUCCESS_CODE : 'success',
    FAILURE_CODE : 'error',
    cssSelectors : {
        popupBg : '.popup-opacity-background'
    }
};

var popupFlags = {
    chpwd : 0,
    userPref : 0,
    execCode : 0,
    about : 0
};

var errorObject = {
    isError : false,
    errorMsg : ''
};

function getAjaxConnection() {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return xmlhttp;
}

function addEvent(element, event, fn) {
    if (element.addEventListener)
        element.addEventListener(event, fn, false);
    else if (element.attachEvent)
        element.attachEvent('on' + event, fn);
}

// Enabling ctl+F to be used for site search
addEvent(window, 'keydown', function(e) {
    if (e.ctrlKey && e.keyCode === 70) {
        e.preventDefault();
        document.getElementById('searchbox').focus();
    }
});

addEvent(window, 'keydown', function(e) {
    if (e.keyCode === 27) {
        e.preventDefault();
        if (!$('.account-dropdown').is(':hidden')) {
            $('.account-dropdown').slideToggle();
        }
    }
});

AJAX = getAjaxConnection();

function attachPopupEvents(bg, container, flagKey, callback) {
    // Aligning box in the middle
    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var popupHeight = $(container).height();
    var popupWidth = $(container).width();
    // centering
    $(container).css({
        "position" : "absolute",
        "top" : windowHeight / 2 - popupHeight / 2,
        "left" : windowWidth / 2 - popupWidth / 2
    });

    // aligning full bg
    $(bg).css({
        "height" : windowHeight
    });

    // Pop up the div and Bg
    if (popupFlags[flagKey] == 0) {
        $(bg).css({
            "opacity" : "0.7"
        });
        $(bg).fadeIn("slow");
        $(container).fadeIn("slow");
        popupFlags[flagKey] = 1;
    }

    if (typeof flagKey != 'undefined') {
        addEvent(window, 'keydown', function(e) {
            if (e.keyCode === 27 && popupFlags[flagKey] === 1) {
                e.preventDefault();
                $(bg).fadeOut("slow");
                $(container).fadeOut("slow");
                popupFlags[flagKey] = 0;
            }
        });
    }

    if (typeof callback != 'undefined') {
        callback();
    }
}

// Close Them
$("#popupClose").click(function() {
    closePopup();
});

function closePopup(bg, container) {
    if (popupStatus == 1) {
        $(bg).fadeOut("slow");
        $(container).fadeOut("slow");
        popupStatus = 0;
    }
}

function getAccountDropdown() {
    $('.account-dropdown').slideToggle();
}

// Initialize search suggestions
var searchSuggestions = [];
if (typeof searchDataSource != 'undefined') {
    searchSuggestions = JSON.parse(searchDataSource);
    searchSuggestions.sort();
    $("#searchbox").autocomplete({
        source : searchSuggestions,
        appendTo : '#jquery-autocomplete-results',
        position : {
            my : 'right top',
            at : 'center'
        },
        select : function(event, ui) {
            $('.searchform').submit();
        }
    });
}

function removeClass(el, className) {
    className = " " + className.trim(); // must keep a space before class name
    el.className = el.className.replace(className, "");
}

function getLoadingPopup() {
    var container = document.createElement('span');
    container.innerHTML = 'Loading...';
    attachPopupEvents($(APP_CONSTANTS.cssSelectors.popupBg)[0], container);
}

