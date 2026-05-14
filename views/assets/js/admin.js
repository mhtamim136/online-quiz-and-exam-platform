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
    setupCreateTaValidation();
    setupSubjectValidation();
    setupAnnouncementValidation();
    setupSettingsValidation();
    setupIntegrityValidation();
    setupLiveUserSearch();
});
