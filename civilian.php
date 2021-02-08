<?php
session_name('hydrid');
session_start();
require_once 'inc/connect.php';

require_once 'inc/config.php';

require_once 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'REGISTRO DE PERSONAS/IMPRESIÓN DE INFORMACIÓN';

// Page PHP

$view = strip_tags($_GET['v']);

if (isset($_GET['v']) && strip_tags($_GET['v']) === 'setsession') {
    if (isset($_GET['id']) && strip_tags($_GET['id'])) {
        $id   = $_GET['id'];
        $sql  = "SELECT * FROM characters WHERE character_id = :character_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':character_id', $id);
        $stmt->execute();
        $characterDB = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($characterDB === false) {
            header('Location: ' . $url['civilian'] . '?v=nosession&error=character-not-found');
            exit();
        } else {
            $character_id                          = $characterDB['character_id'];
            $_SESSION['character_id']              = $character_id;
            $character_first_name                  = $characterDB['first_name'];
            $_SESSION['character_first_name']      = $character_first_name;
            $character_last_name                   = $characterDB['last_name'];
            $_SESSION['character_last_name']       = $character_last_name;
            $character_dob                         = $characterDB['date_of_birth'];
            $_SESSION['character_dob']             = $character_dob;
            $character_address                     = $characterDB['address'];
            $_SESSION['character_address']         = $character_address;
            $character_height                      = $characterDB['height'];
            $_SESSION['character_height']          = $character_height;
            $character_eye_color                   = $characterDB['eye_color'];
            $_SESSION['character_eye_color']       = $character_eye_color;
            $character_hair_color                  = $characterDB['hair_color'];
            $_SESSION['character_hair_color']      = $character_hair_color;
            $character_sex                         = $characterDB['sex'];
            $_SESSION['character_sex']             = $character_sex;
            $character_race                        = $characterDB['race'];
            $_SESSION['character_race']            = $character_race;
            $character_weight                      = $characterDB['weight'];
            $_SESSION['character_weight']          = $character_weight;
            $character_owner_id                    = $characterDB['owner_id'];
            $_SESSION['character_owner_id']        = $character_owner_id;
            $character_status                      = $characterDB['status'];
            $_SESSION['character_status']          = $character_status;
            $character_license_driver              = $characterDB['license_driver'];
            $_SESSION['character_license_driver']  = $character_license_driver;
            $character_license_firearm             = $characterDB['license_firearm'];
            $_SESSION['character_license_firearm'] = $character_license_firearm;
            $_SESSION['character_full_name']       = $character_first_name . ' ' . $character_last_name;
            if ($character_owner_id !== $user_id) {
                echo '<script type="text/javascript">
                window.location.href = "civilian.php?v=nosession&error=character-owner";
                </script>';
                exit();
            }

            echo '<script type="text/javascript">
            window.location.href = "civilian.php?v=main";
            </script>';
            exit();
        }
    }
}

?>
<?php include 'inc/page-top.php'; ?>
<script src="assets/js/pages/civilian.js?v=<?php echo $assets_ver ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#createCharacter').ajaxForm(function(error) {
            console.log(error);
            var error = JSON.parse(error);
            if (error['msg'] === "") {
                $("#createCharacter")[0].reset();
                toastr.success('Ficha creada', 'System:', {
                    timeOut: 10000
                });
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                });
            }
        });
        $('#new911call').ajaxForm(function(error) {
            console.log(error);
            var error = JSON.parse(error);
            if (error['msg'] === "") {
                $("#new911call")[0].reset();
                $('#new911callModal').modal('hide');
                toastr.success('Aviso creado', 'System:', {
                    timeOut: 10000
                });
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                });
            }
        });
        $('#createVehicle').ajaxForm(function(error) {
            var error = JSON.parse(error);
            if (error['msg'] === "") {
                $("#createVehicle")[0].reset();
                $('#newVehicleModal').modal('hide');
                toastr.success('Vehiculo agregado al sistema', 'System:', {
                    timeOut: 10000
                });
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                });
            }
        });
        $('#createFirearm').ajaxForm(function(error) {
            var error = JSON.parse(error);
            if (error['msg'] === "") {
                $("#createFirearm")[0].reset();
                $('#newFirearmModel').modal('hide');
                toastr.success('Registro armamentistico añadido', 'System:', {
                    timeOut: 10000
                });
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                });
            }
        });
        $('#createWarrant').ajaxForm(function(error) {
            var error = JSON.parse(error);
            if (error['msg'] === "") {
                $("#createWarrant")[0].reset();
                $('#newSelfWarrantModal').modal('hide');
                toastr.success('Aviso creado', 'System:', {
                    timeOut: 10000
                });
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                });
            }
        });
    });
