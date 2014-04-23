addEvent(window, 'load', function() {
    var editor = document.getElementById('code-editor');
    addEvent(editor, 'keypress', function(e) {
        if (e.keyCode == 9) {
           alert('tab pressed');
        }
    });
});