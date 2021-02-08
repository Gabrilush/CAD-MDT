<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if ($_SESSION['on_duty'] === "Dispatch" || $_SESSION['on_duty'] === "LEO") {
    // First we will check if any units are actually online
    $countActiveBolos = $pdo->query('select count(*) from bolos')->fetchColumn();
    if ($countActiveBolos === 0) {
      echo 'No Active BOLOs';
    } else {
      echo '
      <table class="table table-borderless">
      <tr>
        <th>Description</th>
        <th>Created On</th>
      </tr>
      ';
      $getActiveBolos = 'SELECT * FROM bolos';
      $result         = $pdo->prepare($getActiveBolos);
      $result->execute();
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          echo "<td width='75%'>" . $row['description'] . "</td>";
          echo "<td>" . $row['created_on'] . "</td>";
          echo "</tr>";
    }
    echo '</table>';

  }
}

?>