</script>

<body>
    <?php
        if (isset($_GET['error']) && strip_tags($_GET['error']) === 'character-not-found') {
            clientNotify('error', 'No se encuentra a tal persona.');
        } elseif (isset($_GET['error']) && strip_tags($_GET['error']) === 'character-owner') {
            clientNotify('error', 'Sin permisos.');
        } elseif (isset($_GET['error']) && strip_tags($_GET['error']) === 'character-session') {
            clientNotify('error', 'Error de sesion, seleccionar civil de nuevo.');
        } elseif (isset($_GET['error']) && strip_tags($_GET['error']) === 'character-deleted') {
            clientNotify('error', 'Civil eliminado');
        }
        ?>
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
            <?php switch($view):
                  case "nosession": ?>
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <select class="form-control" id="listCharacters" onchange="location = this.value;">
                            <option selected="true" disabled="disabled">Cargando...</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">CREAR HISTORIAL DE PERSONA</h4>
                        Se deberá rellenar la información de la persona en su primer toma de huellas o prueba de saliva. Es recomendable buscar el historial de la persona antes de crear uno nuevo. (( AVISO: En el campo de la fecha se debe escoger la del día de creación del historial, no la fecha de nacimiento ))
                        <form class="form-horizontal m-t-20" id="createCharacter" action="inc/backend/user/civ/createCharacter.php" method="POST">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="firstname" placeholder="Nombres">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="lastname" placeholder="Apellidos">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <select class="form-control" name="gender" required="">
                                            <option selected="true" disabled="disabled">Seleccionar género</option>
                                            <option value="M">M</option>
                                            <option value="F">F</option>
                                            <!--<option value="O">Otro</option>-->
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <select class="form-control" name="race" required="">
                                            <option selected="true" disabled="disabled">Seleccionar raza</option>
                                            <option value="Alaska Native">Nativo de Alaska</option>
                                            <option value="American Indian">Nativo americano</option>
                                            <option value="Asian">Asiático</option>
                                            <option value="Black">Negro</option>
                                            <option value="African American">Afroamericano</option>
                                            <option value="Native Hawaiian">Hawaiano</option>
                                            <option value="White">Blanco</option>
                                            <option value="Hispanic">Hispano</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="address" placeholder="Dirección">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input class="form-control" type="date" required="" name="date_of_birth">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <select class="form-control" name="height" required="">
                                            <option selected="true" disabled="disabled">Seleccionar altura</option>
                                            <option value="4'6">4'6"</option>
                                            <option value="4'7">4'7"</option>
                                            <option value="4'8">4'8"</option>
                                            <option value="4'9">4'9"</option>
                                            <option value="4'10">4'10"</option>
                                            <option value="4'11">4'11"</option>
                                            <option value="5'0">5'0"</option>
                                            <option value="5'1">5'1"</option>
                                            <option value="5'2">5'2"</option>
                                            <option value="5'3">5'3"</option>
                                            <option value="5'4">5'4"</option>
                                            <option value="5'5">5'5"</option>
                                            <option value="5'6">5'6"</option>
                                            <option value="5'7">5'7"</option>
                                            <option value="5'8">5'8"</option>
                                            <option value="5'9">5'9"</option>
                                            <option value="5'10">5'10"</option>
                                            <option value="5'11">5'11"</option>
                                            <option value="6'0">6'0"</option>
                                            <option value="6'1">6'1"</option>
                                            <option value="6'2">6'2"</option>
                                            <option value="6'3">6'3"</option>
                                            <option value="6'4">6'4"</option>
                                            <option value="6'5">6'5"</option>
                                            <option value="6'6">6'6"</option>
                                            <option value="6'7">6'7"</option>
                                            <option value="6'8">6'8"</option>
                                            <option value="6'9">6'9"</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="weight" placeholder="Peso">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="eye_color" placeholder="Color de ojos">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="hair_color" placeholder="Color de pelo facial">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input class="btn btn-success btn-block" onClick="disableClick()" type="submit" value="GENERAR HISTORIAL DELICTIVO">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php break; ?>
            <?php case "main": ?>
            <?php
                $sql             = "SELECT * FROM characters WHERE character_id = :character_id";
                $stmt            = $pdo->prepare($sql);
                $stmt->bindValue(':character_id', $_SESSION['character_id']);
                $stmt->execute();
                $characterDB = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$characterDB) {
                   die("<font color='red'><b>SE CAYÓ EL SISTEMA - REINTENTAR</b></font>");
                } ?>
            <script type="text/javascript">
                function getCivInfo() {
                    (function loadVehicles() {
                        $.ajax({
                            url: 'inc/backend/user/civ/getVehicles.php',
                            success: function(data) {
                                $('#getVehicles').html(data);
                            },
                            complete: function() {
                                // Schedule the next request when the current one's complete
                                setTimeout(loadVehicles, 5000);
                            }
                        });
                    })();
                    (function loadFirearms() {
                        $.ajax({
                            url: 'inc/backend/user/civ/getFirearms.php',
                            success: function(data) {
                                $('#getFirearms').html(data);
                            },
                            complete: function() {
                                // Schedule the next request when the current one's complete
                                setTimeout(loadFirearms, 5005);
                            }
                        });
                    })();
                    (function loadTickets() {
                        $.ajax({
                            url: 'inc/backend/user/civ/getTickets.php',
                            success: function(data) {
                                $('#getTickets').html(data);
                            },
                            complete: function() {
                                // Schedule the next request when the current one's complete
                                setTimeout(loadTickets, 5010);
                            }
                        });
                    })();
                    (function loadWarrants() {
                        $.ajax({
                            url: 'inc/backend/user/civ/getWarrants.php',
                            success: function(data) {
                                $('#getWarrants').html(data);
                            },
                            complete: function() {
                                // Schedule the next request when the current one's complete
                                setTimeout(loadWarrants, 5015);
                            }
                        });
                    })();
                    (function loadArrests() {
                        $.ajax({
                            url: 'inc/backend/user/civ/getArrests.php',
                            success: function(data) {
                                $('#getArrests').html(data);
                            },
                            complete: function() {
                                // Schedule the next request when the current one's complete
                                setTimeout(loadArrests, 5020);
                            }
                        });
                    })();
                    (function checkWantedStatus() {
                        $.ajax({
                            url: 'inc/backend/user/civ/checkWantedStatus.php',
                            success: function(data) {
                                $('#isWanted').html(data);
                            },
                            complete: function() {
                                // Schedule the next request when the current one's complete
                                setTimeout(checkWantedStatus, 5050);
                            }
                        });
                    })();
                }
                getCivInfo();

                $(document).ready(function() {
                    var signal100 = false;

                    function loadSig100Status() {
                        $.ajax({
                            url: 'inc/backend/user/leo/checkSignal100.php',
                            success: function(data) {
                                if (data === "1") {
                                    toastr.options = {
                                        "preventDuplicates": true,
                                        "preventOpenDuplicates": true
                                    };
                                    toastr.error('SIGNAL 100 IS IN EFFECT. DO NOT START A NEW HIGH PRIORITY', 'System:', {
                                        timeOut: 10000
                                    })
                                    $('#civSignal100Notice').html("<font color='red'><b>SIGNAL 100 IS IN EFFECT. DO NOT START A NEW HIGH PRIORITY</b></font>");
                                    if (!signal100) {
                                        setTimeout(() => {
                                            var msg = new SpeechSynthesisUtterance('Signal 100 Is In Effect. Do Not Start New High Priority.');
                                            var voices = window.speechSynthesis.getVoices();
                                            window.speechSynthesis.speak(msg);
                                        }, 3000);
                                    }
                                    signal100 = true;
                                } else {
                                    $('#civSignal100Notice').html("");
                                    signal100 = false;
                                }
                            },
                            complete: function() {
                                // Schedule the next request when the current one's complete
                                setTimeout(loadSig100Status, 500);
                            }
                        });
                    }
                    loadSig100Status();
                });
            </script>
            <!-- Character Actions -->
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Acciones para <?php echo $_SESSION['character_first_name'] .' '. $_SESSION['character_last_name'] ?></h4>
                        <button class="btn btn-info btn-sm w-40" data-toggle="modal" data-target="#licenseModal">Manejo de licencias</button>
                        <button class="btn btn-secondary btn-sm ml-2 w-40" data-toggle="modal" data-target="#new911callModal">Nuevo aviso</button>
                        <button class="btn btn-danger btn-sm ml-2 w-40" data-toggle="modal" data-target="#deleteCharacterModel">Eliminar historial</button>
                    </div>
                </div>
            </div>
            <div id="civSignal100Notice"></div>
            <div id="isWanted"></div>
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <div class="dropdown pull-right">
                            <button class="btn btn-success btn-sm w-40" data-toggle="modal" data-target="#newVehicleModal">Agregar vehículo</button>
                        </div>
                        <h4 class="header-title mt-0 m-b-30">Vehículos de <?php echo $_SESSION['character_first_name'] ?></h4>
                        <!-- CONTENT -->
                        <div id="getVehicles">Cargando...</div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-box">
                        <div class="dropdown pull-right">
                            <button class="btn btn-success btn-sm w-40" data-toggle="modal" data-target="#newFirearmModel">Agregar arma de fuego</button>
                        </div>
                        <h4 class="header-title mt-0 m-b-30">Armas de fuego de <?php echo $_SESSION['character_first_name'] ?></h4>
                        <!-- CONTENT -->
                        <div id="getFirearms">Cargando...</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Tickets de <?php echo $_SESSION['character_first_name'] ?></h4>
                        <!-- CONTENT -->
                        <div id="getTickets">Cargando...</div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-box">
                        <?php if ($settings['civ_side_warrants'] === "true"): ?>
                        <div class="dropdown pull-right">
                            <button class="btn btn-success btn-sm w-40" data-toggle="modal" data-target="#newSelfWarrantModal">Agregar búsqueda (Self)</button>
                        </div>
                        <!-- New Warrant Model -->
                        <div class="modal fade" id="newSelfWarrantModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Agregando búsqueda</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="createWarrant" action="inc/backend/user/civ/createWarrant.php" method="post">
                                            <div class="form-group">
                                                <select class="form-control" name="warrant_reason" required>
                                                    <option value="" disabled selected>Seleccionar búsqueda...</option>
                                                    <option value="Murder">Asesinato</option>
                                                    <option value="Murder of a LEO">Asesinato de un LEO</option>
                                                    <option value="Murder of LEO(s)">Asesinato de varios LEOs</option>
                                                    <option value="Murder of a First Responder">Asesinato de un paramédico</option>
                                                    <option value="Murder of First Responder(s)">Asesinato de varios paramédicos</option>
                                                    <option value="Murder of a Government Official">Asesinatio de un oficial del Gobierno</option>
                                                    <option value="Murder of Government Official(s)">Asesinato de varios oficiales del Gobierno</option>
                                                    <option value="Kidnapping">Secuestro</option>
                                                    <!--<option value="Kidnapping of a LEO">Kidnapping of a LEO</option>
                                                    <option value="Kidnapping of LEO(s)">Kidnapping of LEO(s)</option>
                                                    <option value="Kidnapping of a First Responder">Kidnapping of a First Responder</option>
                                                    <option value="Kidnapping of First Responder(s)">Kidnapping of First Responder(s)</option>
                                                    <option value="Kidnapping of a Government Official">Kidnapping of a Government Official</option>
                                                    <option value="Kidnapping of Government Official(s)">Kidnapping of Government Official(s)</option>-->
                                                    <option value="Robbery">Robo</option>
                                                    <option value="Robbery /w Deadly Weapon">Robo con un arma</option>
                                                    <!--<option value="Bank Robbery">Bank Robbery</option>
                                                    <option value="Bank Robbery">Bank Robbery /w Deadly Weapon</option>
                                                    <option value="Bank Robbery">Prison Break</option>
                                                    <option value="Bank Robbery">Prison Break /w Deadly Weapon</option>
                                                    <option value="Bank Robbery">Prison Escape</option>-->
                                                    <option value="Failure To Appear In Court">Fallo de no aparecer en Corte</option>
                                                    <option value="Grand Theft">Robo de auto</option>
                                                    <option value="Grand Theft Auto">Robo de varios autos</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group">
                                                    <input class="btn btn-primary" type="submit" value="Add Warrant">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <h4 class="header-title mt-0 m-b-30">Orden de búsqueda de <?php echo $_SESSION['character_first_name'] ?></h4>
                        <!-- CONTENT -->
                        <div id="getWarrants">Cargando...</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Arrestos de <?php echo $_SESSION['character_first_name'] ?></h4>
                        <!-- CONTENT -->
                        <div id="getArrests">Cargando...</div>
                    </div>
                </div>
            </div>

            <!-- MODALS -->

            <!-- License Modal -->
            <div class="modal fade" id="licenseModal" tabindex="-1" role="dialog" aria-labelledby="licenseModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="licenseModal">License Management</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="lic_mgt_driver">Drivers License</label>
                                <select class="form-control" name="lic_mgt_driver" onChange='updateDriversLicense(this);'>
                                    <?php
                                 if ($_SESSION['character_license_driver'] === "None") {
                                   echo '
                                   <option value="None" selected>None</option>
                                   <option value="Valid">Valid</option>
                                   <option value="Suspended">Suspended</option>
                                   <option value="Revoked">Revoked</option>
                                   <option value="Fake">Fake</option>
                                   ';
                                 } elseif ($_SESSION['character_license_driver'] === "Valid") {
                                   echo '
                                   <option value="None">None</option>
                                   <option value="Valid" selected>Valid</option>
                                   <option value="Suspended">Suspended</option>
                                   <option value="Revoked">Revoked</option>
                                   <option value="Fake">Fake</option>
                                   ';
                                 } elseif ($_SESSION['character_license_driver'] === "Suspended") {
                                   echo '
                                   <option value="None">None</option>
                                   <option value="Valid">Valid</option>
                                   <option value="Suspended" selected>Suspended</option>
                                   <option value="Revoked">Revoked</option>
                                   <option value="Fake">Fake</option>
                                   ';
                                 } elseif ($_SESSION['character_license_driver'] === "Revoked") {
                                   echo '
                                   <option value="None">None</option>
                                   <option value="Valid">Valid</option>
                                   <option value="Suspended">Suspended</option>
                                   <option value="Revoked" selected>Revoked</option>
                                   <option value="Fake">Fake</option>
                                   ';
                                 } elseif ($_SESSION['character_license_driver'] === "Fake") {
                                   echo '
                                   <option value="None">None</option>
                                   <option value="Valid">Valid</option>
                                   <option value="Suspended">Suspended</option>
                                   <option value="Revoked">Revoked</option>
                                   <option value="Fake" selected>Fake</option>
                                   ';
                                 }
                                  ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="lic_mgt_ccw">CCW License</label>
                                <select class="form-control" name="lic_mgt_ccw" onChange='updateFirearmLicense(this);'>
                                    <?php
                                 if ($_SESSION['character_license_firearm'] === "None") {
                                   echo '
                                   <option value="None" selected>None</option>
                                   <option value="Valid">Valid</option>
                                   <option value="Suspended">Suspended</option>
                                   <option value="Revoked">Revoked</option>
                                   <option value="Fake">Fake</option>
                                   ';
                                 } elseif ($_SESSION['character_license_firearm'] === "Valid") {
                                   echo '
                                   <option value="None">None</option>
                                   <option value="Valid" selected>Valid</option>
                                   <option value="Suspended">Suspended</option>
                                   <option value="Revoked">Revoked</option>
                                   <option value="Fake">Fake</option>
                                   ';
                                 } elseif ($_SESSION['character_license_firearm'] === "Suspended") {
                                   echo '
                                   <option value="None">None</option>
                                   <option value="Valid">Valid</option>
                                   <option value="Suspended" selected>Suspended</option>
                                   <option value="Revoked">Revoked</option>
                                   <option value="Fake">Fake</option>
                                   ';
                                 } elseif ($_SESSION['character_license_firearm'] === "Revoked") {
                                   echo '
                                   <option value="None">None</option>
                                   <option value="Valid">Valid</option>
                                   <option value="Suspended">Suspended</option>
                                   <option value="Revoked" selected>Revoked</option>
                                   <option value="Fake">Fake</option>
                                   ';
                                 } elseif ($_SESSION['character_license_firearm'] === "Fake") {
                                   echo '
                                   <option value="None">None</option>
                                   <option value="Valid">Valid</option>
                                   <option value="Suspended">Suspended</option>
                                   <option value="Revoked">Revoked</option>
                                   <option value="Fake" selected>Fake</option>
                                   ';
                                 }
                                  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Call Modal -->
            <div class="modal fade" id="new911callModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New 911 Call</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="new911call" action="inc/backend/user/civ/new911call.php" method="post">
                                <div class="form-group">
                                    <input type="text" name="call_description" class="form-control" placeholder="Call Desc" data-lpignore="true" required />
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" id="street_ac2" name="call_location" class="form-control" placeholder="Street" data-lpignore="true" required />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="call_postal" class="form-control" pattern="\d*" placeholder="Postal" data-lpignore="true" />
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <input class="btn btn-primary" onClick="disableClick()" type="submit" value="Create New Call">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Character Modal -->
            <div class="modal fade" id="deleteCharacterModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">ELIMINANDO PERSONA (<?php echo $_SESSION['character_full_name']; ?>)</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="deleteCharacter" action="inc/backend/user/civ/deleteCharacter.php" method="post">
                                <div class="alert alert-danger" role="alert"><strong>¿Está seguro de eliminar a esta persona? No se puede deshacer</strong></div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                <input class="btn btn-danger" onClick="disableClick()" type="submit" value="Yes">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- New Vehicle Model -->
            <div class="modal fade" id="newVehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Register Vehicle</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="createVehicle" action="inc/backend/user/civ/createVehicle.php" method="post">
                                <div class="form-group">
                                    <input type="text" name="plate" class="form-control" maxlength="8" style="text-transform:uppercase" placeholder="License Plate" data-lpignore="true" required />
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <select class="form-control" name="color" required>
                                                <option value="" disabled selected>Vehicle Color</option>
                                                <option value="Black">Black</option>
                                                <option value="White">White</option>
                                                <option value="Red">Red</option>
                                                <option value="Blue">Blue</option>
                                                <option value="Green">Green</option>
                                                <option value="Yellow">Yellow</option>
                                                <option value="Orange">Orange</option>
                                                <option value="Brown">Brown</option>
                                                <option value="Gray">Gray</option>
                                                <option value="Silver">Silver</option>
                                                <option value="Gold">Gold</option>
                                                <option value="Cyan">Cyan</option>
                                                <option value="Purple">Purple</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <input type="text" name="model" class="form-control" maxlength="64" placeholder="Vehicle Model" data-lpignore="true" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <select class="form-control" name="insurance_status" required>
                                                <option value="" disabled selected>Insurance Status</option>
                                                <option value="None">None</option>
                                                <option value="Valid">Valid</option>
                                                <option value="Invalid">Invalid</option>
                                                <option value="Expired">Expired</option>
                                                <option value="Fake">Fake</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <select class="form-control" name="registration_status" required>
                                                <option value="" disabled selected>Registration Status</option>
                                                <option value="None">None</option>
                                                <option value="Valid">Valid</option>
                                                <option value="Invalid">Invalid</option>
                                                <option value="Expired">Expired</option>
                                                <option value="Fake">Fake</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="form-group">
                                        <input class="btn btn-primary" onClick="disableClick()" type="submit" value="Complete">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- New Firearm Model -->
            <div class="modal fade" id="newFirearmModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New Firearm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="createFirearm" action="inc/backend/user/civ/createFirearm.php" method="post">
                                <div class="form-group">
                                    <select class="form-control" id="weaponSelector" name="weapon" required>
                                        <option value="" disabled selected>Weapon...</option>
                                        <option value="AP Pistol">AP Pistol</option>
                                        <option value="Combat Pistol">Combat Pistol</option>
                                        <option value="Heavy Pistol">Heavy Pistol</option>
                                        <option value="Heavy Revolver">Heavy Revolver</option>
                                        <option value="Heavy Revolver Mk II">Heavy Revolver Mk II</option>
                                        <option value="Marksman Pistol">Marksman Pistol</option>
                                        <option value="Pistol">Pistol</option>
                                        <option value="Pistol Mk II">Pistol Mk II</option>
                                        <option value="Pistol .50">Pistol .50</option>
                                        <option value="SNS Pistol">SNS Pistol</option>
                                        <option value="SNS Pistol Mk II">SNS Pistol Mk II</option>
                                        <option value="Vintage Pistol">Vintage Pistol</option>
                                        <option value="Double-Action Revolver">Double-Action Revolver</option>
                                        <option value="Assault Shotgun">Assault Shotgun</option>
                                        <option value="Bullpup Shotgun">Bullpup Shotgun</option>
                                        <option value="Double Barrel Shotgun">Double Barrel Shotgun</option>
                                        <option value="Heavy Shotgun">Heavy Shotgun</option>
                                        <option value="Musket">Musket</option>
                                        <option value="Pump Shotgun">Pump Shotgun</option>
                                        <option value="Pump Shotgun Mk II">Pump Shotgun Mk II</option>
                                        <option value="Sawed-Off Shotgun">Sawed-Off Shotgun</option>
                                        <option value="Sweeper Shotgun">Sweeper Shotgun</option>
                                        <option value="Assault SMG">Assault SMG</option>
                                        <option value="Combat MG">Combat MG</option>
                                        <option value="Combat MG Mk II">Combat MG Mk II</option>
                                        <option value="Combat PDW">Combat PDW</option>
                                        <option value="Gusenberg Sweeper">Gusenberg Sweeper</option>
                                        <option value="Machine Pistol">Machine Pistol</option>
                                        <option value="MG">MG</option>
                                        <option value="Micro SMG">Micro SMG</option>
                                        <option value="Mini SMG">Mini SMG</option>
                                        <option value="SMG">SMG</option>
                                        <option value="SMG Mk II">SMG Mk II</option>
                                        <option value="Advanced Rifle">Advanced Rifle</option>
                                        <option value="Assault Rifle">Assault Rifle</option>
                                        <option value="Assault Rifle Mk II">Assault Rifle Mk II</option>
                                        <option value="Bullpup Rifle">Bullpup Rifle</option>
                                        <option value="Bullpup Rifle Mk II">Bullpup Rifle Mk II</option>
                                        <option value="Carbine Rifle">Carbine Rifle</option>
                                        <option value="Carbine Rifle Mk II">Carbine Rifle Mk II</option>
                                        <option value="Compact Rifle">Compact Rifle</option>
                                        <option value="Special Carbine">Special Carbine</option>
                                        <option value="Special Carbine Mk II">Special Carbine Mk II</option>
                                        <option value="Heavy Sniper">Heavy Sniper</option>
                                        <option value="Heavy Sniper Mk II">Heavy Sniper Mk II</option>
                                        <option value="Marksman Rifle">Marksman Rifle</option>
                                        <option value="Marksman Rifle Mk II">Marksman Rifle Mk II</option>
                                        <option value="Sniper Rifle">Sniper Rifle</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="rpstatus" required>
                                        <option value="" disabled selected>Status...</option>
                                        <option value="Valid">Valid</option>
                                        <option value="Stolen">Stolen</option>
                                        <option value="Blackmarket">Blackmarket</option>
                                    </select>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <input class="btn btn-primary" onClick="disableClick()" type="submit" value="Complete">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php break; ?>
        <?php endswitch; ?>
    </div>
    </div>
    <!-- CONTENT END -->
    <?php include 'inc/copyright.php'; ?>
    <?php include 'inc/page-bottom.php'; ?>
