<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    // echo session_id();
    // echo "<br>";
    // echo ini_get('session.cookie_domain');
    // echo "<br>";
    if (isset($_POST['login_submit'])) {
        require 'dbhandler.php';

        $username = $_POST['login_uid'];
        //get_post($conn, 'login_uid');
        $password = $_POST['login_pwd'];
        //$password = get_post($conn, 'login_pwd');
        $hashedPassword = hs_password($password);
                
        //$statement = mysqli_stmt_init($conn);
        //$stmt = $conn->prepare("SELECT * FROM customers WHERE username=?");
        // run actual statement
        $sql = "SELECT * FROM customers where username=? OR email=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../index.php?result=sql_error");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $username, $username);
            mysqli_stmt_execute($stmt);
            // fetching data from result and convert it into usable form
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)) {
                if($hashedPassword == $row['password']) {
                    $_SESSION['customerID']=$row['customerID'];
                    $_SESSION['username']=$row['username'];
                    header("Location: ../index.php?result=login_success");
                    exit();
                } else {
                    header("Location: ../index.php?error=wrong_password");
                    exit();
                }
            } else {
                header("Location: ../index.php?error=no_such_user");
                exit();
            }
        }
        $conn->close();     
    }
?>