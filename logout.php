<?php
    session_name('hydrid');
    session_start();
    session_unset();
    session_destroy();
    header('Location: login.php?error=access');
    exit();
?>
