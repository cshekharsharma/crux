var EDITOR_CONSTANTS = {
    requestPath : '/editor',
    msgContainerId : 'msg-container'
};

var editor = ace.edit("code-editor");
editor.setTheme("ace/theme/" + defaultEditorTheme);
editor.getSession().setMode("ace/mode/" + defaultEditorMode);
editor.setShowInvisibles(true);
EDITOR_CONSTANTS.editor = editor;

//Enabling ctl+S to be used for Save action
addEvent(window, 'keydown', function(e) {
    if (e.ctrlKey && e.keyCode === 83) {
        e.preventDefault();
        submitCode($('#editor-form')[0]);
    }
});


$('#language_id').change(function() {
    var editor = ace.edit("code-editor");
    editor.setTheme("ace/theme/" + defaultEditorTheme);
    editor.getSession().setMode("ace/mode/" + $(this).val());
    EDITOR_CONSTANTS.editor = editor;
});

$('#submit_code').click(function() {
    submitCode($('#editor-form')[0]);
});

function submitCode(formElem) {
    errObj = validateForm(formElem);
    if (!errObj.isError) {
        formData = populateFormData(formElem);
        AJAX.onreadystatechange = function() {
            if (AJAX.readyState == 4 && AJAX.status == 200) {
                var response = JSON.parse(AJAX.responseText);
                if (response.code == 'success') {
                    $('#' + EDITOR_CONSTANTS.msgContainerId).addClass(
                            'success-msg-div');
                    if (typeof response.detail === 'object') {
                        $('#isupdate').val(response.detail.isUpdate);
                        $('#programid').val(response.detail.programId);
                    }
                } else if (response.code == 'error') {
                    $('#' + EDITOR_CONSTANTS.msgContainerId).addClass(
                            'error-field');
                }
                $('#' + EDITOR_CONSTANTS.msgContainerId).show();
                $('#' + EDITOR_CONSTANTS.msgContainerId).html(response.msg);
                setIntervalObject = setInterval(function() {
                    $('#' + EDITOR_CONSTANTS.msgContainerId).hide('drop', {
                        direction: 'up',
                    }, 'slow');
                    clearInterval(setIntervalObject);
                }, 3000);

            }
        };

        AJAX.open("POST", EDITOR_CONSTANTS.requestPath);
        AJAX.setRequestHeader('HTTP_X_REQUESTED_WITH', 'XMLHttpRequest');
        AJAX.send(formData);
    } else {
        $('#' + EDITOR_CONSTANTS.msgContainerId).addClass('error-field');
        $('#' + EDITOR_CONSTANTS.msgContainerId).html(errObj.errMsg);
        return false;
    }
}

function validateForm(formElem) {
    errorObject = {
        isError : false,
        errorMsg : ''
    };
    $('#' + EDITOR_CONSTANTS.msgContainerId).removeClass('error-field');
    var elems = $('.apply-validation');
    for ( var i = 0; i < elems.length; i++) {
        removeClass(elems[i], 'error-field');
    }

    for ( var i = 0; i < elems.length; i++) {
        if (elems[i].value == '') {
            elems[i].className += " error-field";
            errorObject.isError = true;
            errorObject.errorMsg = 'Can\'t leave a field empty';
        }

        if (elems[i].id == "filename") {
            val = elems[i].value.trim();
            if (val.indexOf(" ") !== -1 || val.indexOf(".") === -1
                    || val.indexOf(".") === (val.length - 1)) {
                errorObject.isError = true;
                errorObject.errorMsg = 'Invalid file name';
                elems[i].className += " error-field";
            }
        }
    }
    return errorObject;
    ;
}

function populateFormData(formElem) {
    var formData = new FormData();
    formData.append('editorContents', EDITOR_CONSTANTS.editor.getValue());
    formData.append('isupdate', formElem.isupdate.value);
    formData.append('programid', formElem.programid.value);
    formData.append('title', formElem.programtitle.value);
    formData.append('actual_file_name', formElem.filename.value);
    formData.append('fk_language', formElem.language_id.value);
    formData.append('fk_category', formElem.category_id.value);
    formData.append('level', formElem.level.value);
    formData.append('description', formElem.description.value);
    formData.append('is_verified', (formElem.verified.checked) ? 1 : 0);
    formData.append('edit_action_name', $('#edit_action_name').val());
    return formData;
};
