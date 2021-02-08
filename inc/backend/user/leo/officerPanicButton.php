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

$sql          = "INSERT INTO 911calls (call_description, call_location, call_postal, call_timestamp, call_isPriority, call_status) VALUES (
  :call_description,
  :call_location,
  :call_postal,
  :call_timestamp,
  :call_isPriority,
  :call_status
  )";
$stmt         = $pdo->prepare($sql);
$stmt->bindValue(':call_description', 'PANIC BUTTON HAS BEEN PUSHED ' . $_SESSION['identity_name']);
$stmt->bindValue(':call_location', 'N/A');
$stmt->bindValue(':call_postal', '0');
$stmt->bindValue(':call_timestamp', $us_date . ' ' . $time);
$stmt->bindValue(':call_isPriority', 'true');
$stmt->bindValue(':call_status', 'PRIORITY');
$result = $stmt->execute();
if ($result) {
  if ($settings['discord_alerts'] === 'true') {
  discordAlert('**NEW 911 CALL**
  **Description:** '. $call_description .'
  **Location:** '. $call_location .' / '. $call_crossstreat .' / '. $call_postal .'
  **Called On:** '. $datetime .'
    - **Hydrid CAD System**');
  }
}
