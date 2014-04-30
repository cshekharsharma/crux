var EDITOR_CONSTANTS = {
    editor : '',
    requestPath : '/editor'
};

$('#language_id').change(function() {
    var editor = ace.edit("code-editor");
    editor.setTheme("ace/theme/chrome");
    editor.getSession().setMode("ace/mode/" + $(this).val());
    EDITOR_CONSTANTS.editor = editor;
});

$('#submit_code').click(function() {
    submitCode($('#editor-form')[0]);
});

function submitCode(formElem) {
    formData = populateFormData(formElem);
    AJAX.onreadystatechange = function() {
        if (AJAX.readyState == 4 && AJAX.status == 200) {
            var response = JSON.parse(AJAX.responseText);
            if (response.code == 'success') {
                alert('success');
            } else if (response.code == 'error') {
                alert('error');
            }
        }
    };

    AJAX.open("POST", EDITOR_CONSTANTS.requestPath);
    AJAX.send(formData);
}

function populateFormData(formElem) {
    var formData = new FormData();
    formData.append('title', formElem.programtitle.value);
    formData.append('actual_file_name', formElem.filename.value);
    formData.append('fk_language', formElem.language_id.value);
    formData.append('fk_category', formElem.category_id.value);
    formData.append('level', formElem.level.value);
    formData.append('description', formElem.description.value);
    formData.append('is_verified', (formElem.verified.checked) ? 1: 0);
    formData.append('edit_action_name', $('#edit_action_name').val());
    return formData;
};