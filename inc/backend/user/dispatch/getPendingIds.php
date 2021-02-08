<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if (!isset($_SESSION['on_duty'])) {
	header('Location: ../../../../' . $url['leo'] . '?v=nosession');
	exit();
}

// Supervisor Check
if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true') {
  // Page PHP
  $sql             = "SELECT * FROM identities WHERE status='Approval Needed' AND department='Dispatch'";
  $stmt            = $pdo->prepare($sql);
  $stmt->execute();
  $pendingIdsRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($pendingIdsRow)) {
    echo '<div class="alert alert-danger" role="alert">
      <strong>No Pending Identites.</strong>
    </div>';
  } else {
    foreach($pendingIdsRow as $pending_id) {
        echo "<table style='width:100%'>
        <tr>
          <th>Name</th>
          <th>Owner</th>
          <th>Actions</th>
        </tr>";
        echo "<tr>";
        echo "<td>" . $pending_id['name'] . "</td>";
        echo "<td>" . $pending_id['user_name'] . "</td>";
        echo '<td><input type="button" class="btn btn-sm btn-success" name="approve" value="Approve" id='.$pending_id['identity_id'].' onclick="approveID(this)"> <input type="button" class="btn btn-sm btn-danger" name="reject" value="Reject" id='.$pending_id['identity_id'].' onclick="rejectID(this)"></td>';
        echo "</tr>";
        echo "</table>";
    }
  }
} else {
  $error['msg'] = "Permission Error";
  echo json_encode($error);
  exit();
}
