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

$newVeh['plate'] = !empty($_POST['plate']) ? trim($_POST['plate']) : null;
$newVeh['color'] = !empty($_POST['color']) ? trim($_POST['color']) : null;
$newVeh['model'] = !empty($_POST['model']) ? trim($_POST['model']) : null;
$newVeh['insurance_status'] = !empty($_POST['insurance_status']) ? trim($_POST['insurance_status']) : null;
$newVeh['registration_status'] = !empty($_POST['registration_status']) ? trim($_POST['registration_status']) : null;

$error = array();

// Check if plate is taken
$sql = "SELECT COUNT(vehicle_plate) AS num FROM vehicles WHERE vehicle_plate = :vehicle_plate";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':vehicle_plate', $newVeh['plate']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['num'] > 0) {
    $error['msg'] = "Please try a different License Plate.";
    echo json_encode($error);
    exit();
}
function generateRandomString($length = 17) {
    return substr(str_shuffle(str_repeat($x = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))) , 1, $length);
}

$vin = generateRandomString();

// Check if VIN already exists, and if it does, re-run the generate string
$sql3 = "SELECT COUNT(vehicle_vin) AS num FROM vehicles WHERE vehicle_vin = :vehicle_vin";
$stmt3 = $pdo->prepare($sql3);
$stmt3->bindValue(':vehicle_vin', $vin);
$stmt3->execute();
$row = $stmt3->fetch(PDO::FETCH_ASSOC);
if ($row['num'] > 0) {
    $vin = generateRandomString();
}

$sql2 = "INSERT INTO vehicles (vehicle_plate, vehicle_color, vehicle_model, vehicle_is, vehicle_rs, vehicle_vin, vehicle_owner, vehicle_ownername) VALUES (
    :vehicle_plate,
    :vehicle_color,
    :vehicle_model,
    :vehicle_is,
    :vehicle_rs,
    :vehicle_vin,
    :vehicle_owner,
    :vehicle_ownername
    )";
$stmt2 = $pdo->prepare($sql2);
$stmt2->bindValue(':vehicle_plate', strtoupper($newVeh['plate']));
$stmt2->bindValue(':vehicle_color', $newVeh['color']);
$stmt2->bindValue(':vehicle_model', $newVeh['model']);
$stmt2->bindValue(':vehicle_is', $newVeh['insurance_status']);
$stmt2->bindValue(':vehicle_rs', $newVeh['registration_status']);
$stmt2->bindValue(':vehicle_vin', $vin);
$stmt2->bindValue(':vehicle_owner', $_SESSION['character_id']);
$stmt2->bindValue(':vehicle_ownername', $_SESSION['character_full_name']);
$result = $stmt2->execute();
if ($result) {
    $error['msg'] = "";
    echo json_encode($error);
    exit();
}
