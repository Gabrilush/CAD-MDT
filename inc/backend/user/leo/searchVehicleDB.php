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

    // Page PHP
        $q      = strip_tags($_GET['id']);
        $getVeh = "SELECT * FROM vehicles WHERE vehicle_id='$q'";
        $result = $pdo->prepare($getVeh);
        $result->execute();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<h5>Plate: " . $row['vehicle_plate'] . "</h5><br-leo-name-search>";
            echo "<h5>Color: " . $row['vehicle_color'] . "</h5><br-leo-name-search>";
            echo "<h5>Model: " . $row['vehicle_model'] . "</h5><br-leo-name-search>";
            echo "<h5>Insurance Status: " . $row['vehicle_is'] . "</h5><br-leo-name-search>";
            echo "<h5>Registration Status: " . $row['vehicle_rs'] . "</h5><br-leo-name-search>";
            echo "<h5>VIN: " . $row['vehicle_vin'] . "</h5><br-leo-name-search>";
            echo "<h5>Owner: " . $row['vehicle_ownername'] . "</h5><br-leo-name-search>";
            $plate = $row['vehicle_plate'];
            $stmt  = $pdo->prepare("SELECT * FROM bolos WHERE vehicle_plate =:veh_plate");
            $stmt->bindParam(':veh_plate', $row['vehicle_plate']);
            $stmt->execute();
            $bolosRows = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($bolosRows['bolo_id'])) {
                echo "<hr><h5>No Bolos On Vehicle</h5>";
            } else {
                $getVehBolo = "SELECT * FROM bolos WHERE vehicle_plate=:plate";
                $result     = $pdo->prepare($getVehBolo);
                $stmt->bindValue(':plate', $plate);
                $result->execute();
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<table>";
                    echo "<tr>";
                    echo "<td><center><font color='red'>" . $row['bolo_reason'] . "</font></center></td>";
                    echo "<td><center><font color='red'>" . $row['bolo_created_on'] . "</font></center></td>";
                    echo "</tr>";
                }

                echo "</table>";
            }
        }
