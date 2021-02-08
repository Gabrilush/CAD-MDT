<?php
// Set all values to equal nothing first just to make sure nobody some how gets staff access or whatever
$group['banned'] = false;
$group['panel_access'] = false;
$group['staff_approveUsers'] = false;
$group['staff_access'] = false;
$group['staff_viewUsers'] = false;
$group['staff_editUsers'] = false;
$group['staff_editAdmins'] = false;
$group['staff_siteSettings'] = false;
$group['staff_banUsers'] = false;
$group['staff_SuperAdmin'] = false;

// Pull the usergroup from the database
$sql1_gp             = "SELECT * FROM usergroups WHERE id = :usergroup";
$stmt1_gp            = $pdo->prepare($sql1_gp);
$stmt1_gp->bindValue(':usergroup', $user['usergroup']);
$stmt1_gp->execute();
$groupRow = $stmt1_gp->fetch(PDO::FETCH_ASSOC);

if ($stmt1_gp->rowCount() < 0) {
    // Checks if the users usergroup is valid, if it is not, they are assigned to the default group
    $sql_iug = "UPDATE users SET usergroup=? WHERE user_id=?";
    $stmt_iug = $pdo->prepare($sql_iug);
    $stmt_iug->execute([$settings['verifiedGroup'], $user_id]);
}

// Define variables
$group['id'] = $groupRow['id'];
$group['name'] = $groupRow['name'];
$group['isBanned'] = $groupRow['isBanned'];
$group['panel_access'] = $groupRow['panel_access'];
$group['staff_approveUsers'] = $groupRow['staff_approveUsers'];
$group['staff_access'] = $groupRow['staff_access'];
$group['staff_viewUsers'] = $groupRow['staff_viewUsers'];
$group['staff_editUsers'] = $groupRow['staff_editUsers'];
$group['staff_editAdmins'] = $groupRow['staff_editAdmins'];
$group['staff_siteSettings'] = $groupRow['staff_siteSettings'];
$group['staff_banUsers'] = $groupRow['staff_banUsers'];
$group['staff_SuperAdmin'] = $groupRow['staff_SuperAdmin'];

define("isBanned", $group['banned']);
define("panel_access", $group['panel_access']);
define("staff_approveUsers", $group['staff_approveUsers']);
define("staff_access", $group['staff_access']);
define("staff_viewUsers", $group['staff_viewUsers']);
define("staff_editUsers", $group['staff_editUsers']);
define("staff_editAdmins", $group['staff_editAdmins']);
define("staff_siteSettings", $group['staff_siteSettings']);
define("staff_banUsers", $group['staff_banUsers']);
define("staff_SuperAdmin", $group['staff_SuperAdmin']);

if (isBanned) {
    session_unset();
    session_destroy();
    header('Location: login.php?error=banned');
    exit();
} elseif (!panel_access) {
    session_unset();
    session_destroy();
    header('Location: login.php?error=access');
    exit();
}
?>
