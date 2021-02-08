<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch" || $_SESSION['on_duty'] === "LEO") {
    echo '
  <table class="table table-borderless">
  <tr>
    <th><center>Unit</center></th>
    <th><center>Status</center></th>';
    if ($_SESSION['on_duty'] === "Dispatch") {
        echo '<th><center>Actions</center></th>';
    }
    echo '
  </tr>';
    $getAttachedUnits = 'SELECT * FROM assigned_callunits where call_id = :call_id';
    $stmt = $pdo->prepare($getAttachedUnits);
    $stmt->bindValue(':call_id', $_SESSION['viewingCallID']);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sql2 = "SELECT * FROM on_duty WHERE id = ?";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([$row['unit_id']]);
        $unitInfo = $stmt2->fetch(PDO::FETCH_ASSOC);

        echo "<tr>";
        echo '<td><center>' . $unitInfo['name'] . '</center></td>';
        echo '<td><center>' . $unitInfo['status'] . '</center></td>';
        if ($_SESSION['on_duty'] === "Dispatch") {
            echo '<td><center><input type="button" class="btn btn-danger btn-sm" name="unassignUnit" value="Unassign" id=' . $unitInfo['id'] . ' onclick="unassignUnit(this.id)"></center></td>';
        }
        echo "</tr>";
    }

    echo '</table>';
}
