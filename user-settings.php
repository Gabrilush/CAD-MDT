<?php
session_name('hydrid');
session_start();
require_once 'inc/connect.php';
require_once 'inc/config.php';
require_once 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'User Settings';

if (isset($_POST['updateSettingsBtn'])) {
    $updateEmail    = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $updatePassword       = !empty($_POST['password']) ? trim($_POST['password']) : null;

    $updateEmail    = strip_tags($updateEmail);
    $updatePassword    = strip_tags($updatePassword);

    if ($user['email'] === $updateEmail) {
        // Checks if the user's current email, matches the new email...
        // if it does, we wont update it, so than we will check if the password field is empty or not
        if (empty($updatePassword)) {
            // okay... so they are both empty... we will just return them to the page.
            header('Location: '.$url['settings'].'?error=no-updates');
            exit();
        } else {
            // okay so the password isn't empty... we will update it now
            $passwordHash = password_hash($updatePassword, PASSWORD_BCRYPT, array("cost" => 12));
            $sql = "UPDATE users SET password=? WHERE user_id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$passwordHash, $user_id]);

            logAction('Datos de usuario actualizados', $user['username']);
            header('Location: '.$url['settings'].'?success=updated');
            exit();
        }
    } else {
        // Alright so they changed the email... we will check if the password was changed as well
        if (empty($updatePassword)) {
            // Okay, the password is empty, so only update the email.
            $sql = "UPDATE users SET email=? WHERE user_id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$updateEmail, $user_id]);

            logAction('Datos de usuario actualizados', $user['username']);
            header('Location: '.$url['settings'].'?success=updated');
            exit();
        } else {
            // okay so the password isn't empty... we will update it now
            $passwordHash = password_hash($updatePassword, PASSWORD_BCRYPT, array("cost" => 12));
            $sql = "UPDATE users SET password=?, email=? WHERE user_id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$passwordHash, $updateEmail, $user_id]);

            logAction('Datos de usuario actualizados', $user['username']);
            header('Location: '.$url['settings'].'?success=updated');
            exit();
        }
    }
}
?>
<?php include 'inc/page-top.php'; ?>

<body>
    <?php
    if (isset($_GET['error']) && strip_tags($_GET['error']) === 'no-updates') {
        throwError('Perdón pero no detectamos que hicieras algo.');
    } elseif (isset($_GET['success']) && strip_tags($_GET['success'])) {
        clientNotify('success', 'Settings Updated.');
    }
    ?>
    <?php include 'inc/top-nav.php'; ?>
    <!-- CONTENT START -->
    <div class="wrapper m-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h4 class="page-title"><?php echo $page['name']; ?></h4>
                </div>
            </div>
            <!-- CONTENT HERE -->
            <div class="row">
                <div class="col-6">
                    <div class="bg-picture card-box">
                        <h4 class="m-t-0 header-title">Configuración</h4>
                        <div class="profile-info-name">
                            <img src="<?php echo $user['avatar']; ?>" class="img-thumbnail" alt="profile-image">
                            <div class="profile-info-detail">
                                <form method="POST">
                                    <div class="form-group">
                                        <div class="col-12">
                                            <label for="email">Email</label>
                                            <input class="form-control" type="email" id="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-12">
                                            <label for="password">Contraseña</label>
                                            <input class="form-control" type="password" id="password" name="password" placeholder="Nueva contraseña...">
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <div class="col-12">
                                            <button class="btn btn-success btn-bordred btn-block waves-effect waves-light" type="submit" name="updateSettingsBtn">Actualizar datos</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-picture card-box">
                        <h4 class="m-t-0 header-title">Logs de Usuario</h4>
                        <!-- CONTENT -->
                        <table id="datatable" class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>ID de LOG</th>
                                    <th>Acción</th>
                                    <th>Fecha/Hora</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php
                        $sql             = "SELECT * FROM logs WHERE username=?";
                        $stmt            = $pdo->prepare($sql);
                        $stmt->execute([$user['username']]);
                        $logRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($logRow as $log) {
                        echo '
                        <tr>
                            <td>'. $log['log_id'] .'</td>
                            <td>'. $log['action'] .'</td>
                            <td>'. $log['timestamp'] .'</td>
                        </tr>
                        ';
                        }
                        ?>
                            </tbody>
                        </table>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTENT END -->
        <?php include 'inc/copyright.php'; ?>
        <?php include 'inc/page-bottom.php'; ?>
