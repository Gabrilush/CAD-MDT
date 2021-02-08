<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch") {
    $unit = strip_tags($_GET['unit']);

    $sql = "INSERT INTO assigned_callunits (call_id, unit_id) VALUES (?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['viewingCallID'], $unit]);

    $sql2 = "SELECT * FROM 911calls WHERE call_id= ?";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([$_SESSION['viewingCallID']]);
    $callInfo = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($callInfo['call_status'] === "NOT ASSIGNED") {
        $sql3 = "UPDATE 911calls SET call_status=? WHERE call_id=?";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute(['ASSIGNED', $_SESSION['viewingCallID']]);
    }

}
