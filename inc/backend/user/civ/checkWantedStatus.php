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
$sql = "SELECT * FROM warrants WHERE wanted_person_id = :character_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':character_id', $_SESSION['character_id']);
$stmt->execute();
$checkWantedDBcall = $stmt->fetch(PDO::FETCH_ASSOC);

if (!empty($checkWantedDBcall)) {
    echo '<div class="alert alert-danger" role="alert">You are currently WANTED</div>';
}
