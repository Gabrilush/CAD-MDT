<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
$error = array();
if ($_SESSION['on_duty'] === "Dispatch" || $_SESSION['on_duty'] === "LEO") {
    $updated_desc = htmlspecialchars($_POST['callDesc']);

    $sql = "UPDATE 911calls SET call_description=? WHERE call_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$updated_desc, $_SESSION['viewingCallID']]);

    $error['msg'] = "";
    echo json_encode($error);
    exit();
}
