<?php

    function login($email, $db_password) {

            $servername = "localhost";
    		$username = "root";
    		$password = "";
    		$dbname = "myDB";


    $conn = new mysqli($servername, $username, $password, $dbname);

    $count = "SELECT * from Registration where email like '{$email}'";

    $result = $conn->query($count);
    if($result->num_rows !== 0) {
        $query = "SELECT * from Registration where email like '{$email}' and db_password like '{$db_password}'";
        $pass = $conn->query($query);

        if($pass->num_rows!==0) {
        echo "Login successful!";
        } else {
        echo "Email or password incorrect!";
        }

    }
    else {
        echo "Email or password incorrect1!";
    }
    $conn->close();
    }

if($_POST['login']=="login_form")
    {
        login($_POST['email'], $_POST['password']);
    }
?>