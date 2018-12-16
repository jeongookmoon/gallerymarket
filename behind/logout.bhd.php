<?php
    session_start();
    // clear sessions;
    session_unset();
    session_destroy();
    header("Location: ../index.php");
?>