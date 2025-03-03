<?php
    /*When a user presses logout we have to start a session in here in order 
    to abort it*/
    session_start();
    session_unset();
    session_destroy();

    header("location: ../main/index.php?error=none");
?>