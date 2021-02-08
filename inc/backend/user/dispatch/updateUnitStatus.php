<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch") {
    $unit = strip_tags($_GET['unit']);
    $status = strip_tags($_GET['status']);

    if ($status === "10-42") {
        $stmt = $pdo->prepare("DELETE FROM on_duty WHERE `id`= ?");
        $result = $stmt->execute([$unit]);
    }
    else {
        $sql = "UPDATE on_duty SET status=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status, $unit]);
    }
}
