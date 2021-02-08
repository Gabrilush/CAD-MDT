<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch" || $_SESSION['on_duty'] === "LEO") {
    $stmt = $pdo->prepare("SELECT * FROM servers WHERE id=:server_id");
    $stmt->bindValue(':server_id', $_SESSION['server']);
    $stmt->execute();
    $pb_row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pb_row['priority'] === 1) {
        if ($settings['discord_alerts'] === 'true') {
            discordAlert('**Signal 100 Is No Longer In Effect**
            Dispatcher (' . $_SESSION['identity_name'] . ') Has Deactivated Signal 100
            - **Hydrid CAD System**');
        }
        $stmt4 = $pdo->prepare("UPDATE `servers` SET `priority`='0' WHERE `id`=:server_id");
        $stmt4->bindValue(':server_id', $_SESSION['server']);
        $result = $stmt4->execute();
    }
    else {
        if ($settings['discord_alerts'] === 'true') {
            discordAlert('**Signal 100 IS NOW IN EFFECT**
            Dispatcher (' . $_SESSION['identity_name'] . ') Has Activated Signal 100 - Check CAD For Further Details
            - **Hydrid CAD System**');
        }
        $stmt3 = $pdo->prepare("UPDATE `servers` SET `priority`='1' WHERE `id`=:server_id");
        $stmt3->bindValue(':server_id', $_SESSION['server']);
        $result = $stmt3->execute();
    }
}
else {
    header('Location: ../../../../' . $url['dispatch'] . '?v=nosession');
    exit();
}
