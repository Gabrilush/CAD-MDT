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

$stmt = $pdo->prepare("SELECT * FROM on_duty WHERE name=:name");
$stmt->bindValue(':name', $_SESSION['identity_name']);
$stmt->execute();
$status_row = $stmt->fetch(PDO::FETCH_ASSOC);
echo $status_row['status'];

$_SESSION['duty_id'] = $status_row['id'];

if (empty($status_row)) {
	echo 'Off-Duty';
}

?>
