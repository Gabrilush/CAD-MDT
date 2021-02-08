<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

if (!isset($_SESSION['on_duty'])) {
	header('Location: ../../../../' . $url['leo'] . '?v=nosession');
	exit();
}

// Page PHP

echo '<option disabled="disabled" selected="true"> Search by Type or Serial</option>';
$sql             = "SELECT * FROM weapons";
$stmt            = $pdo->prepare($sql);
$stmt->execute();
$getFirearmsDBcall = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($getFirearmsDBcall as $firearm) {
	echo '
                <option value="' . $firearm['wpn_id'] . '">' . $firearm['wpn_type'] . ' ~ ' . $firearm['wpn_serial'] . '</option>
            ';
}
