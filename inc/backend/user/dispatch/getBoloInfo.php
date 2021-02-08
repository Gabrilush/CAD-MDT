<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

// Makes sure the person actually has a character set
if ($_SESSION['on_duty'] === "Dispatch" || $_SESSION['on_duty'] === "LEO") {
    $bolo_id = strip_tags($_GET['id']);
    $sql = "SELECT * FROM bolos WHERE id= ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$bolo_id]);
    $boloInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['viewingBoloID'] = $bolo_id;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#updateBoloDesc').ajaxForm(function (error) {
                console.log(error);
                var error = JSON.parse(error);
                if (error['msg'] === "") {
                    toastr.success('BOLO Description Updated.', 'System:', {timeOut: 10000});
                } else {
                    toastr.error(error['msg'], 'System:', {
                        timeOut: 10000
                    });
                }
            });
        });
    </script>
  </head>
  <body>
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label for="created_on">Created On</label>
          <input class="form-control" type="text" readonly="" value="<?php echo $boloInfo['created_on'] ?>">
        </div>
      </div>
      <div class="col-6">
        <div class="form-group">
          <label for="created_by">Created By</label>
          <input class="form-control" type="text" readonly="" value="<?php
          $sql_bcr             = "SELECT * FROM identities WHERE identity_id = ?";
        	$stmt_bcr            = $pdo->prepare($sql_bcr);
        	$stmt_bcr->execute([$boloInfo['created_by']]);
        	$idrRow = $stmt_bcr->fetch(PDO::FETCH_ASSOC);

          echo $idrRow['name'];
           ?>">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="boloDesc">Call Details</label>
          <form id="updateBoloDesc" method="post" action="inc/backend/user/dispatch/updateBoloDesc.php">
            <textarea class="form-control" id="boloDesc" name="boloDesc" style="white-space: pre-line;" wrap="hard" rows="6"><?php echo $boloInfo['description']; ?></textarea>
            <div class="row">
              <div class="col-12">
                <input class="btn btn-warning btn-block" type="submit" value="Update Bolo Description">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <input type="button" class="btn btn-danger btn-block" name="clearBOLO" value="Clear BOLO" onclick="clearBOLO()">
      </div>
    </div>
  </body>
</html>
