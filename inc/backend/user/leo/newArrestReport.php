<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if (!isset($_SESSION['on_duty'])) {
	header('Location: ../../../../' . $url['leo'] . '?v=nosession');
	exit();
}

// Page PHP

$error = array();
$newArrestReport['suspect_id'] = !empty($_POST['suspect']) ? trim($_POST['suspect']) : null;
$newArrestReport['reason'] = !empty($_POST['reason']) ? trim($_POST['reason']) : null;

$newArrestReport['suspect_id'] = strip_tags($_POST['suspect']);
$newArrestReport['reason'] = strip_tags($_POST['reason']);


if (empty($newArrestReport['suspect_id']) || $newArrestReport['suspect_id'] === "Search") {
  $error['msg'] = "Please select a person to ticket!";
  echo json_encode($error);
  exit();
}

$sql  = "SELECT * FROM characters WHERE character_id = :character_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':character_id', $newArrestReport['suspect_id']);
$stmt->execute();
$suspectdb = $stmt->fetch(PDO::FETCH_ASSOC);
if ($suspectdb === false) {
  $error['msg'] = "Database Error";
  echo json_encode($error);
  exit();
} else {
  $sql2          = "INSERT INTO arrest_reports (arresting_officer, suspect, suspect_id, timestamp, summary) VALUES (
    :officer, 
    :suspect,
    :suspect_id,
    :created_on,
    :summary
    )";
  $stmt2         = $pdo->prepare($sql2);
  $stmt2->bindValue(':officer', $_SESSION['identity_name']);
  $stmt2->bindValue(':suspect', $suspectdb['first_name'] . ' ' . $suspectdb['last_name']);
  $stmt2->bindValue(':suspect_id', $newArrestReport['suspect_id']);
  $stmt2->bindValue(':created_on', $us_date . ' ' . $time);
  $stmt2->bindValue(':summary', $newArrestReport['reason']);
  $result = $stmt2->execute();
  if ($result) {
    $error['msg'] = "";
    echo json_encode($error);
    exit();
  }
}
