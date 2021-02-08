<?php
session_name('hydrid');
session_start();
require_once 'inc/connect.php';

require_once 'inc/config.php';

require_once 'inc/backend/user/auth/userIsLoggedIn.php';

$page['name'] = 'DISPATCH SCC';

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
					header('Location: '.$url['dispatch'].'?v=nosession&error=identity-not-found');
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

            $_SESSION['on_duty'] = "Dispatch";

            if ($identity_owner !== $user_id) {
							header('Location: '.$url['dispatch'].'?v=nosession&error=identity-owner');
							exit();
						}

						$stmt2              = $pdo->prepare("DELETE FROM `on_duty` WHERE `name`=:identity_name");
						$stmt2->bindValue(':identity_name', $identity_name);
						$result2 = $stmt2->execute();
						$stmt3              = $pdo->prepare("INSERT INTO on_duty (name, department, status) VALUES (:name, :department, 'On-Duty')");
						$stmt3->bindValue(':name', $identity_name);
						$stmt3->bindValue(':department', $identity_department);
						$result3 = $stmt3->execute();

						header('Location: '.$url['dispatch'].'?v=main');
						exit();
			        }
			    }
}
?>
<?php include 'inc/page-top.php'; ?>
<script src="assets/js/pages/dispatch.js?v=<?php echo $assets_ver ?>"></script>
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
    });
</script>

