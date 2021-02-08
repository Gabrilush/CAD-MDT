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
        <th><center>Created On</center></th>
        <th><center>Description</center></th>
        <th><center>View</center></th>
      </tr>
      ';
      $getActiveBolos = 'SELECT * FROM bolos';
      $result         = $pdo->prepare($getActiveBolos);
      $result->execute();
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          echo "<td><center>" . $row['created_on'] . "</center></td>";
          echo "<td><center>" . truncate_string($row['description'], 100, ' .....') . "</center></td>";
          echo '<td><center><a href="javascript:void(0);" data-href="inc/backend/user/dispatch/getBoloInfo.php?id='.$row['id'].'" class="openBoloInfoModal">View</a></center></td>';
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
      $('.openBoloInfoModal').on('click',function(){
          var dataURL = $(this).attr('data-href');
          $('#boloModalBody.modal-body').load(dataURL,function(){
              $('#boloInfoModal').modal({show:true});
          });
      });
    });
    </script>
  </head>
  <body>
  </body>
</html>
