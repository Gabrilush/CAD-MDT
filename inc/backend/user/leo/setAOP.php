<?php
session_name('hydrid');
session_start();
require '../../../connect.php';
require '../../../config.php';
require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if (!isset($_SESSION['identity_name'])) {
  header('Location: ../../../../' . $url['leo'] . '?v=nosession');
  exit();
}

// Supervisor Check
if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true') {
  // Page PHP
  $newAOP['aop'] = strip_tags($_POST['newAOP']);
  $error = array();

  $stmt              = $pdo->prepare("UPDATE `servers` SET `aop`=:newAOP WHERE `id`=:server_id");
  $stmt->bindValue(':newAOP', $newAOP['aop']);
  $stmt->bindValue(':server_id', $_SESSION['server']);
  $result = $stmt->execute();
  if ($result) {
    if ($settings['discord_alerts'] === 'true') {
    discordAlert('**AOP Updated**
    The Area of Patrol has been updated for Server **'. $_SESSION['server'] .'**
    New AOP: **'. $newAOP['aop'] . '**
      - **Hydrid CAD System**');
    }
    $error['msg'] = "";
    echo json_encode($error);
    exit();
  } else {
    $error['msg'] = "Database Error";
    echo json_encode($error);
    exit();
  }
} else {
  $error['msg'] = "Permission Error";
  echo json_encode($error);
  exit();
}