<body>
    <?php include 'inc/top-nav.php';?>
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
                        <h4 class="header-title mt-0 m-b-30">CREAR PERSONAJE DISPATCH</h4>
                        Esta sección es para crear un personaje (como el del MDC) pero para llevar el rol de dispatch o SCC, debe ser autorizado por la cúpula del departamento para efectuar este tipo de rol.
                        <form class="form-horizontal m-t-20" id="createIdentity" action="inc/backend/user/dispatch/createIdentity.php" method="POST">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input type="text" class="form-control" required="" name="name" placeholder="John Doe">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input class="btn btn-success btn-block" type="submit" value="Crear personaje">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php break; ?>
            <?php case "main": ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    var elem = document.querySelector('.allcallCheckbox'); // referred checkbox class is here
                    var init = new Switchery(elem, {
                        size: 'small'
                    }); // put option after elem attribute

                    $('textarea').keypress(function(event) {
                        if (event.which == 13) {
                            event.preventDefault();
                            this.value = this.value + "\n";
                        }
                    });

                    var signal100 = false;

                    function checkTime(i) {
                        if (i < 10) {
                            i = "0" + i;
                        }
                        return i;
                    }

                    $('#new911call').ajaxForm(function(error) {
                        console.log(error);
                        var error = JSON.parse(error);
                        if (error['msg'] === "") {
                            $("#new911call")[0].reset();
                            $('#new911callModal').modal('hide');
                            toastr.success('Llamada añadida', 'System:', {
                                timeOut: 10000
                            });
                        } else if (error['msg'] === "allCall") {
                            $("#new911call")[0].reset();
                            $('#new911callModal').modal('hide');
                            changeSignal();
                            toastr.success('Llamada añadida', 'System:', {
                                timeOut: 10000
                            });
                        } else {
                            toastr.error(error['msg'], 'System:', {
                                timeOut: 10000
                            });
                        }
                    });

                    $('#newBolo').ajaxForm(function(error) {
                        console.log(error);
                        var error = JSON.parse(error);
                        if (error['msg'] === "") {
                            $("#newBolo")[0].reset();
                            $('#newBoloModel').modal('hide');
                            toastr.success('BOLO añadido', 'System:', {
                                timeOut: 10000
                            });
                        } else {
                            toastr.error(error['msg'], 'System:', {
                                timeOut: 10000
                            });
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
                                        toastr.error('SIGNAL 100 IS IN EFFECT.', 'System:', {
                                            timeOut: 10000
                                        })
                                        $('#signal100Status').html("<font color='red'><b> - SIGNAL 100 IS IN EFFECT</b></font>");

                                        if (!signal100) {
                                            var audio = new Audio('assets/sounds/signal100.mp3');
                                            audio.play();
                                            setTimeout(() => {
                                                var msg = new SpeechSynthesisUtterance('Signal 100 Activated - Check CAD For Details');
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
            <div class="row">
                <div class="col">
                    <div class="card-box">
                        <div class="dropdown pull-right">
                            <b>
                                <div id="getTime">Cargando...</div>
                            </b>
                        </div>
                        <h4 class="header-title mt-0 m-b-30"><?php echo $_SESSION['identity_name']; ?> <?php if ($_SESSION['identity_supervisor'] === "Yes"): ?><small>
                                <font color="white"><i>Supervisor</i></font>
                            </small><?php endif; ?> <label id="signal100Status">Cargando...</label></h4>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openNameSearch">DB de Nombres</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openVehicleSearch">DB de Vehículos</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#openFirearmSearch">DB de Armas</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#notepadModal">Notas Personales</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#new911callModal">Crear Aviso</button>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#newBoloModel">Crear BOLO</button>
                        <!--<button class="btn btn-danger btn-sm" onclick="changeSignal();">Signal 100</button>-->
                        <?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                        <a href="dispatch.php?v=supervisor"><button class="btn btn-darkred btn-sm">Supervisar</button></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">Avisos</h4>
                        <div id="get911Calls"></div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-30">BOLOs</h4>
                        <div id="getBolos"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                <div class="col-8">
                    <?php else: ?>
                    <div class="col-12">
                        <?php endif; ?>
                        <div class="card-box">
                            <h4 class="header-title mt-0 m-b-30">Unidades Activas</h4>
                            <div id="getActiveUnits"></div>
                        </div>
                    </div>
                    <?php if($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
                    <div class="col-4">
                        <div class="card-box">
                            <h4 class="header-title mt-0 m-b-30">AOP Editor</h4>
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
                    </div>
                    <?php endif; ?>
                </div>

                <!-- MODALS -->
                <!-- New Bolo Modal -->
                <div class="modal fade" id="newBoloModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Nuevo BOLO</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="newBolo" action="inc/backend/user/dispatch/newBolo.php" method="post">
                                    <div class="form-group">
                                        <textarea class="form-control" placeholder="Descripción (Que sea lo más detallado posible)" id="description" name="description" style="white-space: pre-line;" wrap="hard" rows="6" required></textarea>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <div class="form-group">
                                    <input class="btn btn-primary" onClick="disableClick()" type="submit" value="Crear BOLO">
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- // -->
                <!-- New Call Modal -->
                <div class="modal fade" id="new911callModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Nuevo Aviso</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="new911call" action="inc/backend/user/dispatch/new911call.php" method="post">
                                    <div class="form-group">
                                        <input type="text" name="call_description" class="form-control" placeholder="Descripción" data-lpignore="true" required />
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <input type="text" id="street_ac2" name="call_location" class="form-control" placeholder="Ubicación" data-lpignore="true" required />
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <input type="text" name="call_postal" class="form-control" pattern="\d*" placeholder="Número" data-lpignore="true" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label>All Call</label>
                                            <input type="checkbox" class="allcallCheckbox" name="allCall" value="1" />
                                        </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <div class="form-group">
                                    <input class="btn btn-primary" onClick="disableClick()" type="submit" value="Crear nuevo aviso">
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- // -->
                <!-- Call Info Modal -->
                <div class="modal fade" id="callInfoModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Info de Aviso</h5>
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
                <!-- BOLO Info Modal -->
                <div class="modal fade" id="boloInfoModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Info de BOLO</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div id="boloModalBody" class="modal-body">

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
                                <h5 class="modal-title" id="exampleModalLabel">DB de Personas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <select class="select2" name="nameSearch" id="getAllCharacters" onchange="showName(this.value)">
                                        <option selected="true" disabled="disabled">Cargando personas...</option>
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
                                <h5 class="modal-title" id="exampleModalLabel">DB de Vehículos</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <select class="select2" name="vehicleSearch" id="getAllVehicles" onchange="showVehicle(this.value)">
                                        <option selected="true" disabled="disabled">Cargando vehículos...</option>
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
                                <h5 class="modal-title" id="exampleModalLabel">DB de Armas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <select class="select2" name="firearmSearch" id="getAllFirearms" onchange="showFirearm(this.value)">
                                        <option selected="true" disabled="disabled">Cargando armas de fuego...</option>
                                    </select>
                                </form>
                                <br>
                                <div id="showFirearmInfo"></div>
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
                <?php break; ?>

								<?php case "supervisor": ?>
									<?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
										<div class="row">
											<div class="col-12">
												<div class="card-box">
		                        <h4 class="header-title mt-0 m-b-30"><?php echo $_SESSION['identity_name']; ?> <?php if ($_SESSION['identity_supervisor'] === "Yes"): ?><small>
		                                <font color="white"><i>Supervisor</i></font>
		                            </small><?php endif; ?></h4>
		                        <?php if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings === 'true'): ?>
		                        <a href="dispatch.php?v=main"><button class="btn btn-info btn-sm">Volver al panel</button></a>
		                        <?php endif; ?>
		                    </div>
											</div>
										</div>
										<div class="row">
											<div class="col-7">
												<div class="card-box">
                        	<h4 class="header-title mt-0 m-b-30">All LEO Identities</h4>
													<table id="datatable" class="table table-borderless">
														<thead>
	                            <tr>
	                                <th>Nombre</th>
	                                <th>Supervisor</th>
	                                <th>Usuario</th>
	                                <th>Estado</th>
	                                <th>Acciones</th>
	                            </tr>
                            </thead>
														<tbody>
															<?php
															$sql             = "SELECT * FROM identities WHERE department='Dispatch'";
															$stmt            = $pdo->prepare($sql);
															$stmt->execute();
															$idsRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

															foreach ($idsRow as $identity) {
															?>
															<tr>
																<td><?php echo $identity['name']?></td>
																<td><?php echo $identity['supervisor']?></td>
																<td><?php echo $identity['user_name']?></td>
																<td><?php echo $identity['status']?></td>
																<td><a href="dispatch.php?v=supervisor&a=edit-id&id=<?php echo $identity['identity_id']?>"><input type="button" class="btn btn-sm btn-success btn-block" value="Edit"></a></td>
															</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
											</div>
											<div class="col-5">
		                    <div class="card-box">
	                        <h4 class="header-title mt-0 m-b-30">Personajes pendientes</h4>
	                        <div id="getPendingIds"></div>
		                    </div>
			                </div>
										</div>
									<?php else: ?>
										<div class="alert alert-danger" role="alert">
				                No sos supervisor.
				            </div>
									<?php endif; ?>
								<?php break; ?>

                <?php endswitch; ?>
            </div>
        </div>
        <!-- CONTENT END -->
        <?php include 'inc/copyright.php'; ?>
        <?php include 'inc/page-bottom.php'; ?>
