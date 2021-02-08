<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

$newChar['first_name'] = !empty($_POST['firstname']) ? trim($_POST['firstname']) : null;
$newChar['last_name'] = !empty($_POST['lastname']) ? trim($_POST['lastname']) : null;
$newChar['gender'] = !empty($_POST['gender']) ? trim($_POST['gender']) : null;
$newChar['race'] = !empty($_POST['race']) ? trim($_POST['race']) : null;
$newChar['address'] = !empty($_POST['address']) ? trim($_POST['address']) : null;
$newChar['date_of_birth'] = !empty($_POST['date_of_birth']) ? trim($_POST['date_of_birth']) : null;
$newChar['height'] = !empty($_POST['height']) ? trim($_POST['height']) : null;
$newChar['weight'] = !empty($_POST['weight']) ? trim($_POST['weight']) : null;
$newChar['eye_color'] = !empty($_POST['eye_color']) ? trim($_POST['eye_color']) : null;
$newChar['hair_color'] = !empty($_POST['hair_color']) ? trim($_POST['hair_color']) : null;

$newChar['first_name'] = strip_tags($_POST['firstname']);
$newChar['last_name'] = strip_tags($_POST['lastname']);
$newChar['gender'] = strip_tags($_POST['gender']);
$newChar['race'] = strip_tags($_POST['race']);
$newChar['address'] = strip_tags($_POST['address']);
$newChar['date_of_birth'] = strip_tags($_POST['date_of_birth']);
$newChar['height'] = strip_tags($_POST['height']);
$newChar['weight'] = strip_tags($_POST['weight']);
$newChar['eye_color'] = strip_tags($_POST['eye_color']);
$newChar['hair_color'] = strip_tags($_POST['hair_color']);

$error = array();

// Length Checks
if (strlen($newChar['first_name']) < 2) {
    $error['msg'] = "Your first name must be longer than 2 characters.";
    echo json_encode($error);
    exit();
}
elseif (strlen($newChar['last_name']) > 120) {
    $error['msg'] = "Your last name must be longer than 2 characters.";
    echo json_encode($error);
    exit();
}

// Check if name is taken
$sql = "SELECT COUNT(first_name) AS num FROM characters WHERE first_name = :first_name";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':first_name', $newChar['first_name']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['num'] > 0) {
    $sql3 = "SELECT COUNT(last_name) AS num FROM characters WHERE last_name = :last_name";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->bindValue(':last_name', $newChar['last_name']);
    $stmt3->execute();
    $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
    if ($row3['num'] > 0) {
        $error['msg'] = "Please use a different name.";
        echo json_encode($error);
        exit();
    }
}

$sql2 = "INSERT INTO characters (first_name, last_name, date_of_birth, address, height, eye_color, hair_color, sex, race, weight, owner_id, owner_name) VALUES (
	:first_name,
	:last_name,
	:date_of_birth,
	:address,
	:height,
	:eye_color,
	:hair_color,
	:gender,
	:race,
	:weight,
	:owner_id,
	:owner_name
	)";
$stmt2 = $pdo->prepare($sql2);
$stmt2->bindValue(':first_name', $newChar['first_name']);
$stmt2->bindValue(':last_name', $newChar['last_name']);
$stmt2->bindValue(':date_of_birth', $newChar['date_of_birth']);
$stmt2->bindValue(':address', $newChar['address']);
$stmt2->bindValue(':height', $newChar['height']);
$stmt2->bindValue(':eye_color', $newChar['eye_color']);
$stmt2->bindValue(':hair_color', $newChar['hair_color']);
$stmt2->bindValue(':gender', $newChar['gender']);
$stmt2->bindValue(':race', $newChar['race']);
$stmt2->bindValue(':weight', $newChar['weight']);
$stmt2->bindValue(':owner_id', $user_id);
$stmt2->bindValue(':owner_name', $user['username']);
$result = $stmt2->execute();
if ($result) {
    $error['msg'] = "";
    echo json_encode($error);
    exit();
}
