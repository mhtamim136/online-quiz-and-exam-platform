function taValue(id) {
    var field = document.getElementById(id);
    if (!field) {
        return "";
    }
    return field.value.trim();
}

function taShowError(id, message) {
    var box = document.getElementById(id);
    if (box) {
        box.innerHTML = message;
        box.style.display = "block";
    }
}

function taClearError(id) {
    var box = document.getElementById(id);
    if (box) {
        box.innerHTML = "";
        box.style.display = "none";
    }
}

function taEscapeHtml(value) {
    var div = document.createElement("div");
    div.appendChild(document.createTextNode(value === null ? "" : String(value)));
    return div.innerHTML;
}

function taAttachSimpleValidation(formId, errorId, rules) {
    var form = document.getElementById(formId);
    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {
        taClearError(errorId);
        for (var i = 0; i < rules.length; i++) {
            if (rules[i].check()) {
                taShowError(errorId, rules[i].message);
                event.preventDefault();
                return;
            }
        }
    });
}

function taConfirmForms() {
    var forms = document.getElementsByClassName("ta-confirm-form");
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener("submit", function (event) {
            var message = this.getAttribute("data-confirm-message") || "Are you sure?";
            if (!confirm(message)) {
                event.preventDefault();
            }
        });
    }
}

function taSetupValidation() {
    taAttachSimpleValidation("taProfileForm", "profileError", [
        { check: function () { return taValue("name") === ""; }, message: "Name is required." }
    ]);

    taAttachSimpleValidation("taPasswordForm", "passwordError", [
        { check: function () { return taValue("current_password") === "" || taValue("new_password") === "" || taValue("confirm_password") === ""; }, message: "All password fields are required." },
        { check: function () { return taValue("new_password").length > 0 && taValue("new_password").length < 4; }, message: "New password must be at least 4 characters." },
        { check: function () { return taValue("new_password") !== taValue("confirm_password"); }, message: "New password and confirm password must match." }
    ]);

    taAttachSimpleValidation("taQuizForm", "quizError", [
        { check: function () { return taValue("title") === ""; }, message: "Quiz title is required." },
        { check: function () { return isNaN(taValue("time_limit_minutes")) || Number(taValue("time_limit_minutes")) <= 0; }, message: "Time limit must be a positive number." },
        { check: function () { return isNaN(taValue("total_marks")) || Number(taValue("total_marks")) <= 0; }, message: "Total marks must be a positive number." }
    ]);

    taAttachSimpleValidation("taQuestionForm", "questionError", [
        { check: function () { return taValue("question_text") === ""; }, message: "Question text is required." },
        { check: function () { return taValue("option0") === "" || taValue("option1") === "" || taValue("option2") === "" || taValue("option3") === ""; }, message: "All four options are required." }
    ]);

    taAttachSimpleValidation("taAnnouncementForm", "announcementError", [
        { check: function () { return taValue("title") === "" || taValue("body") === ""; }, message: "Title and body are required." }
    ]);

    taAttachSimpleValidation("taMaterialForm", "materialError", [
        { check: function () { return taValue("title") === ""; }, message: "Material title is required." },
        { check: function () { return !(taValue("material_type") === "document" || taValue("material_type") === "link" || taValue("material_type") === "video"); }, message: "Select a valid material type." },
        { check: function () { return taValue("material_type") === "document" && taValue("material_file") === "" && taValue("material_id") === "0"; }, message: "Document materials require a file upload." },
        { check: function () { return (taValue("material_type") === "link" || taValue("material_type") === "video") && taValue("external_link") === ""; }, message: "External URL is required for link or video materials." },
        { check: function () { return (taValue("material_type") === "link" || taValue("material_type") === "video") && taValue("external_link") !== "" && !(taValue("external_link").indexOf("http://") === 0 || taValue("external_link").indexOf("https://") === 0); }, message: "External URL must start with http:// or https://." },
        { check: function () { return (taValue("material_type") === "link" || taValue("material_type") === "video") && taValue("material_file") !== ""; }, message: "Use an external URL for link or video materials, not a file upload." }
    ]);

    taAttachSimpleValidation("taQaAnswerForm", "qaAnswerError", [
        { check: function () { return taValue("body") === ""; }, message: "Answer body is required." }
    ]);

    taAttachSimpleValidation("taDoubtSessionForm", "doubtSessionError", [
        { check: function () { return taValue("title") === ""; }, message: "Session title is required." },
        { check: function () { return taValue("scheduled_at") === ""; }, message: "Scheduled date and time are required." },
        { check: function () { return isNaN(taValue("duration_minutes")) || Number(taValue("duration_minutes")) <= 0; }, message: "Duration must be a positive number." },
        { check: function () { return taValue("location_or_link") === ""; }, message: "Location or meeting link is required." }
    ]);
}

function taLoadAtRiskStudents() {
    var form = document.getElementById("taAtRiskForm");
    var tableBody = document.getElementById("atRiskTableBody");
    if (!form || !tableBody) {
        return;
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        var courseId = taValue("course_id");
        var threshold = taValue("threshold");
        if (threshold === "" || isNaN(threshold)) {
            taShowError("atRiskError", "Threshold must be a number.");
            return;
        }
        if (Number(threshold) < 0 || Number(threshold) > 100) {
            taShowError("atRiskError", "Threshold must be between 0 and 100.");
            return;
        }
        taClearError("atRiskError");

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "api/ta_at_risk_students.php?course_id=" + encodeURIComponent(courseId) + "&threshold=" + encodeURIComponent(threshold), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status !== 200) {
                    taShowError("atRiskError", "Could not load at-risk students.");
                    return;
                }
                var data = JSON.parse(xhr.responseText);
                tableBody.innerHTML = "";
                if (!data.success) {
                    taShowError("atRiskError", data.message);
                    return;
                }
                for (var i = 0; i < data.students.length; i++) {
                    var row = data.students[i];
                    tableBody.innerHTML += "<tr><td>" + taEscapeHtml(row.student_name) + "</td><td>" + taEscapeHtml(row.student_id) + "</td><td>" + taEscapeHtml(row.course_title) + "</td><td>" + taEscapeHtml(row.quiz_title) + "</td><td>" + taEscapeHtml(row.percentage_score) + "%</td><td>" + taEscapeHtml(row.average_score) + "%</td></tr>";
                }
                if (data.students.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan=\"6\">No at-risk students found.</td></tr>";
                }
            }
        };
        xhr.send();
    });
}

document.addEventListener("DOMContentLoaded", function () {
    taConfirmForms();
    taSetupValidation();
    taLoadAtRiskStudents();
});
