<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if (!isset($_SESSION['character_full_name'])) {
    header('Location: ../../../../' . $url['civilian'] . '?v=nosession');
    exit();
}

// Page PHP
$sql = "SELECT * FROM arrest_reports WHERE suspect_id=:character_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':character_id', $_SESSION['character_id']);
$stmt->execute();
$arrestsDBcall = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($arrestsDBcall)) {
    echo '
      You have no Arrests on file.
      ';
}
else {
    echo '
      <table class="table table-borderless">
          <thead>
            <tr>
                <th>Summary</th>
                <th>Officer</th>
                <th>Timestamp</th>
            </tr>
          </thead>
            <tbody>
              ';
    foreach ($arrestsDBcall as $arrest) {
        echo '
                      <tr>
                          <td>' . $arrest['summary'] . '</td>
                          <td>' . $arrest['arresting_officer'] . '</td>
                          <td>' . $arrest['timestamp'] . '</td>
                      </tr>
                      ';
    }

    echo '
            </tbody>
      </table>';
}
