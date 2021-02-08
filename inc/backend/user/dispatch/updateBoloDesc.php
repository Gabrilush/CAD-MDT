<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
$error = array();
if ($_SESSION['on_duty'] === "Dispatch" || $_SESSION['on_duty'] === "LEO") {
    $updated_desc = htmlspecialchars($_POST['boloDesc']);

    $sql = "UPDATE bolos SET description=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$updated_desc, $_SESSION['viewingBoloID']]);

    $error['msg'] = "";
    echo json_encode($error);
    exit();
}
