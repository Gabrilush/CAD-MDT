<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch" || $_SESSION['on_duty'] === "LEO") {
    $sql = "UPDATE 911calls SET call_status=?, call_isPriority=? WHERE call_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['Archived', 'false', $_SESSION['viewingCallID']]);
}
