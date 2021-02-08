<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch" || $_SESSION['on_duty'] === "LEO") {
    $stmt = $pdo->prepare("DELETE FROM bolos WHERE `id`=:id");
    $stmt->bindValue(':id', $_SESSION['viewingBoloID']);
    $result = $stmt->execute();
}
