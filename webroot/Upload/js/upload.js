function getAjaxConnection() {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return xmlhttp;
}

UPLOAD_GLOBALS = {
    uploadMsgContainerId : 'upload-msg-container',
    uploadPath : '/upload',
    UPLOAD_ERROR_CODE : 'error',
    UPLOAD_SUCCESS_CODE : 'success'
};

var AJAX = getAjaxConnection();

function upload(formId) {
    if (validateUploadForm(formId)) {
        var formData = populateFormData(formId);
        AJAX.onreadystatechange = function() {
            if (AJAX.readyState == 4 && AJAX.status == 200) {
                var response = JSON.parse(AJAX.responseText);
                var msgContainerId = UPLOAD_GLOBALS.uploadMsgContainerId;
                var msgContainer = document.getElementById(msgContainerId);
                if (response.code == UPLOAD_GLOBALS.UPLOAD_SUCCESS_CODE) {
                    msgContainer.className = "success-msg-div";
                    document.getElementById(formId).reset();
                } else if (response.code == UPLOAD_GLOBALS.UPLOAD_ERROR_CODE) {
                    msgContainer.className = "error-msg-div";
                }
                msgContainer.innerHTML = response.msg;
                msgContainer.style.display = "block";
            }
        }
        AJAX.open("POST", UPLOAD_GLOBALS.uploadPath);
        AJAX.setRequestHeader('X-REQUESTED-WITH', 'XMLHttpRequest');
        AJAX.send(formData);

    } else {
        errorContainer = document
                .getElementById(UPLOAD_GLOBALS.uploadMsgContainerId);
        errorContainer.className = "error-msg-div";
        errorContainer.innerHTML = "Please fill all the fields";
        errorContainer.style.display = "block";
        return false;
    }
}

function populateFormData(formId) {
    var formElem = document.getElementById(formId);
    var formData = new FormData();
    formData.append('file-upload-action-name', document
            .getElementById('file-upload-action-name').value);
    formData.append('uploadfile', formElem.uploadfile.files[0]);
    formData.append('language_id', formElem.language_id.value);
    formData.append('category_id', formElem.category_id.value);
    formData.append('level', formElem.level.value);
    formData.append('program_title', formElem.program_title.value);
    formData.append('program_description', formElem.program_description.value);
    formData.append(APP_CONSTANTS.CSRF_TOKEN_NAME,
            formElem[APP_CONSTANTS.CSRF_TOKEN_NAME].value);
    formData.append('is_verified', (formElem.is_verified.checked == true) ? 1
            : 0);
    return formData;
}

function validateUploadForm(formId) {
    var error = false;
    var formElem = document.getElementById(formId);
    for ( var i = 0; i < formElem.length; i++) {
        if (formElem.hasOwnProperty(i)) {
            removeClass(formElem[i], "error-field");
        }
    }

    for ( var i = 0; i < formElem.length; i++) {
        if (formElem.hasOwnProperty(i)) {
            if (formElem[i].value.length == 0) {
                formElem[i].className += " error-field";
                error = true;
            }
        }
    }

    return (!error);
}
