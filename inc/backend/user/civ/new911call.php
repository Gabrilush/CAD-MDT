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

// Page PHP
$call_description = !empty($_POST['call_description']) ? trim($_POST['call_description']) : null;
$call_location = !empty($_POST['call_location']) ? trim($_POST['call_location']) : null;

$call_description = strip_tags($_POST['call_description']);
$call_location = strip_tags($_POST['call_location']);
$call_postal = strip_tags($_POST['call_postal']);

$error = array();
$sql = "INSERT INTO 911calls (caller_id, call_description, call_location, call_postal, call_timestamp) VALUES (
	:caller_id,
	:call_description,
	:call_location,
	:call_postal,
	:call_timestamp
	)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':caller_id', $_SESSION['character_id']);
$stmt->bindValue(':call_description', $call_description);
$stmt->bindValue(':call_location', $call_location);
$stmt->bindValue(':call_postal', $call_postal);
$stmt->bindValue(':call_timestamp', $us_date . ' ' . $time);
$result = $stmt->execute();
if ($result) {
    if ($settings['discord_alerts'] === 'true') {
        discordAlert('**NEW 911 CALL**
	**Description:** ' . $call_description . '
	**Location:** ' . $call_location . ' / ' . $call_crossstreat . ' / ' . $call_postal . '
	**Called On:** ' . $datetime . '
	  - **Hydrid CAD System**');
    }
    $error['msg'] = "";
    echo json_encode($error);
    exit();
}
