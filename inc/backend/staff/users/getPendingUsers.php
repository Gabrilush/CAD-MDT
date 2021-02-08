<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

if (staff_access === 'true' && staff_approveUsers === 'true') {
    $stmt = $pdo->query("SELECT * FROM users");

    $sql = "SELECT * FROM users WHERE usergroup='Unverified'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pendingUsersRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($pendingUsersRow)) {
        echo '<div class="alert alert-danger" role="alert">
      <strong>No Pending Users Found.</strong>
    </div>';
    }
    else {
        foreach ($pendingUsersRow as $pending_user) {
            echo "<table class='table table-bordered' style='width:100%'>
        <tr>
          <th>User ID</th>
          <th>Username</th>
          <th>Join Date</th>
          <th>Join IP</th>
          <th>Actions</th>
        </tr>";
            echo "<tr>";
            echo "<td>" . $pending_user['user_id'] . "</td>";
            echo "<td>" . $pending_user['username'] . "</td>";
            echo "<td>" . $pending_user['join_date'] . "</td>";
            echo "<td>" . $pending_user['join_ip'] . "</td>";
            echo '<td><input type="button" class="btn btn-sm btn-success" name="approve" value="Approve" id=' . $pending_user['user_id'] . ' onclick="approveUser(this)"> <input type="button" class="btn btn-sm btn-danger" name="reject" value="Reject" id=' . $pending_user['user_id'] . ' onclick="rejectUser(this)"></td>';
            echo "</tr>";
            echo "</table>";
        }
    }
}
else {
    $error['msg'] = "No Permissions";
    echo json_encode($error);
    exit();
}
