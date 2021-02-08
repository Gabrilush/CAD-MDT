<?php
session_name('hydrid');
session_start();
require_once 'inc/connect.php';

require_once 'inc/config.php';

require_once 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'ESTADÍSTICAS GENERALES DEL SITIO';
?>
<?php include 'inc/page-top.php'; ?>

<body>
    <?php include 'inc/top-nav.php'; ?>
    <?php
        if (isset($_GET['notify']) && strip_tags($_GET['notify']) === 'steam-linked') {
            clientNotify('success', 'Your Steam Account Has Been Linked.');
        }
        $stats['users'] = null;
        $stats['staff'] = null;
        $stats['civ'] = null;
        $stats['ems'] = null;

        $stats['users'] = $pdo->query('select count(*) from users')->fetchColumn();
        $stats['staff'] = $pdo->query('select count(*) from users WHERE usergroup <> "1" AND usergroup <> "2" AND usergroup <> "3"')->fetchColumn();
        $stats['civ'] = $pdo->query('select count(*) from characters')->fetchColumn();
        $stats['ems'] = $pdo->query('select count(*) from identities')->fetchColumn();
        ?>
    <!-- CONTENT START -->
    <div class="wrapper m-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h4 class="page-title"><?php echo $page['name']; ?></h4>
                </div>
            </div>
            <div class="alert alert-warning" role="alert">
                <strong>Warning: </strong> Esto es para mera prueba, aún no se oficializa y pueden haber cambios en el proceso.
            </div>
            <div class="row">
                <div class="col col-xs-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Usuarios Totales</h4>
                        <h2 class="p-t-10 mb-0"><?php echo $stats['users']; ?></h2>
                    </div>
                </div>
                <div class="col col-xs-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Administradores Totales</h4>
                        <h2 class="p-t-10 mb-0"><?php echo $stats['staff']; ?></h2>
                    </div>
                </div>
                <div class="col col-xs-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Fichas Civiles</h4>
                        <h2 class="p-t-10 mb-0"><?php echo $stats['civ']; ?></h2>
                    </div>
                </div>
                <div class="col col-xs-6">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Oficiales Disponibles</h4>
                        <h2 class="p-t-10 mb-0"><?php echo $stats['ems']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Otro mensaje</h4>
                        <p>En constante cambio.</i></p>
                    </div>
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Un mensaje</h4>
                        <p>Siempre culto al rey.</i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT END -->
    <?php include 'inc/copyright.php'; ?>
    <?php include 'inc/page-bottom.php'; ?>
