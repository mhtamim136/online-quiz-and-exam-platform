<?php
function instructorAnnouncementListController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $announcements = instructorAnnouncementAll($courseId, instructorCurrentId());
    instructorRender('instructor_announcements', array('pageTitle' => 'Announcements', 'course' => $course, 'announcements' => $announcements));
}

function instructorAnnouncementSave($courseId, $announcementId)
{
    $errors = array();
    $title = instructorPost('title');
    $body = instructorPost('body');
    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    if ($body === '') {
        $errors[] = 'Announcement body is required.';
    }
    if (count($errors) === 0) {
        $attachment = instructorUploadFile('attachment', __DIR__ . '/../uploads/instructor_announcements');
        if ($announcementId > 0) {
            instructorAnnouncementUpdate($announcementId, instructorCurrentId(), $title, $body, $attachment);
        } else {
            instructorAnnouncementCreate($courseId, instructorCurrentId(), $title, $body, $attachment);
        }
    }
    return array($errors, array('title' => $title, 'body' => $body));
}

function instructorAnnouncementCreateController()
{
    $courseId = instructorInt('course_id');
    $course = instructorCourseFind($courseId, instructorCurrentId());
    if (!$course) {
        instructorRedirect('instructor.php?action=courses');
    }
    $errors = array();
    $announcement = array('title' => '', 'body' => '');
    if (instructorIsPost()) {
        list($errors, $announcement) = instructorAnnouncementSave($courseId, 0);
        if (count($errors) === 0) {
            instructorRedirect('instructor.php?action=announcements&course_id=' . $courseId);
        }
    }
    instructorRender('instructor_create_announcement', array('pageTitle' => 'Create Announcement', 'course' => $course, 'announcement' => $announcement, 'errors' => $errors));
}

function instructorAnnouncementEditController()
{
    $announcementId = instructorInt('id');
    $announcement = instructorAnnouncementFind($announcementId, instructorCurrentId());
    if (!$announcement) {
        instructorRedirect('instructor.php?action=courses');
    }
    $errors = array();
    if (instructorIsPost()) {
        list($errors, $announcement) = instructorAnnouncementSave($announcement['course_id'], $announcementId);
        $announcement['course_id'] = instructorAnnouncementFind($announcementId, instructorCurrentId())['course_id'];
        if (count($errors) === 0) {
            instructorRedirect('instructor.php?action=announcements&course_id=' . $announcement['course_id']);
        }
    }
    instructorRender('instructor_edit_announcement', array('pageTitle' => 'Edit Announcement', 'announcement' => $announcement, 'errors' => $errors));
}
