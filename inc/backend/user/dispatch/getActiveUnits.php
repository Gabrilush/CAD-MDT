<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch") {
    // First we will check if any units are actually online
    $countUnits = $pdo->query('select count(*) from on_duty WHERE status <> "Off-Duty" AND department <> "Dispatch"')
        ->fetchColumn();
    if ($countUnits === 0) {
        echo 'No Active Units';
    }
    else {
        echo '
    <table id="datatable" class="table table-borderless">
    <tr>
      <th><center>Unit</center></th>
      <th><center>Department</center></th>
      <th><center>Division</center></th>
      <th><center>Status</center></th>
    </tr>
    ';
        $getActiveUnits = 'SELECT * FROM on_duty where status <> "Off-Duty" AND department <> "Dispatch"';
        $result = $pdo->prepare($getActiveUnits);
        $result->execute();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td><center>" . $row['name'] . "</center></td>";
            echo "<td><center>" . $row['department'] . "</center></td>";
            echo "<td><center>" . $row['division'] . "</center></td>";
            echo "<td><center><select style='width:150px;' name='updateUnitStatus' id='" . $row['id'] . "' class='select-units custom-select' onChange='updateUnitStatus(this)'>

        <option selected='true' disabled='disabled'>" . $row['status'] . "</option>";
            $sql_get10codedispatch = "SELECT * FROM 10_codes";
            $stmt_get10codedispatch = $pdo->prepare($sql_get10codedispatch);
            $stmt_get10codedispatch->execute();
            $dbq10codes = $stmt_get10codedispatch->fetchAll(PDO::FETCH_ASSOC);
            foreach ($dbq10codes as $codes) {
                echo '<option value="' . $codes['code'] . '">' . $codes['code'] . '</option>';
            }

            "</select></center></td>";
            echo "</tr>";
        }
        echo '</table>';

    }
}
