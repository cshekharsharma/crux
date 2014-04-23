var popupStatus = 0;
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
    if (e.keyCode === 27 && popupStatus === 1) {
        e.preventDefault();
        $("#popup-opacity-background").fadeOut("slow");
        $("#popupDiv").fadeOut("slow");
        popupStatus = 0;
    }
});

AJAX = getAjaxConnection();
$(".change-password-link").click(function() {
    // Aligning our box in the middle
    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var popupHeight = $("#popupDiv").height();
    var popupWidth = $("#popupDiv").width();
    // centering
    $("#popupDiv").css({
        "position" : "absolute",
        "top" : windowHeight / 2 - popupHeight / 2,
        "left" : windowWidth / 2 - popupWidth / 2
    });

    // aligning our full bg
    $("#popup-opacity-background").css({
        "height" : windowHeight
    });

    // Pop up the div and Bg
    if (popupStatus == 0) {
        $("#popup-opacity-background").css({
            "opacity" : "0.7"
        });
        $("#popup-opacity-background").fadeIn("slow");
        $("#popupDiv").fadeIn("slow");
        popupStatus = 1;
    }

});

// Close Them
$("#popupClose").click(function() {
    closePopup();
});

function closePopup() {
    if (popupStatus == 1) {
        $("#popup-opacity-background").fadeOut("slow");
        $("#popupDiv").fadeOut("slow");
        popupStatus = 0;
    }
}

function getAccountDropdown() {
    $('.account-dropdown').slideToggle();
}

// Initialize search suggestions
var searchSuggestions = [];
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

