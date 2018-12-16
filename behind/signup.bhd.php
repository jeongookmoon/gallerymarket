<?php
    if (isset($_POST['createCustomer'])) {
        require 'dbhandler.php';
  
        $name = get_post($conn, 'name');
        $email = get_post($conn, 'email');
        $username = get_post($conn, 'username');
        $password = get_post($conn, 'password');
        $passwordRpt = get_post($conn, 'passwordRpt');
        $continent = get_post($conn, 'continent');
        $age = get_post($conn, 'age');
        //echo $continent;
        //echo $age;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)){
            header("Location: ../signup.php?error=invalid_email");
            exit();
        } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username) && !empty($email)){
            header("Location: ../signup.php?error=invalid_username");
            exit();
        } else if ($password !== $passwordRpt && !empty($email)){
            header("Location: ../signup.php?error=password_mismatch");
            exit();
        } else if(empty($name) || empty($email) ||empty($username) ||empty($password)) {
            header("Location: ../signup.php?error=emptyfields");
            exit();
        } else {
            $hashedPassword = hs_password($password);
            $query = "INSERT INTO customers VALUES('', '$name', '$email', '$username', '$hashedPassword', 0, '$continent', '$age', 0)";
            $result = $conn->query($query);
            if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
            header("Location: ../signup.php?result=success");
            exit();
        }
        $conn->close();
    } else {
        header("Location: ../signup.php");
        exit();
    }

?>