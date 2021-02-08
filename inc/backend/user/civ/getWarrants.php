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
$sql = "SELECT * FROM warrants WHERE wanted_person_id=:character_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':character_id', $_SESSION['character_id']);
$stmt->execute();
$warrantDBcall = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($warrantDBcall)) {
    echo '
      You have no Warrants.
      ';
}
else {
    echo '
      <table class="table table-borderless">
          <thead>
            <tr>
                <th>Reason</th>
                <th>Signed By</th>
                <th>Issued On</th>
            </tr>
          </thead>
            <tbody>
              ';
    foreach ($warrantDBcall as $displayWarrants) {
        echo '
        <tr>
            <td>' . $displayWarrants['reason'] . '</td>
            <td>' . $displayWarrants['signed_by'] . '</td>
            <td>' . $displayWarrants['issued_on'] . '</td>
        </tr>
        ';
    }

    echo '
            </tbody>
      </table>';
}
