EXPLORER_CONSTANTS = {
    baseURI : '/explorer/',
    isDeletekey : 'isdelete'
};

function deleteProgram(pid) {
    if (pid == '') {
        alert('No FileId mentiond');
        return false;
    } else {
        if (confirm('File deletion is not recoverable, Are you sure?')) {
            var formData = new FormData();
            formData.append(EXPLORER_CONSTANTS.isDeletekey, $(
                    '#' + EXPLORER_CONSTANTS.isDeletekey).val());
            AJAX.onreadystatechange = function() {
                if (AJAX.readyState == 4 && AJAX.status == 200) {
                    var response = JSON.parse(AJAX.responseText);
                    if (response.code == 'success') {
                        alert(response.msg);
                        window.location.href = '/';
                    } else if (response.code == 'error') {
                        alert(response.msg);
                    }
                }
            };

            AJAX.open("POST", EXPLORER_CONSTANTS.baseURI + 'delete/' + pid);
            AJAX.send(formData);
        }
    }
}