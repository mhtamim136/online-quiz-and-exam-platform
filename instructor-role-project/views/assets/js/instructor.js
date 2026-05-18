document.addEventListener('DOMContentLoaded', function () {
    var messages = document.querySelectorAll('.instructor-alert');
    for (var i = 0; i < messages.length; i++) {
        messages[i].setAttribute('role', 'alert');
    }
});
