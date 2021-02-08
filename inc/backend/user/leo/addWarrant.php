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

$newWarrant['name']       	    = !empty($_POST['civilian']) ? trim($_POST['civilian']) : null;
$newWarrant['name']             = strip_tags($_POST['civilian']);
$newWarrant['reason']       	    = !empty($_POST['reason']) ? trim($_POST['reason']) : null;
$newWarrant['reason']             = strip_tags($_POST['reason']);
$error = array();

$sql_getCharName             = "SELECT * FROM characters WHERE character_id = ?";
$stmt_getCharName            = $pdo->prepare($sql_getCharName);
$stmt_getCharName->execute([$newWarrant['name']]);
$realChar = $stmt_getCharName->fetch(PDO::FETCH_ASSOC);

$sql          = "INSERT INTO warrants (issued_on, signed_by, reason, wanted_person, wanted_person_id) VALUES (
  :issued_on,
  :signed_by,
  :reason,
  :wanted_person,
  :wanted_person_id
  )";
$stmt         = $pdo->prepare($sql);
$stmt->bindValue(':issued_on', $us_date . ' ' . $time);
$stmt->bindValue(':signed_by', $_SESSION['identity_name']);
$stmt->bindValue(':reason', $newWarrant['reason']);
$stmt->bindValue(':wanted_person', $realChar['first_name'] . ' ' . $realChar['last_name']);
$stmt->bindValue(':wanted_person_id', $newWarrant['name']);
$result = $stmt->execute();
if ($result) {
	if ($settings['discord_alerts'] === 'true') {
	discordAlert('**New Warrant**
	'.$realChar['first_name'] . ' ' . $realChar['last_name'].' is now WANTED.
	Warrant added by '.$_SESSION['identity_name'].'
	Reason: '.$newWarrant['reason'].'
		- **Hydrid CAD System**');
	}
  $error['msg'] = "";
	echo json_encode($error);
	exit();
}
