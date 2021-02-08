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
$sql = "SELECT * FROM tickets WHERE suspect_id=:character_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':character_id', $_SESSION['character_id']);
$stmt->execute();
$ticketDBcall = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($ticketDBcall)) {
    echo '
      You have no Tickets on file.
      ';
}
else {
    echo '
      <table class="table table-borderless">
          <thead>
            <tr>
                <th>Reason</th>
                <th>Fine Amount</th>
                <th>Timestamp</th>
                <th>Officer</th>
            </tr>
          </thead>
            <tbody>
              ';
    foreach ($ticketDBcall as $ticket) {
        echo '
        <tr>
            <td>' . $ticket['reasons'] . '</td>
            <td>' . $ticket['amount'] . '</td>
            <td>' . $ticket['ticket_timestamp'] . '</td>
            <td>' . $ticket['officer'] . '</td>
        </tr>
        ';
    }

    echo '
            </tbody>
      </table>';
}
