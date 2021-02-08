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

if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true') {
	// Page PHP

	$id = strip_tags($_GET['id']);
	$stmt              = $pdo->prepare("DELETE FROM identities WHERE `identity_id`=:id");
	$stmt->bindValue(':id', $id);
	$result = $stmt->execute();

	if ($settings['discord_alerts'] === 'true') {
	discordAlert('**ID Rejected**
		  ID #'. $id .' has been Rejected for Law Enforcement
		  - **Hydrid CAD System**');
	}
} else {
	header('Location: ../../../../' . $url['leo'] . '?v=nosession');
	exit();
}
