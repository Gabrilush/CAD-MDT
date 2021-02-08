<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch") {
    // Page PHP
    $call_description = !empty($_POST['call_description']) ? trim($_POST['call_description']) : null;
    $call_location = !empty($_POST['call_location']) ? trim($_POST['call_location']) : null;

    $call_description = strip_tags($_POST['call_description']);
    $call_location = strip_tags($_POST['call_location']);
    $call_postal = strip_tags($_POST['call_postal']);

    $error = array();

    if (isset($_POST['allCall'])) {
        $sql = "INSERT INTO 911calls (call_description, call_location, call_postal, call_timestamp, call_isPriority, call_status) VALUES (
			:call_description,
			:call_location,
			:call_postal,
			:call_timestamp,
			:call_isPriority,
			:call_status
			)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':call_description', $call_description);
        $stmt->bindValue(':call_location', $call_location);
        $stmt->bindValue(':call_postal', $call_postal);
        $stmt->bindValue(':call_timestamp', $us_date . ' ' . $time);
        $stmt->bindValue(':call_isPriority', 'true');
        $stmt->bindValue(':call_status', 'PRIORITY');
        $result = $stmt->execute();
        if ($result) {
            if ($settings['discord_alerts'] === 'true') {
                discordAlert('**NEW 911 CALL**
			**Description:** ' . $call_description . '
			**Location:** ' . $call_location . ' / ' . $call_crossstreat . ' / ' . $call_postal . '
			**Called On:** ' . $datetime . '
				- **Hydrid CAD System**');
            }
            $error['msg'] = "allCall";
            echo json_encode($error);
            exit();
        }
    }
    else {
        $sql = "INSERT INTO 911calls (caller_id, call_description, call_location, call_postal, call_timestamp) VALUES (
			:caller_id,
			:call_description,
			:call_location,
			:call_postal,
			:call_timestamp
			)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':caller_id', 'Dispatch');
        $stmt->bindValue(':call_description', $call_description);
        $stmt->bindValue(':call_location', $call_location);
        $stmt->bindValue(':call_postal', $call_postal);
        $stmt->bindValue(':call_timestamp', $us_date . ' ' . $time);
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
}
