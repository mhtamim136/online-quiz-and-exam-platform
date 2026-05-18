(function () {
    function byId(id) {
        return document.getElementById(id);
    }

    function value(id) {
        var field = byId(id);
        return field ? field.value.trim() : '';
    }

    function showErrors(containerId, errors) {
        var box = byId(containerId);
        if (!box) {
            return;
        }

        box.innerHTML = '';
        if (errors.length === 0) {
            return;
        }

        var ul = document.createElement('ul');
        for (var i = 0; i < errors.length; i++) {
            var li = document.createElement('li');
            li.textContent = errors[i];
            ul.appendChild(li);
        }
        box.appendChild(ul);
    }

    function looksLikeEmail(email) {
        return email.indexOf('@') > 0 && email.indexOf('.') > email.indexOf('@') + 1;
    }

    var loginForm = byId('studentLoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            var errors = [];
            var email = value('login_email');
            var password = value('login_password');

            if (email === '') {
                errors.push('Email address is required.');
            } else if (!looksLikeEmail(email)) {
                errors.push('Please enter a valid email address.');
            }

            if (password === '') {
                errors.push('Password is required.');
            }

            showErrors('studentLoginErrors', errors);
            if (errors.length > 0) {
                event.preventDefault();
            }
        });
    }

    var registerForm = byId('studentRegisterForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            var errors = [];
            var email = value('register_email');
            var password = value('register_password');
            var confirmPassword = value('register_confirm_password');

            if (value('register_name') === '') {
                errors.push('Name is required.');
            }
            if (email === '') {
                errors.push('Email address is required.');
            } else if (!looksLikeEmail(email)) {
                errors.push('Please enter a valid email address.');
            }
            if (value('register_student_id') === '') {
                errors.push('Student ID is required.');
            }
            if (value('register_program') === '') {
                errors.push('Program is required.');
            }
            if (value('register_phone') === '') {
                errors.push('Phone number is required.');
            }
            if (password.length < 6) {
                errors.push('Password must be at least 6 characters long.');
            }
            if (password !== confirmPassword) {
                errors.push('Password and confirm password do not match.');
            }

            showErrors('studentRegisterErrors', errors);
            if (errors.length > 0) {
                event.preventDefault();
            }
        });
    }

    var profileForm = byId('studentProfileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function (event) {
            var errors = [];

            if (value('profile_name') === '') {
                errors.push('Name is required.');
            }
            if (value('profile_phone') === '') {
                errors.push('Phone number is required.');
            }
            if (value('profile_program') === '') {
                errors.push('Program is required.');
            }

            showErrors('studentProfileErrors', errors);
            if (errors.length > 0) {
                event.preventDefault();
            }
        });
    }

    var passwordForm = byId('studentPasswordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function (event) {
            var errors = [];
            var newPassword = value('password_new');
            var confirmPassword = value('password_confirm');

            if (value('password_current') === '') {
                errors.push('Current password is required.');
            }
            if (newPassword.length < 6) {
                errors.push('New password must be at least 6 characters long.');
            }
            if (newPassword !== confirmPassword) {
                errors.push('New password and confirm password do not match.');
            }

            showErrors('studentPasswordErrors', errors);
            if (errors.length > 0) {
                event.preventDefault();
            }
        });
    }

    var qaForm = byId('studentQaForm');
    if (qaForm) {
        qaForm.addEventListener('submit', function (event) {
            var errors = [];
            if (value('qa_title') === '') {
                errors.push('Question title is required.');
            }
            if (value('qa_body') === '') {
                errors.push('Question body is required.');
            }

            showErrors('studentQaErrors', errors);
            if (errors.length > 0) {
                event.preventDefault();
            }
        });
    }

    var quizForm = byId('studentQuizForm');
    if (quizForm) {
        quizForm.addEventListener('submit', function (event) {
            var ok = confirm('Submit this quiz now? You cannot edit answers after submission.');
            if (!ok) {
                event.preventDefault();
            }
        });
    }

    var bookingForms = document.getElementsByClassName('studentBookingForm');
    for (var b = 0; b < bookingForms.length; b++) {
        bookingForms[b].addEventListener('submit', function (event) {
            var ok = confirm('Book this doubt session?');
            if (!ok) {
                event.preventDefault();
            }
        });
    }

    var countdown = byId('studentQuizCountdown');
    if (countdown) {
        var minutes = parseInt(countdown.getAttribute('data-minutes'), 10);
        var secondsLeft = minutes * 60;
        var timer = setInterval(function () {
            var minutesLeft = Math.floor(secondsLeft / 60);
            var secondsPart = secondsLeft % 60;
            countdown.textContent = 'Time left: ' + minutesLeft + 'm ' + secondsPart + 's';
            secondsLeft--;

            if (secondsLeft < 0) {
                clearInterval(timer);
                var form = byId('studentQuizForm');
                if (form) {
                    form.submit();
                }
            }
        }, 1000);
    }

    function loadLeaderboard() {
        var select = byId('leaderboardQuizSelect');
        var body = byId('leaderboardBody');
        var message = byId('leaderboardMessage');

        if (!select || !body || !message) {
            return;
        }

        var quizId = select.value;
        body.innerHTML = '';
        message.textContent = 'Loading leaderboard...';

        if (quizId === '') {
            message.textContent = 'Please select a quiz.';
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) {
                return;
            }

            if (xhr.status !== 200) {
                message.textContent = 'Could not load leaderboard.';
                return;
            }

            var data;
            try {
                data = JSON.parse(xhr.responseText);
            } catch (error) {
                message.textContent = 'Invalid leaderboard response.';
                return;
            }

            if (!data.success) {
                message.textContent = data.message || 'Leaderboard is not available.';
                return;
            }

            if (data.rows.length === 0) {
                message.textContent = 'No attempts found for this quiz yet.';
                return;
            }

            message.textContent = '';
            for (var i = 0; i < data.rows.length; i++) {
                var tr = document.createElement('tr');
                var rank = document.createElement('td');
                var name = document.createElement('td');
                var studentId = document.createElement('td');
                var score = document.createElement('td');
                var attempts = document.createElement('td');

                rank.textContent = i + 1;
                name.textContent = data.rows[i].name;
                studentId.textContent = data.rows[i].student_id || '';
                score.textContent = data.rows[i].best_score;
                attempts.textContent = data.rows[i].attempt_count;

                tr.appendChild(rank);
                tr.appendChild(name);
                tr.appendChild(studentId);
                tr.appendChild(score);
                tr.appendChild(attempts);
                body.appendChild(tr);
            }
        };

        xhr.open('GET', 'api/student_leaderboard.php?quiz_id=' + encodeURIComponent(quizId), true);
        xhr.send();
    }

    var leaderboardSelect = byId('leaderboardQuizSelect');
    if (leaderboardSelect) {
        leaderboardSelect.addEventListener('change', loadLeaderboard);
        loadLeaderboard();
    }
})();
