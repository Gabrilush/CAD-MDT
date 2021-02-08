<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

$newIdentity['name'] = strip_tags($_POST['name']);
$error = array();

// Check if name is taken
$sql = "SELECT COUNT(name) AS num FROM identities WHERE name = :name";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $newIdentity['name']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['num'] > 0) {
    $error['msg'] = "Please use a different name.";
    echo json_encode($error);
    exit();
}

if ($settings['identity_validation'] === "no") {
    $sql2 = "INSERT INTO identities (name, department, created_on, user, user_name) VALUES (
        :name,
        'Dispatch',
        :created_on,
        :user,
        :user_name
        )";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindValue(':name', $newIdentity['name']);
    $stmt2->bindValue(':created_on', $us_date . ' ' . $time);
    $stmt2->bindValue(':user', $user_id);
    $stmt2->bindValue(':user_name', $user['username']);
    $result = $stmt2->execute();
    if ($result) {
        $error['msg'] = "";
        echo json_encode($error);
        exit();
    }
}
elseif ($settings['identity_validation'] === "yes") {
    $sql2 = "INSERT INTO identities (name, department, created_on, user, user_name, status) VALUES (
        :name,
        'Dispatch',
        :created_on,
        :user,
        :user_name,
        'Approval Needed'
        )";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindValue(':name', $newIdentity['name']);
    $stmt2->bindValue(':created_on', $us_date . ' ' . $time);
    $stmt2->bindValue(':user', $user_id);
    $stmt2->bindValue(':user_name', $user['username']);
    $result = $stmt2->execute();
    if ($result) {
        $error['msg'] = "";
        echo json_encode($error);
        exit();
    }
}
else {
    $error['msg'] = "Fatal System Error - Contact Hydrid Support";
    echo json_encode($error);
    exit();
}
