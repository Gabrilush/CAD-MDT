<?php
// General Configuration
$GLOBAL['language'] = "es-es"; // Set Language
$debug = true; // Toggle Debug
// Version Number -- Do Not Change
$version = "v2.0.0 (PRE-BETA)";
$assets_ver = "2008";

// Set Language
// require('languages/' . $GLOBAL['language'] . '.php');
// Get Global Functions
require_once "functions.php";

// Get Site Config
$sql = "SELECT * FROM settings";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$settingsRow = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($settingsRow)) {
    throwError('Settings Table Missing/Broken', true);
    die("Settings Table Missing/Broken");
}

// Define variables
$settings['name'] = $settingsRow['site_name'];
$settings['account_validation'] = $settingsRow['account_validation'];
$settings['identity_validation'] = $settingsRow['identity_validation'];
$settings['steam_required'] = $settingsRow['steam_required'];
$settings['timezone'] = $settingsRow['timezone'];
$settings['civ_side_warrants'] = $settingsRow['civ_side_warrants'];
$settings['add_warrant'] = $settingsRow['add_warrant'];
$settings['discord_alerts'] = $settingsRow['discord_alerts'];
$discord_webhook = $settingsRow['discord_webhook'];

//group settings
$settings['unverifiedGroup'] = $settingsRow['group_unverifiedGroup'];
$settings['verifiedGroup'] = $settingsRow['group_verifiedGroup'];
$settings['banGroup'] = $settingsRow['group_banGroup'];

$sql2 = "SELECT * FROM servers";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$serversRow = $stmt2->fetch(PDO::FETCH_ASSOC);

if (empty($serversRow)) {
    throwError('Servers Table Missing/Broken', true);
    die("Servers Table Missing/Broken");
}


$_SESSION['server'] = '1';

// Define URLS
require_once "urls.php";

$ip = $_SERVER['REMOTE_ADDR'];
date_default_timezone_set($settings['timezone']);
$date = date('Y-m-d');
$us_date = date_format(date_create_from_format('Y-m-d', $date) , 'm/d/Y');
$time = date('h:i:s A', time());
$datetime = $us_date . ' ' . $time;

?>
