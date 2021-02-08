<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if (!isset($_SESSION['character_full_name'])) {
    header('Location: ../../../../' . $url['civilian'] . '?v=nosession');
    exit();
}

$error = array();
$stmt = $pdo->prepare("DELETE FROM 911calls WHERE caller_id =:character_id");
$stmt->bindParam(':character_id', $_SESSION['character_id']);
$stmt->execute();
sleep(3);
$stmt2 = $pdo->prepare("DELETE FROM arrest_reports WHERE suspect =:character_full_name");
$stmt2->bindParam(':character_full_name', $_SESSION['character_full_name']);
$stmt2->execute();
$stmt3 = $pdo->prepare("DELETE FROM tickets WHERE suspect =:character_full_name");
$stmt3->bindParam(':character_full_name', $_SESSION['character_full_name']);
$stmt3->execute();
sleep(3);
$stmt4 = $pdo->prepare("DELETE FROM vehicles WHERE vehicle_owner =:character_id");
$stmt4->bindParam(':character_id', $_SESSION['character_id']);
$stmt4->execute();
$stmt5 = $pdo->prepare("DELETE FROM warrants WHERE wanted_person =:character_full_name");
$stmt5->bindParam(':character_full_name', $_SESSION['character_full_name']);
$stmt5->execute();
sleep(3);
$stmt6 = $pdo->prepare("DELETE FROM weapons WHERE wpn_owner =:character_id");
$stmt6->bindParam(':character_id', $_SESSION['character_id']);
$stmt6->execute();
$stmt7 = $pdo->prepare("DELETE FROM characters WHERE character_id =:character_id");
$stmt7->bindParam(':character_id', $_SESSION['character_id']);
$stmt7->execute();
header('Location: ../../../../' . $url['civilian'] . '?v=nosession&error=character-deleted');
exit();
