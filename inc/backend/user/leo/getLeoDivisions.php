<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Page PHP

echo '<option selected="true" disabled="disabled">Select Division</option>';
$sql             = "SELECT * FROM leo_division";
$stmt            = $pdo->prepare($sql);
$stmt->execute();
$divRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($divRow as $leoDivision) {
	echo '
                <option value="' . $leoDivision['name'] . '">' . $leoDivision['name'] . '</option>
            ';
}
