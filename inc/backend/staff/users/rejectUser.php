<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

if (staff_access === 'true' && staff_siteSettings === 'true') {
    $id = strip_tags($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE `user_id`= ?");
    $result = $stmt->execute([$id]);
    logAction('Rejected A New User ID:' . $id, $user['username']);
}
else {
    logAction('Attempted To Reject A New User ID:' . $id, $user['username']);
    $error['msg'] = "No Permissions";
    echo json_encode($error);
    exit();
}
