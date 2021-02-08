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

    // Page PHP
    $status = strip_tags($_GET['status']);
    if ($status === "10-42") {
      $stmt              = $pdo->prepare("DELETE FROM on_duty WHERE `name`=:name");
      $stmt->bindValue(':name', $_SESSION['identity_name']);
      $result = $stmt->execute();
    } elseif ($status === "10-41") {
      $sql4  = "SELECT COUNT(name) AS num FROM on_duty WHERE name = :name";
      $stmt4 = $pdo->prepare($sql4);
      $stmt4->bindValue(':name', $_SESSION['identity_name']);
      $stmt4->execute();
      $row = $stmt4->fetch(PDO::FETCH_ASSOC);
      if ($row['num'] > 0) {
        $stmt3              = $pdo->prepare("UPDATE `on_duty` SET `department`=:department, `division`=:division WHERE `name`=:name");
        $stmt3->bindValue(':department', $_SESSION['identity_department']);
        $stmt3->bindValue(':division', $_SESSION['identity_division']);
        $stmt3->bindValue(':name', $_SESSION['identity_name']);
        $result = $stmt3->execute();
        logAction('Started Shift (LEO) - '.$datetime.'', $_SESSION['identity_name']);
    } else {
        $sql5          = "INSERT INTO on_duty (name, department, division, status) VALUES (:name, :department, :division, '10-41')";
        $stmt5         = $pdo->prepare($sql5);
        $stmt5->bindValue(':name', $_SESSION['identity_name']);
        $stmt5->bindValue(':department', $_SESSION['identity_department']);
        $stmt5->bindValue(':division', $_SESSION['identity_division']);
        $result = $stmt5->execute();
        logAction('Started Shift (LEO) - '.$datetime.'', $_SESSION['identity_name']);
    }
    } else {
      $stmt2              = $pdo->prepare("UPDATE `on_duty` SET `status`=:status WHERE `name`=:name");
      $stmt2->bindValue(':status', $status);
      $stmt2->bindValue(':name', $_SESSION['identity_name']);
      $result = $stmt2->execute();
      logAction('Started Shift (LEO) - '.$datetime.'', $_SESSION['identity_name']);
    }
