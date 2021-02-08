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
      echo '<div class="alert alert-danger" role="alert">This Person Is WANTED. Proceed with caution</div>';
    }

    echo '
    <div class="row">
      <div class="col-6">
        <h4 class="header-title mt-0 m-b-30">Person Info</h4>
        <hr />
        <h5>Name: '.$character['first_name'].' '.$character['last_name'].'</h5>
        <h5>Sex: '.$character['sex'].'</h5>
        <h5>Race: '.$character['race'].'</h5>
        <h5>Date of Birth: '.$character['date_of_birth'].'</h5>
        <h5>Address: '.$character['address'].'</h5>

        <h5>Height / Weight: '.$character['height'].' '.$character['weight'].'</h5>
        <h5>Hair Color: '.$character['hair_color'].'</h5>
        <h5>Eye Color: '.$character['eye_color'].'</h5>
      </div>

      <div class="col-6">
        <h4 class="header-title mt-0 m-b-30">License Info</h4>
        <hr />
        <h5>Drivers License: '.$character['license_driver'].'</h5>
        <h5>Firearm License: '.$character['license_firearm'].'</h5>
      </div>
    </div><br />
    <div class="row">
      <div class="col-6">
        <h4 class="header-title mt-0 m-b-30">Ticket History</h4>
        <hr />';
        if (empty($characterTickets)) {
        	echo 'No Tickets On File.';
        } else {
          echo '<table class="table table-borderless">
                  <thead>
                    <tr>
                        <th>Reason</th>
                        <th>Fine Amount</th>
                        <th>Timestamp</th>
                        <th>Officer</th>
                    </tr>
                  </thead>
                  <tbody>';
        	foreach($characterTickets as $ticket) {
        		echo '<tr>
                    <td>' . $ticket['reasons'] . '</td>
                    <td>' . $ticket['amount'] . '</td>
                    <td>' . $ticket['ticket_timestamp'] . '</td>
                    <td>' . $ticket['officer'] . '</td>
                </tr>';
        	}
        	echo '</tbody>
              </table>';
        }

      echo '</div>

      <div class="col-6">
        <h4 class="header-title mt-0 m-b-30">Arrest History</h4>
        <hr />';
        if (empty($characterArrests)) {
        	echo 'No Arrests On File.';
        } else {
          echo '<table class="table table-borderless">
                  <thead>
                    <tr>
                        <th>Officer</th>
                        <th>Timestamp</th>
                        <th>Summary</th>
                    </tr>
                  </thead>
                  <tbody>';
        	foreach($characterArrests as $arrest) {
        		echo '<tr>
                    <td>' . $arrest['arresting_officer'] . '</td>
                    <td>' . $arrest['timestamp'] . '</td>
                    <td>' . $arrest['summary'] . '</td>
                </tr>';
        	}
        	echo '</tbody>
              </table>';
        }

      echo '</div>
    </div><br />

    <div class="row">
      <div class="col-12">
        <h4 class="header-title mt-0 m-b-30">Warrants</h4>
        <hr />';
        if (empty($characterWarrants)) {
          echo '<div class="alert alert-success" role="alert">No Active Warrants</div>';
        } else {
          echo '<table class="table table-borderless">
                  <thead>
                    <tr>
                        <th>Issued On</th>
                        <th>Signed By</th>
                        <th>Reason</th>';
                        if ($_SESSION['identity_supervisor'] === "Yes" || staff_siteSettings) {
                          echo '<th>Actions</th>';
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
                      echo '<td><input type="button" class="btn btn-danger btn-sm" name="deleteWarrant" value="Delete Warrant" id='.$warrant['warrant_id'].' onclick="deleteWarrantLEO(this)"></td>';
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
