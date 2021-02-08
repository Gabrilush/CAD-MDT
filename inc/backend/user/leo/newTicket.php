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
$newTicket['suspect_id'] = !empty($_POST['suspect']) ? trim($_POST['suspect']) : null;
$newTicket['location'] = !empty($_POST['location']) ? trim($_POST['location']) : null;
$newTicket['postal'] = !empty($_POST['postal']) ? trim($_POST['postal']) : null;
$newTicket['amount'] = !empty($_POST['amount']) ? trim($_POST['amount']) : null;
$newTicket['reason'] = !empty($_POST['reason']) ? trim($_POST['reason']) : null;

$newTicket['suspect_id'] = strip_tags($_POST['suspect']);
$newTicket['location'] = strip_tags($_POST['location']);
$newTicket['postal'] = strip_tags($_POST['postal']);
$newTicket['amount'] = strip_tags($_POST['amount']);
$newTicket['reason'] = strip_tags($_POST['reason']);


if (empty($newTicket['suspect_id']) || $newTicket['suspect_id'] === "Search") {
  $error['msg'] = "Please select a person to ticket!";
  echo json_encode($error);
  exit();
}

$sql  = "SELECT * FROM characters WHERE character_id = :character_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':character_id', $newTicket['suspect_id']);
$stmt->execute();
$suspectdb = $stmt->fetch(PDO::FETCH_ASSOC);
if ($suspectdb === false) {
  $error['msg'] = "Database Error";
  echo json_encode($error);
  exit();
} else {
  $sql2          = "INSERT INTO tickets (officer, suspect, suspect_id, ticket_timestamp, reasons, location, postal, amount) VALUES (
    :officer, 
    :suspect,
    :suspect_id,
    :created_on,
    :reasons,
    :location,
    :postal,
    :amount
    )";
  $stmt2         = $pdo->prepare($sql2);
  $stmt2->bindValue(':officer', $_SESSION['identity_name']);
  $stmt2->bindValue(':suspect', $suspectdb['first_name'] . ' ' . $suspectdb['last_name']);
  $stmt2->bindValue(':suspect_id', $newTicket['suspect_id']);
  $stmt2->bindValue(':created_on', $us_date . ' ' . $time);
  $stmt2->bindValue(':reasons', $newTicket['reason']);
  $stmt2->bindValue(':location', $newTicket['location']);
  $stmt2->bindValue(':postal', $newTicket['postal']);
  $stmt2->bindValue(':amount', $newTicket['amount']);
  $result = $stmt2->execute();
  if ($result) {
    $error['msg'] = "";
    echo json_encode($error);
    exit();
  }
}
