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
$sql = "SELECT * FROM vehicles WHERE vehicle_owner=:character_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':character_id', $_SESSION['character_id']);
$stmt->execute();
$vehicleDBcall = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($vehicleDBcall)) {
    echo '
      You have no Vehicles.
      ';
}
else {
    echo '
      <table class="table table-borderless">
          <thead>
            <tr>
                <th>Plate</th>
                <th>Color</th>
                <th>Model</th>
                <th>Insurance</th>
                <th>Registration</th>
                <th>Actions</th>
            </tr>
          </thead>
            <tbody>
              ';
    foreach ($vehicleDBcall as $vehicle) {
        echo '
        <tr>
            <td>' . $vehicle['vehicle_plate'] . '</td>
            <td>' . $vehicle['vehicle_color'] . '</td>
            <td>' . $vehicle['vehicle_model'] . '</td>
            <td>' . $vehicle['vehicle_is'] . '</td>
            <td>' . $vehicle['vehicle_rs'] . '</td>
            <td><input type="button" class="btn btn-danger btn-sm" name="deleteVehicle" value="Delete" id=' . $vehicle['vehicle_id'] . ' onclick="deleteVehicle(this)"></td>
        </tr>
        ';
    }

    echo '
            </tbody>
      </table>';
}
