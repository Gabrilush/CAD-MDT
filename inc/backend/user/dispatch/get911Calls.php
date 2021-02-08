<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch") {
    // First we will check if any units are actually online
    $countActiveCalls = $pdo->query('select count(*) from 911calls where call_status <> "Archived"')
        ->fetchColumn();
    if ($countActiveCalls === 0) {
        echo 'No Active 911 Calls';
    }
    else {
        echo '
      <table class="table table-borderless">
      <tr>
        <th><center>Location</center></th>
        <th><center>Status</center></th>
        <th><center>Desc</center></th>
        <th><center>View</center></th>
      </tr>
      ';
        $getActiveCalls = 'SELECT * FROM 911calls where call_status <> "Archived"';
        $result = $pdo->prepare($getActiveCalls);
        $result->execute();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row['call_status'] === "PRIORITY") {
                echo "<tr class='table-danger'>";
            }
            else {
                echo "<tr>";
            }
            echo "<td><center>" . $row['call_location'] . " / " . $row['call_postal'] . "</center></td>";
            echo "<td><center>" . $row['call_status'] . "</center></td>";
            echo "<td><center>" . truncate_string($row['call_description'], 100, ' .....') . "</center></td>";
            echo '<td><center><a href="javascript:void(0);" data-href="inc/backend/user/dispatch/getCallInfo.php?id=' . $row['call_id'] . '" class="openCallInfoModal">View</a></center></td>';
            echo "</tr>";
        }
        echo '</table>';

    }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <script type="text/javascript">
    $(document).ready(function() {
      $('.openCallInfoModal').on('click',function(){
          var dataURL = $(this).attr('data-href');
          $('#callModalBody.modal-body').load(dataURL,function(){
              $('#callInfoModal').modal({show:true});
          });
      });
    });
    </script>
  </head>
  <body>
  </body>
</html>
