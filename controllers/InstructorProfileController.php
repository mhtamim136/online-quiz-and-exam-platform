<?php
function instructorProfileController()
{
    $errors = array();
    $success = '';
    $profile = instructorProfileFind(instructorCurrentId());

    if (instructorIsPost()) {
        $name = instructorPost('name');
        $department = instructorPost('department');
        $bio = instructorPost('bio');
        if ($name === '') {
            $errors[] = 'Name is required.';
        }
        if ($department === '') {
            $errors[] = 'Department is required.';
        }
        if (count($errors) === 0) {
            $picture = instructorUploadFile('profile_pic', __DIR__ . '/../uploads/instructor_profile_pics');
            instructorProfileUpdate(instructorCurrentId(), $name, $department, $bio, $picture);
            $_SESSION['instructor_name'] = $name;
            $success = 'Profile updated successfully.';
            $profile = instructorProfileFind(instructorCurrentId());
        }
    }

    instructorRender('instructor_profile', array('pageTitle' => 'Profile', 'profile' => $profile, 'errors' => $errors, 'success' => $success));
}
