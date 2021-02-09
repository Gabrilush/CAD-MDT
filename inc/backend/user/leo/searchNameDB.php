<?php
    session_name('hydrid');
    session_start();
    require '../../../connect.php';
    require '../../../config.php';
    require '../../../backend/user/auth/userIsLoggedIn.php';

    // Makes sure the person actually has a character set
    if (!isset($_SESSION['identity_name'])) {
      header('Location: ../../../../' . $url['leo'] . '?v=nosession');
      exit();
    }


    // Gets the characters ID that should be searched
    $charID = strip_tags($_GET['id']);
    // Selects the Character from Character Table
    $charTable = $pdo->prepare("SELECT * FROM characters WHERE character_id=?");
    $charTable->execute([$charID]);
    $character = $charTable->fetch();

    $charTickets = $pdo->prepare("SELECT * FROM tickets WHERE suspect_id=?");
    $charTickets->execute([$charID]);
    $characterTickets = $charTickets->fetchAll();

    $charArrests = $pdo->prepare("SELECT * FROM arrest_reports WHERE suspect_id=?");
    $charArrests->execute([$charID]);
    $characterArrests = $charArrests->fetchAll();

    $charWanted = $pdo->prepare("SELECT * FROM warrants WHERE wanted_person_id=?");
    $charWanted->execute([$charID]);
    $characterWarrants = $charWanted->fetchAll();

    if (!empty($characterWarrants)) {
      echo '<div class="alert alert-danger" role="alert">ESTA PERSONA ESTÁ SIENDO BUSCADA, PROCEDA CON PRECAUCIÓN.</div>';
    }

    echo '
    <div class="row">
      <div class="col-6">
        <h4 class="header-title mt-0 m-b-30">INFORMACIÓN DE PERSONA</h4>
        <hr />
        <h5>NOMBRE: '.$character['first_name'].' '.$character['last_name'].'</h5>
        <h5>GÉNERO: '.$character['sex'].'</h5>
        <h5>RAZA: '.$character['race'].'</h5>
        <h5>FECHA DE EMISIÓN: '.$character['date_of_birth'].'</h5>
        <h5>DIRECCIÓN DE RESIDENCIA: '.$character['address'].'</h5>

        <h5>ALTURA / PESO: '.$character['height'].' '.$character['weight'].'</h5>
        <h5>COLOR DE CABELLO: '.$character['hair_color'].'</h5>
        <h5>COLOR DE OJOS: '.$character['eye_color'].'</h5>
      </div>

      <div class="col-6">
        <h4 class="header-title mt-0 m-b-30">INFORMACIÓN DMV</h4>
        <hr />
        <h5>LICENCIA DE CONDUCIR: '.$character['license_driver'].'</h5>
        <h5>PERMISO DE ARMAS: '.$character['license_firearm'].'</h5>
      </div>
    </div><br />
    <div class="row">
      <div class="col-12">
        <h4 class="header-title mt-0 m-b-30">HISTORIAL DE ANTECEDENTES</h4>
        <hr />';
        if (empty($characterTickets)) {
        	echo 'No hay historial.';
        } else {
          echo '<table class="table table-borderless">
                  <thead>
                    <tr>
                        <th>ANTECEDENTE</th>
                        <th>DESCRIPCIÓN</th>
                        <th>FECHA DE EMISIÓN</th>
                        <th>OFICIAL</th>
                    </tr>
                  </thead>
                  <tbody>';
        	foreach($characterTickets as $ticket) {
        		echo '<tr>
                    <td>' . $ticket['amount'] . '</td>
                    <td>' . $ticket['reasons'] . '</td>
                    <td>' . $ticket['ticket_timestamp'] . '</td>
                    <td>' . $ticket['officer'] . '</td>
                </tr>';
        	}
        	echo '</tbody>
              </table>';
        }

      echo '</div>
      </div><br />

      <div class="col-12">
        <h4 class="header-title mt-0 m-b-30">HISTORIAL DE ARRESTOS</h4>
        <hr />';
        if (empty($characterArrests)) {
        	echo 'No posee historial de arrestos.';
        } else {
          echo '<table class="table table-borderless">
                  <thead>
                    <tr>
                        <th>OFICIAL</th>
                        <th>FECHA DE REGISTRO</th>
                        <th>SUMARIO</th>
                    </tr>
                  </thead>
                  <tbody>';
        	foreach($characterArrests as $arrest) {
        		echo '<tr>
                    <td>' . $arrest['arresting_officer'] . '</td>
                    <td>' . $arrest['timestamp'] . '</td>
                    <td><details>
                      <summary>Ver</summary>
                      ' . $arrest['summary'] . '
                    </details></td>
                </tr>';
        	}
        	echo '</tbody>
              </table>';
        }

      echo '</div>
    </div><br />

    <div class="row">
      <div class="col-12">
        <h4 class="header-title mt-0 m-b-30">ALL POINTS BULLETIN</h4>
        <hr />';
        if (empty($characterWarrants)) {
          echo '<div class="alert alert-success" role="alert">No hay alertas activas.</div>';
        } else {
          echo '<table class="table table-borderless">
                  <thead>
                    <tr>
                        <th>FECHA DE EMISIÓN</th>
                        <th>EMITIDO POR</th>
                        <th>RAZÓN</th>';
                        if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings) {
                          echo '<th>Acciones</th>';
                        }
                        echo '
                    </tr>
                  </thead>
                  <tbody>';
          foreach($characterWarrants as $warrant) {
            echo '<tr>
                    <td>' . $warrant['issued_on'] . '</td>
                    <td>' . $warrant['signed_by'] . '</td>
                    <td>' . $warrant['reason'] . '</td>';
                    if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings) {
                      echo '<td><input type="button" class="btn btn-danger btn-sm" name="deleteWarrant" value="Eliminar APB" id='.$warrant['warrant_id'].' onclick="deleteWarrantLEO(this)"></td>';
                    }
                    echo '
                </tr>';
          }
          echo '</tbody>
              </table>';
        }

      echo '</div>
    </div>
    ';
