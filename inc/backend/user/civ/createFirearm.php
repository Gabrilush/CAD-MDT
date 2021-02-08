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
$newWpn['weapon_type'] = !empty($_POST['weapon']) ? trim($_POST['weapon']) : null;
$newWpn['rpstatus'] = !empty($_POST['rpstatus']) ? trim($_POST['rpstatus']) : null;

$newWpn['weapon_type'] = strip_tags($_POST['weapon']);
$newWpn['rpstatus'] = strip_tags($_POST['rpstatus']);
$error = array();

function generate3LTRSerial($length = 3) {
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))) , 1, $length);
}

function generate7NUMSerial($length2 = 7) {
    return substr(str_shuffle(str_repeat($x1 = '0123456789', ceil($length2 / strlen($x1)))) , 1, $length2);
}

$serial_1 = generate3LTRSerial();
$serial_2 = generate7NUMSerial();
$serial = $serial_1 . '' . $serial_2;

$sql = "INSERT INTO weapons (wpn_type, wpn_serial, wpn_owner, wpn_ownername, wpn_rpstatus) VALUES (:wpn_type, :wpn_serial, :wpn_owner, :wpn_ownername, :wpn_rpstatus)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':wpn_type', $newWpn['weapon_type']);
$stmt->bindValue(':wpn_serial', $serial);
$stmt->bindValue(':wpn_owner', $_SESSION['character_id']);
$stmt->bindValue(':wpn_ownername', $_SESSION['character_full_name']);
$stmt->bindValue(':wpn_rpstatus', $newWpn['rpstatus']);
$result = $stmt->execute();
if ($result) {
    $error['msg'] = "";
    echo json_encode($error);
    exit();
}
