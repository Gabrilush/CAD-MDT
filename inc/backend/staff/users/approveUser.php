<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

if (staff_access === 'true' && staff_siteSettings === 'true') {
    $id = strip_tags($_GET['id']);
    $result = $pdo->prepare("UPDATE `users` SET `usergroup`= ? WHERE `user_id` = ?")
        ->execute(['User', $id]);

    logAction('Approved A New User ID:' . $id, $user['username']);
}
else {
    logAction('Attempted To Approve A New User ID:' . $id, $user['username']);
    $error['msg'] = "No Permissions";
    echo json_encode($error);
    exit();
}
