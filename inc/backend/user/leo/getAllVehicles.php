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

echo '<option disabled="disabled" selected="true"> Search by Plate , Model or VIN </option>';
$sql             = "SELECT * FROM vehicles";
$stmt            = $pdo->prepare($sql);
$stmt->execute();
$vehRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($vehRow as $vehicles) {
	echo '
                <option value="' . $vehicles['vehicle_id'] . '">' . $vehicles['vehicle_plate'] . ' ~ ' . $vehicles['vehicle_model'] . ' ~ ' . $vehicles['vehicle_vin'] . '</option>
            ';
}
