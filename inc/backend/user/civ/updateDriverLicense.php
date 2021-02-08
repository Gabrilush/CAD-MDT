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
$license = strip_tags($_GET['license']);
$_SESSION['character_license_driver'] = $license;

$stmt = $pdo->prepare("UPDATE `characters` SET `license_driver`=:license WHERE `character_id`=:character_id");
$stmt->bindValue(':license', $license);
$stmt->bindValue(':character_id', $_SESSION['character_id']);
$result = $stmt->execute();
