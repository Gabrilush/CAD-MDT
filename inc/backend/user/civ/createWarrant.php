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

$newWarrant['warrant_reason'] = !empty($_POST['warrant_reason']) ? trim($_POST['warrant_reason']) : null;
$newWarrant['warrant_reason'] = strip_tags($_POST['warrant_reason']);
$error = array();

$sql = "INSERT INTO warrants (issued_on, signed_by, reason, wanted_person, wanted_person_id) VALUES (
  :issued_on,
  :signed_by,
  :reason,
  :wanted_person,
  :wanted_person_id
  )";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':issued_on', $us_date . ' ' . $time);
$stmt->bindValue(':signed_by', $_SESSION['character_full_name']);
$stmt->bindValue(':reason', $newWarrant['warrant_reason']);
$stmt->bindValue(':wanted_person', $_SESSION['character_full_name']);
$stmt->bindValue(':wanted_person_id', $_SESSION['character_id']);
$result = $stmt->execute();
if ($result) {
    $error['msg'] = "";
    echo json_encode($error);
    exit();
}
