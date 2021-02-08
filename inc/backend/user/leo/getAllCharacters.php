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

echo '<option disabled="disabled" selected="true"> Search by Name or Date of Birth</option>';
$sql             = "SELECT * FROM characters";
$stmt            = $pdo->prepare($sql);
$stmt->execute();
$getCharsDBcall = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($getCharsDBcall as $characters) {
	echo '
                <option value="' . $characters['character_id'] . '">' . $characters['first_name'] . ' ' . $characters['last_name'] . ' - ' . $characters['date_of_birth'] . '</option>
            ';
}
