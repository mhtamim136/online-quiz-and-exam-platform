function showError(message) {
    var box = document.getElementById("formError");
    if (box) {
        box.innerHTML = message;
        box.style.display = "block";
    }
}

function clearError() {
    var box = document.getElementById("formError");
    if (box) {
        box.innerHTML = "";
        box.style.display = "none";
    }
}

function isEmpty(value) {
    return value.trim() === "";
}

function isValidEmail(email) {
    return email.indexOf("@") > 0 && email.indexOf(".") > 0;
}

function getValue(id) {
    var field = document.getElementById(id);
    if (!field) {
        return "";
    }
    return field.value;
}

function clearRegisterFieldErrors() {
    var form = document.getElementById("registerForm");
    if (!form) {
        return;
    }

    var messages = form.getElementsByClassName("field-error");
    for (var i = 0; i < messages.length; i++) {
        messages[i].innerHTML = "";
    }

    var fields = form.getElementsByTagName("input");
    for (var j = 0; j < fields.length; j++) {
        fields[j].className = fields[j].className.replace("input-error", "").trim();
    }
}

function showFieldError(id, message) {
    var field = document.getElementById(id);
    var box = document.getElementById(id + "Error");

    if (field && field.className.indexOf("input-error") === -1) {
        field.className = (field.className + " input-error").trim();
    }

    if (box) {
        box.innerHTML = message;
    }
}

function escapeHtml(value) {
    var div = document.createElement("div");
    div.appendChild(document.createTextNode(value === null ? "" : String(value)));
    return div.innerHTML;
}

function setupConfirmForms() {
    var forms = document.getElementsByClassName("confirm-form");
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener("submit", function (event) {
            var message = this.getAttribute("data-confirm");
            if (message && !confirm(message)) {
                event.preventDefault();
            }
        });
    }
}

function setupLoginValidation() {
    var form = document.getElementById("loginForm");
    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {
        clearError();
        var email = getValue("email");
        var password = getValue("password");

        if (isEmpty(email)) {
            showError("Email is required.");
            event.preventDefault();
            return;
        }

        if (!isValidEmail(email)) {
            showError("Please enter a valid email address.");
            event.preventDefault();
            return;
        }

        if (isEmpty(password)) {
            showError("Password is required.");
            event.preventDefault();
        }
    });
}

function setupPasswordToggle() {
    var buttons = document.getElementsByClassName("password-toggle");

    for (var i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener("click", function () {
            var targetId = this.getAttribute("data-target");
            var field = document.getElementById(targetId);

            if (!field) {
                return;
            }

            if (field.type === "password") {
                field.type = "text";
                this.className = "password-toggle active";
                this.setAttribute("aria-label", "Hide password");
                this.setAttribute("title", "Hide password");
            } else {
                field.type = "password";
                this.className = "password-toggle";
                this.setAttribute("aria-label", "Show password");
                this.setAttribute("title", "Show password");
            }
        });
    }
}

function setupRegisterRoleFields() {
    var role = document.getElementById("role");
    var studentFields = document.getElementsByClassName("student-only");
    var instructorFields = document.getElementsByClassName("instructor-only");
    var roleHelp = document.getElementById("roleHelp");

    if (!role) {
        return;
    }

    function showFields(fields, show) {
        for (var i = 0; i < fields.length; i++) {
            fields[i].style.display = show ? "block" : "none";
        }
    }

    function updateFields() {
        if (role.value === "instructor") {
            showFields(studentFields, false);
            showFields(instructorFields, true);
            if (roleHelp) {
                roleHelp.innerHTML = "Instructor requests stay pending until an admin approves the account.";
            }
        } else {
            showFields(studentFields, true);
            showFields(instructorFields, false);
            if (roleHelp) {
                roleHelp.innerHTML = "Student accounts can log in after successful registration.";
            }
        }
    }

    role.addEventListener("change", updateFields);
    updateFields();
}

function setupRegisterValidation() {
    var form = document.getElementById("registerForm");
    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {
        clearError();
        clearRegisterFieldErrors();

        var role = getValue("role");
        var name = getValue("name");
        var username = getValue("username");
        var email = getValue("email");
        var phone = getValue("phone");
        var department = getValue("department");
        var password = getValue("password");
        var confirmPassword = getValue("confirm_password");
        var hasError = false;

        if (isEmpty(name)) {
            showFieldError("name", "Full name is required.");
            hasError = true;
        }

        if (isEmpty(username)) {
            showFieldError("username", "Username is required.");
            hasError = true;
        }

        if (isEmpty(email)) {
            showFieldError("email", "Email is required.");
            hasError = true;
        } else if (!isValidEmail(email)) {
            showFieldError("email", "Please enter a valid email address.");
            hasError = true;
        }

        if (isEmpty(phone)) {
            showFieldError("phone", "Phone is required.");
            hasError = true;
        }

        if (!(role === "student" || role === "instructor")) {
            showError("Please select a valid account type.");
            event.preventDefault();
            return;
        }

        if (role === "student") {
            if (isEmpty(getValue("student_id"))) {
                showFieldError("student_id", "Student ID is required.");
                hasError = true;
            }

            if (isEmpty(getValue("program"))) {
                showFieldError("program", "Program is required.");
                hasError = true;
            }
        }

        if (isEmpty(department)) {
            showFieldError("department", "Department is required.");
            hasError = true;
        }

        if (isEmpty(password)) {
            showFieldError("password", "Password is required.");
            hasError = true;
        } else if (password.length < 4) {
            showFieldError("password", "Password must be at least 4 characters.");
            hasError = true;
        }

        if (isEmpty(confirmPassword)) {
            showFieldError("confirm_password", "Confirm password is required.");
            hasError = true;
        }

        if (!isEmpty(password) && !isEmpty(confirmPassword) && password !== confirmPassword) {
            showFieldError("password", "");
            showFieldError("confirm_password", "Passwords do not match.");
            hasError = true;
        }

        if (hasError) {
            event.preventDefault();
        }
    });
}

