<?php
// Check if the user is actually logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ' . $url['login'] . '?error=access');
    exit();
}
else {
    // The user is logged in, so we will grab the data required
    // Get User Data
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userRow === false) {
        header('Location: logout.php');
        exit();
    }

    // Define variables
    $user['username'] = $userRow['username'];
    $user['email'] = $userRow['email'];
    $user['usergroup'] = $userRow['usergroup'];
    $user['ip'] = $userRow['join_ip'];
    $user['join_date'] = $userRow['join_date'];
    $user['avatar'] = $userRow['avatar'];
    $user['failed_logins'] = $userRow['failed_logins'];
    $user['last_ip'] = $userRow['last_ip'];
    $user['root'] = $userRow['root_user'];

    if ($settings['steam_required'] === "true") {
        $user['steam_id'] = $userRow['steam_id'];
    }

    require_once 'groupPerms.php';

    if (!strpos($_SERVER['REQUEST_URI'], "steam-required") !== false) {
        if ($settings['steam_required'] === "true") {
            if (empty($user['steam_id'])) {
                header('Location: ' . $url['steam-required'] . '');
                exit();
            }
        }
    }
}

?>
