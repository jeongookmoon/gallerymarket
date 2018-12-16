<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    // echo session_id();
    // echo "<br>";
    // echo ini_get('session.cookie_domain');
    // echo "<br>";
    if (isset($_POST['abstract'])) {
        header("Location: ../gallery.php?genre=abstract");
        exit();
    } else if (isset($_POST['modernart'])) {
        header("Location: ../gallery.php?genre=modernart");
        exit();
    } else if (isset($_POST['impressionist'])) {
        header("Location: ../gallery.php?genre=impressionist");
        exit();
    } else if (isset($_POST['popart'])) {
        header("Location: ../gallery.php?genre=popArt");
        exit();
    } else if (isset($_POST['cubism'])) {
        header("Location: ../gallery.php?genre=cubism");
        exit();
    } else if (isset($_POST['contemporary'])) {
        header("Location: ../gallery.php?genre=contemporary");
        exit();
    } else if (isset($_POST['fantasy'])) {
        header("Location: ../gallery.php?genre=fantasy");
        exit();
    } else if (isset($_POST['photo'])) {
        header("Location: ../gallery.php?genre=photo");
        exit();
    } else if (isset($_POST['graffiti'])) {
        header("Location: ../gallery.php?genre=graffiti");
        exit();
    } else if (isset($_POST['all'])) {
        header("Location: ../gallery.php");
        exit();
    }
?>