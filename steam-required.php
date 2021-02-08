<?php
session_name('hydrid');

require_once 'steamauth/steamauth.php';

require_once 'inc/connect.php';

require_once 'inc/config.php';

require_once 'inc/backend/user/auth/userIsLoggedIn.php';

error_reporting(0);
ini_set('display_errors', 0);


$page['name'] = 'Please Link Your Steam';
if (isset($_GET['s']) && strip_tags($_GET['s']) === '2') {
    include ('steamauth/userInfo.php');
    //db stuff
    $stmt              = $pdo->prepare("UPDATE `users` SET `steam_id`=:steam_id WHERE `user_id`=:user_id");
    $stmt->bindValue(':steam_id', $steamprofile['steamid']);
    $stmt->bindValue(':user_id', $user_id);
    $result = $stmt->execute();

    $stmt2              = $pdo->prepare("UPDATE `users` SET `avatar`=:steam_id WHERE `user_id`=:user_id");
    $stmt2->bindValue(':steam_id', $steamprofile['avatar']);
    $stmt2->bindValue(':user_id', $user_id);
    $result2 = $stmt->execute();

    header('Location: ' . $url['index'] . '?notify=steam-linked');
	exit();
}

// Page PHP
?>
<?php include 'inc/page-top.php'; ?>

<body>
    <?php include 'inc/top-nav.php'; ?>
    <!-- CONTENT START -->
    <div class="wrapper m-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h4 class="page-title">
                        <?php echo $page['name']; ?></h4>
                </div>
            </div>
            <!-- CONTENT HERE -->
            <div class="row">
                <div class="col">
                    <div class="alert alert-danger" role="alert">
                        You are required to link your Steam Account
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?php loginbutton("rectangle"); ?>
                </div>
            </div>
            <!-- CONTENT END -->
            <?php include 'inc/copyright.php'; ?>
            <?php include 'inc/page-bottom.php'; ?>
