<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch" || LEO) {
    // Page PHP
    $description = !empty($_POST['description']) ? trim($_POST['description']) : null;

    $description = strip_tags($_POST['description']);

    $error = array();

    $sql = "INSERT INTO bolos (created_on, description, created_by) VALUES (
		:created_on,
		:description,
		:created_by
		)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':created_on', $us_date . ' ' . $time);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':created_by', $_SESSION['identity_id']);
    $result = $stmt->execute();
    if ($result) {
        if ($settings['discord_alerts'] === 'true') {
            discordAlert('**NEW 911 CALL**
		**Description:** ' . $call_description . '
		**Location:** ' . $call_location . ' / ' . $call_crossstreat . ' / ' . $call_postal . '
		**Called On:** ' . $datetime . '
			- **Hydrid CAD System**');
        }
        $error['msg'] = "";
        echo json_encode($error);
        exit();
    }
}
