<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Page PHP
echo '<option disabled="disabled" selected="true"> Select Character </option>';

$sql = "SELECT * FROM characters WHERE owner_id=:user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id);
$stmt->execute();
while ($characters = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $url['civilian'] . '?v=setsession&id=' . $characters['character_id'] . '">' . $characters['first_name'] . ' ' . $characters['last_name'] . ' // ' . $characters['date_of_birth'] . '</option>';
}
