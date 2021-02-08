<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch") {
    if (isset($_GET['opt']) && strip_tags($_GET['opt']) === '1') {
        echo '<option disabled="disabled" selected="true"> Select Unit To Add </option>';
        $sql = 'SELECT * FROM on_duty where status <> "Off-Duty" AND department <> "Dispatch"';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $getUnits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($getUnits as $unit) {
            $stmt2 = $pdo->prepare("SELECT * FROM assigned_callunits WHERE unit_id=?");
            $stmt2->execute([$unit['id']]);
            $checkUnit = $stmt2->fetch();

            if ($checkUnit === false) {
                echo '<option value="' . $unit['id'] . '">' . $unit['name'] . '</option>';
            }
        }
    }
    elseif (isset($_GET['opt']) && strip_tags($_GET['opt']) === '2') {
        $sql = 'SELECT * FROM on_duty where status <> "Off-Duty" AND department <> "Dispatch"';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $getUnits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($getUnits as $unit) {
            $stmt2 = $pdo->prepare("SELECT * FROM assigned_callunits WHERE unit_id=?");
            $stmt2->execute([$unit['id']]);
            $checkUnit = $stmt2->fetch();

            if ($checkUnit === false) {
                echo '<option value="' . $unit['id'] . '">' . $unit['name'] . '</option>';
            }
        }
    }
}
