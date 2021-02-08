<?php
session_name('hydrid');
session_start();
require_once 'inc/connect.php';

require_once 'inc/config.php';

require_once 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'MOBILE DATA COMPUTER';

// Page PHP
$view = strip_tags($_GET['v']);

if (isset($_GET['v']) && strip_tags($_GET['v']) === 'setsession') {
    if (isset($_GET['id']) && strip_tags($_GET['id'])) {
        $id   = $_GET['id'];
        $sql  = "SELECT * FROM identities WHERE identity_id = :identity_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':identity_id', $id);
        $stmt->execute();
        $identityDB = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($identityDB === false) {
            header('Location: ' . $url['leo'] . '?v=nosession&error=identity-not-found');
            exit();
        } else {
            $identity_id             = $identityDB['identity_id'];
            $_SESSION['identity_id'] = $identity_id;

            $identity_name             = $identityDB['name'];
            $_SESSION['identity_name'] = $identity_name;

            $identity_department             = $identityDB['department'];
            $_SESSION['identity_department'] = $identity_department;

            $identity_division             = $identityDB['division'];
            $_SESSION['identity_division'] = $identity_division;

            $identity_supervisor             = $identityDB['supervisor'];
            $_SESSION['identity_supervisor'] = $identity_supervisor;

            $identity_owner             = $identityDB['user'];
            $_SESSION['identity_owner'] = $identity_owner;

            $_SESSION['notepad'] = "";

            $_SESSION['on_duty'] = "LEO";

            if ($identity_owner !== $user_id) {
    				header('Location: '.$url['leo'].'?v=nosession&error=identity-owner');
    				exit();
			}

			$stmt2              = $pdo->prepare("DELETE FROM `on_duty` WHERE `name`=:identity_name");
			$stmt2->bindValue(':identity_name', $identity_name);
			$result2 = $stmt2->execute();
			$stmt3              = $pdo->prepare("INSERT INTO on_duty (name, department, division, status) VALUES (:name, :department, :division, 'Off-Duty')");
			$stmt3->bindValue(':name', $identity_name);
			$stmt3->bindValue(':department', $identity_department);
			$stmt3->bindValue(':division', $identity_division);
			$result3 = $stmt3->execute();

          header('Location: '.$url['leo'].'?v=main');
	         exit();
        }
    }
}
?>
<?php include 'inc/page-top.php'; ?>
<script src="assets/js/pages/leo.js?v=<?php echo $assets_ver ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#createIdentity').ajaxForm(function(error) {
            error = JSON.parse(error);
            if (error['msg'] === "") {
                $("#createIdentity")[0].reset();
                toastr.success('Identidad creada, ya puedes seleccionarla.', 'System:', {
                    timeOut: 10000
                })
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                })
            }
        });
        $('#newTicket').ajaxForm(function(error) {
            error = JSON.parse(error);
            if (error['msg'] === "") {
                $("#newTicket")[0].reset();
                $('#newTicketModal').modal('hide');
                toastr.success('Ticket creado.', 'System:', {
                    timeOut: 10000
                })
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                })
            }
        });
        $('#newArrestReport').ajaxForm(function(error) {
            error = JSON.parse(error);
            if (error['msg'] === "") {
                $("#newArrestReportModal")[0].reset();
                $('#newArrestReportModal').modal('hide');
                toastr.success('Reporte de arresto creado.', 'System:', {
                    timeOut: 10000
                })
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                })
            }
        });
    });
</script>

