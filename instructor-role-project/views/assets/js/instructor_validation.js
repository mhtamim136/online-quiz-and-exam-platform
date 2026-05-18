function instructorShowClientError(form, message) {
    var box = form.querySelector('.instructor-client-error');
    if (!box) {
        box = document.createElement('div');
        box.className = 'instructor-alert error instructor-client-error';
        form.insertBefore(box, form.firstChild);
    }
    box.innerHTML = message;
}

document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('[data-instructor-validate]');
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener('submit', function (event) {
            var fields = this.querySelectorAll('[data-instructor-label]');
            for (var j = 0; j < fields.length; j++) {
                if (fields[j].value.replace(/\s+/g, '') === '') {
                    event.preventDefault();
                    instructorShowClientError(this, fields[j].getAttribute('data-instructor-label') + ' is required.');
                    return;
                }
            }
        });
    }
});
