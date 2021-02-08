<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Page PHP

echo '<option disabled="disabled" selected="true"> Select Identity </option>';
$sql             = "SELECT * FROM identities WHERE user=:user_id AND department='Law Enforcement'";
$stmt            = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id);
$stmt->execute();
$query = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($query as $identity) {
	if ($identity['status'] === "Approval Needed") {
        echo '
        <option disabled="disabled">' . $identity['name'] . ' - ' . $identity['division'] . ' (Pending Approval)</option>
        ';
    } else {
        echo '
        <option value="' . $url['leo'] . '?v=setsession&id=' . $identity['identity_id'] . '">' . $identity['name'] . ' - ' . $identity['division'] . '</option>
        ';
    }
    
}