<body>
    <?php include 'inc/top-nav.php';

        if (isset($_GET['error']) && strip_tags($_GET['error']) === 'identity-not-found') {
            clientNotify('error', 'No encontramos tu identidad.');
        } elseif (isset($_GET['error']) && strip_tags($_GET['error']) === 'identity-owner') {
            clientNotify('error', 'Sin permisos.');
        } elseif (isset($_GET['error']) && strip_tags($_GET['error']) === 'identity-session') {
            clientNotify('error', 'Error de sesión, elije de nuevo la identidad.');
        }
        ?>
    <!-- CONTENT START -->
    <div class="wrapper m-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h4 class="page-title">
                        <?php echo $page['name']; ?> <label id="displayAOP"></label>
                    </h4>
                </div>
            </div>
            <!-- CONTENT HERE -->
            <?php switch($view):
			         case "nosession": ?>
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <select class="form-control" id="listIdentitys" onchange="location = this.value;">
                            <option selected="true" disabled="disabled">Cargando...</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">CREAR PERSONAJE DE LA LEY</h4>
                        Es recomendado utilizar el Nombre Apellido de tu personaje dentro del servidor. Todo personaje no autorizado por la dirección de LSPD o LSSD no va a estar autorizado a ingresar en la base de datos.
                        <form class="form-horizontal m-t-20" id="createIdentity" action="inc/backend/user/leo/createIdentity.php" method="POST">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="name" placeholder="Sergey Gonzalez">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <select class="form-control" id="listLeoDivisions" name="division" required>
                                            <option selected="true" disabled="disabled">Cargando...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input class="btn btn-success btn-block" type="submit" value="CREAR LEO">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php break; ?>
            <?php case "main": ?>
            <!-- js is put here to prevent issues on other parts of leo -->
            <script type="text/javascript">
                $(document).ready(function() {
                    var signal100 = false;

                    function checkTime(i) {
                        if (i < 10) {
                            i = "0" + i;
                        }
                        return i;
                    }

                    $('textarea').keypress(function(event) {
                        if (event.which == 13) {
                            event.preventDefault();
                            this.value = this.value + "\n";
                        }
                    });

                    $('#changeAOP').ajaxForm(function(error) {
                        console.log(error);
                        error = JSON.parse(error);
                        if (error['msg'] === "") {
                            $("#changeAOP")[0].reset();
                            toastr.success('New AOP Set - Please allow a minute for changes to display.', 'System:', {
                                timeOut: 10000
                            })
                        } else {
                            toastr.error(error['msg'], 'System:', {
                                timeOut: 10000
                            })
                        }
                    });
                    $('#addWarrant').ajaxForm(function(error) {
                        console.log(error);
                        error = JSON.parse(error);
                        if (error['msg'] === "") {
                            $("#addWarrant")[0].reset();
                            toastr.success('Búsqueda agregada', 'System:', {
                                timeOut: 10000
                            })
                        } else {
                            toastr.error(error['msg'], 'System:', {
                                timeOut: 10000
                            })
                        }
                    });

                    function startTime() {
                        var today = new Date();
                        var h = today.getHours();
                        var m = today.getMinutes();
                        var s = today.getSeconds();
                        // add a zero in front of numbers<10
                        m = checkTime(m);
                        s = checkTime(s);
                        document.getElementById('getTime').innerHTML = h + ":" + m + ":" + s;
                        t = setTimeout(function() {
                            startTime()
                        }, 500);
                    }

                    startTime();

                    function getLeoInfo() {
                        (function loadStatus() {
                            $.ajax({
                                url: 'inc/backend/user/leo/getStatus.php',
                                success: function(data) {
                                    $('#getDutyStatus').html(data);
                                },
                                complete: function() {
                                    // Schedule the next request when the current one's complete
                                    setTimeout(loadStatus, 1000);
                                }
                            });
                        })();
                        (function loadAOP() {
                            $.ajax({
                                url: 'inc/backend/user/leo/getAOP.php',
                                success: function(data) {
                                    $('#displayAOP').html(data);
                                },
                                complete: function() {
                                    // Schedule the next request when the current one's complete
                                    setTimeout(loadAOP, 60000);
                                }
                            });
                        })();
                        (function loadSig100Status() {
                            $.ajax({
                                url: 'inc/backend/user/leo/checkSignal100.php',
                                success: function(data) {
                                    if (data === "1") {
                                        toastr.options = {
                                            "preventDuplicates": true,
                                            "preventOpenDuplicates": true
                                        };
                                        toastr.error('998 ACTIVO', 'System:', {
                                            timeOut: 10000
                                        })
                                        $('#signal100Status').html("<font color='red'><b> 998 ESTÁ EN VIGENCIA</b></font>");

                                        if (!signal100) {
                                            var audio = new Audio('assets/sounds/signal100.mp3');
                                            audio.play();
                                            setTimeout(() => {
                                                var msg = new SpeechSynthesisUtterance('998 in progress, wait for new information');
                                                var voices = window.speechSynthesis.getVoices();
                                                window.speechSynthesis.speak(msg);
                                            }, 3000);
                                        }
                                        signal100 = true;
                                    } else {
                                        $('#signal100Status').html("");
                                        signal100 = false;
                                    }
                                },
                                complete: function() {
                                    // Schedule the next request when the current one's complete
                                    setTimeout(loadSig100Status, 500);
                                }
                            });
                        })();
                    }
                    getLeoInfo();
                });
            </script>
            <!-- code here -->
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <div class="dropdown pull-right">
                            <b>
                                <div id="getTime">Cargando...</div>
                            </b>
                        </div>
                        <h4 class="header-title mt-0 m-b-30"><?php echo $_SESSION['identity_name']; ?> <?php if ($_SESSION['identity_supervisor'] === "Yes"): ?><small><i>Supervisor</i></small><?php endif; ?> <label id="signal100Status">Cargando...</label></h4>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openNameSearch">DB de personas</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openVehicleSearch">DB de vehículos</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openFirearmSearch">DB de armas</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#newTicketModal">Tickets</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#newArrestReportModal">Reporte de arrestos</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#notepadModal">Notas personales</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#activeUnitsModal">Unidades en tour de guardia</button>
                        <!--<button class="btn btn-danger btn-sm" onclick="officerPanicBtn();">PANIC BUTTON</button>-->
                        <?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                        <a href="leo.php?v=supervisor"><button class="btn btn-darkred btn-sm">Panel de Supervisor</button></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div id="checkDispatchers">Cargando...</div>
            <div class="row">
                <div class="col-9">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Llamados de servicio
                        </h4>
                        <div id="getMyCalls"></div>
                        <div id="noDis911Calls"></div>
                    </div>

                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">BOLOs activos</h4>
                        <div id="getBolos"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Estado actual: <label id="getDutyStatus">Cargando...</label></h4>
                        <div class="form-group">
                            <select class="form-control" name="setUnitStatus" onChange='setUnitStatus(this)'>
                                <?php
            										$sql             = "SELECT * FROM 10_codes";
            										$stmt            = $pdo->prepare($sql);
            										$stmt->execute();
            										$dbq10codes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            										foreach($dbq10codes as $codes) {
            											echo '<option value="'. $codes['code'] .'">'. $codes['code'] .'</option>';
            										}
            										?>
                            </select>
                        </div>
                    </div>

                    <!--<?php if($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30"></h4>
                        <form method="post" action="inc/backend/user/leo/setAOP.php" id="changeAOP">
                            <div class="form-group">
                                <div class="col">
                                    <input class="form-control" type="text" required="" name="newAOP" placeholder="New AOP">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <button class="btn btn-warning btn-bordred btn-block waves-effect waves-light" onClick="disableClick()" type="submit">Change AOP</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>-->

                    <?php if($settings['add_warrant'] === "supervisor" && $_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Orden de búsqueda rápida</h4>
                        <form method="post" action="inc/backend/user/leo/addWarrant.php" id="addWarrant">
                            <div class="form-group">
                                <div class="col">
                                    <select class="form-control select2" name="civilian" id="getAllCharacters4">
                                        <option selected="true" disabled="disabled">Cargando...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <input class="form-control" type="text" required="" name="reason" placeholder="Reason">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <button class="btn btn-info btn-bordred btn-block waves-effect waves-light" onClick="disableClick()" type="submit">Añadir Búsqueda</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php elseif ($settings['add_warrant'] === "all"): ?>
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Creador de órdenes de búsqueda rápido</h4>
                        <form method="post" action="inc/backend/user/leo/addWarrant.php" id="addWarrant">
                            <div class="form-group">
                                <div class="col">
                                    <select class="select2" name="civilian" id="getAllCharacters4">
                                        <option selected="true" disabled="disabled">Cargando...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <input class="form-control" type="text" required="" name="reason" placeholder="Reason">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <button class="btn btn-info btn-bordred btn-block waves-effect waves-light" onClick="disableClick()" type="submit">Añadir búsqueda</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
            <!-- MODALS -->
            <!-- Call Info Modal -->
            <div class="modal fade" id="callInfoModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Información de llamados de servicio</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="callModalBody" class="modal-body">

                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- search name modal -->
            <div class="modal fade" id="openNameSearch" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">DB de personas</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <select class="select2" name="nameSearch" id="getAllCharacters" onchange="showName(this.value)">
                                    <option selected="true" disabled="disabled">Cargando...</option>
                                </select>
                            </form>
                            <br>
                            <div id="showPersonInfo"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- search vehicle modal -->
            <div class="modal fade" id="openVehicleSearch" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">DB de vehículos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <select class="select2" name="vehicleSearch" id="getAllVehicles" onchange="showVehicle(this.value)">
                                    <option selected="true" disabled="disabled">Cargando...</option>
                                </select>
                            </form>
                            <br>
                            <div id="showVehicleInfo"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- search firearm modal -->
            <div class="modal fade" id="openFirearmSearch" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">DB de armas</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <select class="select2" name="firearmSearch" id="getAllFirearms" onchange="showFirearm(this.value)">
                                    <option selected="true" disabled="disabled">Cargando...</option>
                                </select>
                            </form>
                            <br>
                            <div id="showFirearmInfo"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- active units modal -->
            <div class="modal fade" id="activeUnitsModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Unidades en tour de guardia</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <small>Actualizado cada 15 segundos</small>
                            <div id="getActiveUnits"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- notepad modal -->
            <div class="modal fade" id="notepadModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Notas personales</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="leo-index.php">
                                <div class="form-group">
                                    <textarea class="form-control" name="textarea" oninput="updateNotepad(this.value)" rows="12" cols="104"><?php echo $_SESSION['notepad']; ?></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- new ticket modal -->
            <div class="modal fade" id="newTicketModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Escribiendo nuevo ticket</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="newTicket" action="inc/backend/user/leo/newTicket.php" method="post">
                                <div class="form-group">
                                    <select class="select2" name="suspect" id="getAllCharacters2" required>
                                        <option selected="true" disabled="disabled">Cargando...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="location" class="form-control" placeholder="Ubicación" data-lpignore="true" required />
                                </div>
                                <div class="form-group">
                                    <input type="text" name="postal" class="form-control" pattern="\d*" placeholder="(Código postal)" data-lpignore="true" required />
                                </div>
                                <div class="form-group">
                                    <input type="text" name="amount" class="form-control" pattern="\d*" placeholder="Monto" data-lpignore="true" required />
                                </div>
                                <div class="form-group">
                                    <input type="text" name="reason" class="form-control" maxlength="255" placeholder="Razón(es)" data-lpignore="true" required />
                                </div>
                                <div class="modal-footer">
                                    <div class="form-group">
                                        <input class="btn btn-primary" onClick="disableClick()" type="submit" value="Finalizar">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <!-- new arrest modal -->
            <div class="modal fade" id="newArrestReportModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Escribir nuevo reporte de arresto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="newArrestReport" action="inc/backend/user/leo/newArrestReport.php" method="post">
                                <div class="form-group">
                                    <select class="select2" name="suspect" id="getAllCharacters3" required>
                                        <option selected="true" disabled="disabled">Cargando...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="reason" class="form-control" maxlength="500" placeholder="Summary" data-lpignore="true" required />
                                </div>
                                <div class="modal-footer">
                                    <div class="form-group">
                                        <input class="btn btn-primary" onClick="disableClick()" type="submit" value="Enviar Reporte de Arresto">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // -->
            <?php break; ?>

            <?php case "supervisor": ?>
            <?php if($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
            <?php if(isset($_GET['a']) && strip_tags($_GET['a']) === 'edit-id'): ?>
            <?php
								$id   = $_GET['id'];
								$sql  = "SELECT * FROM identities WHERE identity_id = :identity_id AND department='Law Enforcement'";
								$stmt = $pdo->prepare($sql);
								$stmt->bindValue(':identity_id', $id);
								$stmt->execute();
								$idDB = $stmt->fetch(PDO::FETCH_ASSOC);
								if ($idDB === false) {
									 echo '<script> location.replace("' . $url['leo'] . '?v=supervisor&error=id-not-found"); </script>';
									 exit();
								} else {
									$editing_id['id']	= $idDB['identity_id'];
									$_SESSION['editing_identity_id']	= $editing_id['id'];

									$editing_id['name']	= $idDB['name'];
									$editing_id['division']	= $idDB['division'];
									$editing_id['supervisor']	= $idDB['supervisor'];
									$editing_id['user']	= $idDB['user_name'];
									$editing_id['status']	= $idDB['status'];
								}

								if (isset($_POST['suspendIdBtn'])) {
									$sql = "UPDATE identities SET status=? WHERE identity_id=?";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['Suspended', $_SESSION['editing_identity_id']]);
									echo '<script> location.replace("' . $url['leo'] . '?v=supervisor&id=suspended"); </script>';
									exit();
								}
								if (isset($_POST['unsuspendIdBtn'])) {
									$sql = "UPDATE identities SET status=? WHERE identity_id=?";
									$stmt = $pdo->prepare($sql);
									$stmt->execute(['Active', $_SESSION['editing_identity_id']]);
									echo '<script> location.replace("' . $url['leo'] . '?v=supervisor&id=unsuspended"); </script>';
									exit();
								}
								if (isset($_POST['editIdBtn'])) {
									$updateDivision    = !empty($_POST['division']) ? trim($_POST['division']) : null;
									$updateDivision    = strip_tags($updateDivision);
									$updateSupervisor    = !empty($_POST['supervisor']) ? trim($_POST['supervisor']) : null;
    								$updateSupervisor    = strip_tags($updateSupervisor);

									$sql = "UPDATE identities SET division=?, supervisor=? WHERE identity_id=?";
									$stmt = $pdo->prepare($sql);
									$stmt->execute([$updateDivision, $updateSupervisor, $_SESSION['editing_identity_id']]);
									echo '<script> location.replace("' . $url['leo'] . '?v=supervisor&id=edited"); </script>';
									exit();
								}
								?>
            <div class="row">
                <div class="col-7">
                    <?php if($editing_id['status'] === "Suspended"): ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>This identity is Suspended.</strong>
                    </div>
                    <?php endif; ?>
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Editar LEO (<?php echo $editing_id['name']; ?>)</h4>
                        <form method="POST">
                            <div class="form-group">
                                <div class="col-12">
                                    <label for="supervisor">Supervisor</label>
                                    <select class="custom-select my-1 mr-sm-2" id="supervisor" name="supervisor">
                                        <option selected value="<?php echo $editing_id['supervisor']; ?>"><?php echo $editing_id['supervisor']; ?> (Actual)</option>
                                        <option value="No">No</option>
                                        <option value="Yes">Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <label for="division">Division</label>
                                    <select class="custom-select my-1 mr-sm-2" id="division" name="division">
                                        <option selected value="<?php echo $editing_id['division']; ?>"><?php echo $editing_id['division']; ?> (Actual)</option>
                                        <?php
														$sql             = "SELECT * FROM leo_division";
														$stmt            = $pdo->prepare($sql);
														$stmt->execute();
														$divRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
														foreach($divRow as $leoDivision) {
															echo '
																<option value="' . $leoDivision['name'] . '">' . $leoDivision['name'] . '</option>
															';
														}
														?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <div class="row">
                                    <div class="col-6">
                                        <button class="btn btn-success btn-bordred btn-block waves-effect waves-light" type="submit" name="editIdBtn">Editar</button>
                                    </div>
                                    <div class="col-6">
                                        <?php if($editing_id['status'] === "Suspended"): ?>
                                        <button class="btn btn-danger btn-bordred btn-block waves-effect waves-light" type="submit" name="unsuspendIdBtn">Reincorporar</button>
                                        <?php else: ?>
                                        <button class="btn btn-danger btn-bordred btn-block waves-effect waves-light" type="submit" name="suspendIdBtn">Suspender</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Arrests (<?php echo $editing_id['name']; ?>)</h4>
                        <table id="datatable" class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>ID de Arresto</th>
                                    <th>Fecha/Hora</th>
                                    <th>Sospechoso</th>
                                    <th>Información</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php
											$sql             = "SELECT * FROM arrest_reports WHERE arresting_officer=:editing_idname";
											$stmt            = $pdo->prepare($sql);
											$stmt->bindValue(':editing_idname', $editing_id['name']);
											$stmt->execute();
											$arrestsRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

											foreach ($arrestsRow as $arrest) {
												echo '
												<tr>
													<td>'. $arrest['arrest_id'] .'</td>
													<td>'. $arrest['timestamp'] .'</td>
													<td>'. $arrest['suspect'] .'</td>
													<td width="50%">'. $arrest['summary'] .'</td>
												</tr>
												';
											}
											?>
                        </table>
                    </div>
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Tickets (<?php echo $editing_id['name']; ?>)</h4>
                        <table id="datatable2" class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>ID de Ticket</th>
                                    <th>Fecha/Hora</th>
                                    <th>Sospechoso</th>
                                    <th>Razón</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php
											$sql2             = "SELECT * FROM tickets WHERE officer=:editing_idname";
											$stmt2            = $pdo->prepare($sql2);
											$stmt2->bindValue(':editing_idname', $editing_id['name']);
											$stmt2->execute();
											$ticketRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);

											foreach ($ticketRow as $ticket) {
												echo '
												<tr>
													<td>'. $ticket['ticket_id'] .'</td>
													<td>'. $ticket['ticket_timestamp'] .'</td>
													<td>'. $ticket['suspect'] .'</td>
													<td width="50%">'. $ticket['reasons'] .'</td>
												</tr>
												';
											}
											?>
                        </table>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Shift Logs (<?php echo $editing_id['name']; ?>)</h4>
                        <!-- CONTENT -->
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30"><?php echo $_SESSION['identity_name']; ?> <?php if ($_SESSION['identity_supervisor'] === "Yes"): ?><small>
                                <font color="white"><i>Supervisor</i></font>
                            </small><?php endif; ?></h4>
                        <?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                        <a href="leo.php?v=main"><button class="btn btn-info btn-sm">Volver al Panel</button></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-7">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Todos los oficiales</h4>
                        <table id="datatable" class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Sección</th>
                                    <th>Supervisor</th>
                                    <th>Usuario</th>
                                    <th>Status</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
										$sql             = "SELECT * FROM identities WHERE department='Law Enforcement'";
										$stmt            = $pdo->prepare($sql);
										$stmt->execute();
										$leoIdsRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

										foreach ($leoIdsRow as $identity) {
											echo '
											<tr>
												<td>'. $identity['name'] .'</td>
												<td>'. $identity['division'] .'</td>
												<td>'. $identity['supervisor'] .'</td>
												<td>'. $identity['user_name'] .'</td>
												<td>'. $identity['status'] .'</td>
												<td><a href="leo.php?v=supervisor&a=edit-id&id='. $identity['identity_id'] .'"><input type="button" class="btn btn-sm btn-success btn-block" value="Edit"></a></td>
											</tr>
											';
										}
										?>
                        </table>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">LEOs pendientes</h4>
                        <div id="getPendingIds"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <div class="alert alert-danger" role="alert">
                No eres Supervisor
            </div>
            <?php endif; ?>
            <?php break; ?>
            <?php endswitch; ?>
        </div>
    </div>
    <!-- CONTENT END -->
    <?php include 'inc/copyright.php'; ?>
    <?php include 'inc/page-bottom.php'; ?>