function setupCreateTaValidation() {
    var form = document.getElementById("createTaForm");
    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {
        clearError();
        var name = getValue("name");
        var username = getValue("username");
        var email = getValue("email");
        var password = getValue("password");
        var confirmPassword = getValue("confirm_password");

        if (isEmpty(name) || isEmpty(username) || isEmpty(email) || isEmpty(password) || isEmpty(confirmPassword)) {
            showError("Name, username, email, password, and confirm password are required.");
            event.preventDefault();
            return;
        }

        if (!isValidEmail(email)) {
            showError("Please enter a valid email address.");
            event.preventDefault();
            return;
        }

        if (password !== confirmPassword) {
            showError("Password and confirm password must match.");
            event.preventDefault();
        }
    });
}

function setupSubjectValidation() {
    var forms = document.getElementsByClassName("subject-form");
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener("submit", function (event) {
            clearError();
            var nameField = this.querySelector(".subject-name");
            if (nameField && isEmpty(nameField.value)) {
                showError("Subject name is required.");
                event.preventDefault();
            }
        });
    }
}

function setupAnnouncementValidation() {
    var form = document.getElementById("announcementForm");
    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {
        clearError();
        if (isEmpty(getValue("title")) || isEmpty(getValue("body")) || isEmpty(getValue("audience")) || isEmpty(getValue("status"))) {
            showError("Title, body, audience, and status are required.");
            event.preventDefault();
        }
    });
}

function setupSettingsValidation() {
    var forms = document.getElementsByClassName("setting-form");
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener("submit", function (event) {
            clearError();
            var valueField = this.querySelector(".setting-value");
            var typeField = this.querySelector(".setting-type");

            if (!valueField || !typeField) {
                return;
            }

            var value = valueField.value.trim();
            var settingType = typeField.value;

            if (value === "") {
                showError("Setting value is required.");
                event.preventDefault();
                return;
            }

            if (settingType === "integer" && parseInt(value, 10).toString() !== value) {
                showError("This setting must be an integer.");
                event.preventDefault();
                return;
            }

            if (settingType === "decimal" && isNaN(value)) {
                showError("This setting must be a decimal number.");
                event.preventDefault();
                return;
            }

            if (settingType === "boolean") {
                var lower = value.toLowerCase();
                if (!(lower === "true" || lower === "false" || lower === "1" || lower === "0")) {
                    showError("This setting must be true, false, 1, or 0.");
                    event.preventDefault();
                }
            }
        });
    }
}

function setupIntegrityValidation() {
    var form = document.getElementById("integrityForm");
    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {
        clearError();
        if (isEmpty(getValue("resolution_notes"))) {
            showError("Resolution notes are required.");
            event.preventDefault();
        }
    });
}

function setupLiveUserSearch() {
    var searchBox = document.getElementById("liveUserSearch");
    var tableBody = document.getElementById("userTableBody");
    if (!searchBox || !tableBody) {
        return;
    }

    var roleFilter = document.getElementById("roleFilter");
    var activeFilter = document.getElementById("activeFilter");

    function runSearch() {
        var keyword = searchBox.value;
        var role = roleFilter ? roleFilter.value : "";
        var active = activeFilter ? activeFilter.value : "";
        var url = "api/admin_user_search.php?keyword=" + encodeURIComponent(keyword) +
            "&role=" + encodeURIComponent(role) +
            "&active=" + encodeURIComponent(active);

        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    renderUserRows(response.users);
                }
            }
        };
        xhr.send();
    }

    function renderUserRows(users) {
        var html = "";

        if (users.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="9">No users found.</td></tr>';
            return;
        }

        for (var i = 0; i < users.length; i++) {
            var user = users[i];
            var activeText = parseInt(user.is_active, 10) === 1 ? "Active" : "Inactive";
            var activeClass = parseInt(user.is_active, 10) === 1 ? "good" : "bad";

            html += "<tr>";
            html += "<td>" + escapeHtml(user.id) + "</td>";
            html += "<td><strong>" + escapeHtml(user.name) + "</strong><br><span class=\"muted\">" + escapeHtml(user.username) + "</span></td>";
            html += "<td>" + escapeHtml(user.email) + "</td>";
            html += "<td>" + escapeHtml(user.phone) + "</td>";
            html += "<td>" + escapeHtml(user.role) + "</td>";
            html += "<td>" + escapeHtml(user.student_id) + "</td>";
            html += "<td>" + escapeHtml(user.approval_status) + "</td>";
            html += "<td><span class=\"status " + activeClass + "\">" + activeText + "</span></td>";
            html += "<td><a class=\"btn small\" href=\"admin.php?action=user_view&id=" + encodeURIComponent(user.id) + "\">View</a></td>";
            html += "</tr>";
        }

        tableBody.innerHTML = html;
    }

    searchBox.addEventListener("keyup", runSearch);
    if (roleFilter) {
        roleFilter.addEventListener("change", runSearch);
    }
    if (activeFilter) {
        activeFilter.addEventListener("change", runSearch);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    setupConfirmForms();
    setupLoginValidation();
    setupPasswordToggle();
    setupRegisterRoleFields();
    setupRegisterValidation();
    setupCreateTaValidation();
    setupSubjectValidation();
    setupAnnouncementValidation();
    setupSettingsValidation();
    setupIntegrityValidation();
    setupLiveUserSearch();
});
