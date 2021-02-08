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
$sql = "SELECT * FROM servers WHERE id=:server_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':server_id', $_SESSION['server']);
$stmt->execute();
$aop_row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "- AOP: ".$aop_row['aop'];

$_SESSION['current_aop'] = $aop_row['aop'];
?>
