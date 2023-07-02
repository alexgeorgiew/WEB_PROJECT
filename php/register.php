<?php

    function create_database_table(){
        $servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "myDB";

        $conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		  //die("Connection failed: " . $conn->connect_error);
		  //return some value to know what happen
		}

		// sql to create table
		$sql = "CREATE TABLE Registration (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		first_name VARCHAR(50) NOT NULL,
		last_name VARCHAR(50) NOT NULL,
		db_password VARCHAR(50) NOT NULL,
		email VARCHAR(50) NOT NULL
		)";

		if ($conn->query($sql) === TRUE) {
// 		  echo "Table Registration created successfully";
		  //echo "\n";
		  //return some value to know what happen
		} else {
// 		  echo "Error creating table: " . $conn->error;
		  //echo "\n";
		  //return some value to know what happen
		}
		//return some value to know what happen
		$conn->close();
    }

    function registration($first_name, $last_name, $db_password, $email) {

    $servername = "localhost";
    		$username = "root";
    		$password = "";
    		$dbname = "myDB";


    $conn = new mysqli($servername, $username, $password, $dbname);

    $count = "SELECT * from Registration where email like '{$email}'";

    $result = $conn->query($count);
    if($result->num_rows !== 0) {
        echo "Email already exists";
    }
    else {
        $sql = "INSERT into Registration (first_name, last_name, db_password, email) VALUES('{$first_name}', '{$last_name}', '{$db_password}', '{$email}')";
        $conn->query($sql);
        echo "Registration successful!";

    }
    $conn->close();
    }

    create_database_table();

    if($_POST['register']=="register_btn")
    {
        registration($_POST['first_name'], $_POST['last_name'],$_POST['password'],$_POST['email']);
    }
?>