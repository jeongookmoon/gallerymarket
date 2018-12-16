<?php
	$servername = 'localhost';
	$dbusername = 'jim';
	$dbpassword = 'mypasswd';
    $dbname = 'publications';
    
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

    function get_post($conn, $var) {
        return $conn->real_escape_string($_POST[$var]);
    }
    
    function hs_password($var) {
        return hash('ripemd128', 'eq*@&'.$var.'2R&1');
    }
?>