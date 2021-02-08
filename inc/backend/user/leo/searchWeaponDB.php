<?php
    session_name('hydrid');
    session_start();
    require '../../../connect.php';
    require '../../../config.php';
    require '../../../backend/user/auth/userIsLoggedIn.php';

    // Makes sure the person actually has a character set
    if (!isset($_SESSION['identity_name'])) {
      header('Location: ../../../../' . $url['leo'] . '?v=nosession');
      exit();
    }

    // Page PHP
    $q      = strip_tags($_GET['id']);
    echo "<table style='width:100%'>
    <tr>
    <th><center>Type</center></th>
    <th><center>Serial</center></th>
    <th><center>Status</center></th>
    <th><center>Owner</center></th>
    </tr>";
    $getWpn = "SELECT * FROM weapons WHERE wpn_id='$q'";
    $result = $pdo->prepare($getWpn);
    $result->execute();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td><center>" . $row['wpn_type'] . "</center></td>";
        echo "<td><center>" . $row['wpn_serial'] . "</center></td>";
        echo "<td><center>" . $row['wpn_rpstatus'] . "</center></td>";
        echo "<td><center>" . $row['wpn_ownername'] . "</center></td>";
        echo "</tr>";
    }
    echo "</table>";
