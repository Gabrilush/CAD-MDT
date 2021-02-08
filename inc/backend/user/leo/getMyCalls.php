<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set

if ($_SESSION['on_duty'] === "LEO") {
    // Check if allCall is active
    $stmt_acCheck = $pdo->prepare("SELECT count(*) FROM 911calls where call_isPriority = ?");
    $stmt_acCheck->execute(['true']);
    $countAllCalls = $stmt_acCheck->fetchColumn();
    if ($countAllCalls === 0) {
        // First we will check if any units are actually online
        $stmt = $pdo->prepare("SELECT count(*) FROM assigned_callunits where unit_id = ?");
        $stmt->execute([$_SESSION['duty_id']]);
        $countMyCalls = $stmt->fetchColumn();
        if ($countMyCalls === 0) {
          echo 'No Active 911 Calls';
        } else {
          echo '<table class="table table-borderless">
            <thead>
              <tr>
                <th>Info</th>
                <th>Location</th>
                <th>View</th>
              </tr>
            </thead>
            <tbody>';
            $getMyCallList = 'SELECT * FROM assigned_callunits where unit_id = ?';
            $resultMyCallList         = $pdo->prepare($getMyCallList);
            $resultMyCallList->execute([$_SESSION['duty_id']]);
            while ($rowMyCallList = $resultMyCallList->fetch(PDO::FETCH_ASSOC)) {
              $getMyCallInfo = 'SELECT * FROM 911calls where call_id = ?';
              $resultMyCallInfo         = $pdo->prepare($getMyCallInfo);
              $resultMyCallInfo->execute([$rowMyCallList['call_id']]);
              $rowCallInfo = $resultMyCallInfo->fetch(PDO::FETCH_ASSOC);
              echo '
              <tr>
                <td width="60%">'.$rowCallInfo['call_description'].'</td>
                <td>'.$rowCallInfo['call_location'].' / '.$rowCallInfo['call_postal'].'</td>
                <td><a href="javascript:void(0);" data-href="inc/backend/user/dispatch/getCallInfo.php?id='.$rowCallInfo['call_id'].'" class="openCallInfoModal">View</a></td>
              </tr>';
        }
        echo '</tbody>
      </table>';
      }
    } else {
      echo '<table class="table table-borderless">
        <thead>
          <tr>
            <th>Info</th>
            <th>Location</th>
            <th>View</th>
          </tr>
        </thead>
        <tbody>';
        $getPriCall = 'SELECT * FROM 911calls where call_isPriority = ?';
        $resultPriCall         = $pdo->prepare($getPriCall);
        $resultPriCall->execute(['true']);
        while ($rowPriCall = $resultPriCall->fetch(PDO::FETCH_ASSOC)) {
          echo '
          <tr class="table-danger">
            <td width="60%">'.$rowPriCall['call_description'].'</td>
            <td>'.$rowPriCall['call_location'].' / '.$rowPriCall['call_postal'].'</td>
            <td><a href="javascript:void(0);" data-href="inc/backend/user/dispatch/getCallInfo.php?id='.$rowPriCall['call_id'].'" class="openCallInfoModal">View</a></td>
          </tr>';
    }
    echo '
    </tbody>
  </table>';
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
