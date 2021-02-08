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

$dispatchCheck = array();


// Page PHP

$stmt = $pdo->prepare("SELECT * FROM on_duty WHERE department = ? AND status = ?");
$stmt->execute(['Dispatch', 'On-Duty']);
$getDispatchers = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($getDispatchers)) {
	echo '<div class="alert alert-info" role="alert"><strong>Notice:</strong> No Dispatchers are currently online.</div>';
}

?>
