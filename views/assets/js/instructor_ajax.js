document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('[data-enrollment-action]');
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', function () {
            var row = this.parentNode.parentNode;
            var request = new XMLHttpRequest();
            var params = 'enrollment_id=' + encodeURIComponent(this.getAttribute('data-enrollment-id')) +
                '&decision=' + encodeURIComponent(this.getAttribute('data-enrollment-action'));

            request.open('POST', 'api/instructor_enrollment_requests.php', true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            request.onreadystatechange = function () {
                if (request.readyState === 4) {
                    try {
                        var response = JSON.parse(request.responseText);
                        if (response.success) {
                            row.parentNode.removeChild(row);
                        } else {
                            alert(response.message);
                        }
                    } catch (error) {
                        alert('Request failed.');
                    }
                }
            };
            request.send(params);
        });
    }
});
