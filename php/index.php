<?php
      
        function export($text) {
			    $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
			    fwrite($myfile, $text);
			    fclose($myfile);
        }
        
        function create_database(){
        	$servername = "localhost";
		$username = "root";
		$password = "";

		// Create connection
		$conn = new mysqli($servername, $username, $password);
		// Check connection
		if ($conn->connect_error) {
		  //die("Connection failed: " . $conn->connect_error);
		  //return some value to know what happen
		}

		// Create database
		$sql = "CREATE DATABASE myDB";
		if ($conn->query($sql) === TRUE) {
		  //return some value to know what happen
		} else {
		 //return some value to know what happen
		}
		
		//return some value to know what happen
		
        }
        
        function create_database_table(){
        	$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "myDB";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		  //die("Connection failed: " . $conn->connect_error);
		  //return some value to know what happen
		}

		// sql to create table
		$sql = "CREATE TABLE Services (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50) NOT NULL,
		api_name VARCHAR(50) NOT NULL,
		memory VARCHAR(50) NOT NULL,
		vcpus VARCHAR(50) NOT NULL,
		storage VARCHAR(50) NOT NULL,
		day_cost  DOUBLE PRECISION(10,5) NOT NULL,
		month_cost DOUBLE PRECISION(10,5) NOT NULL,
		year_cost DOUBLE PRECISION(10,5) NOT NULL,
		region VARCHAR(50) NOT NULL
		)";

		if ($conn->query($sql) === TRUE) {
		  //echo "Table Services created successfully";
		  //echo "\n";
		  //return some value to know what happen
		} else {
		  //echo "Error creating table: " . $conn->error;
		  //echo "\n";
		  //return some value to know what happen
		}
		//return some value to know what happen
		$conn->close();
        }
        
        function get_csv_and_fill_database($conn)
        {
        
            $file = fopen('AmazonEC2InstanceComparison.csv', 'r');
            while (($data = fgetcsv($file)) !== false) {

            				$values = implode("','", $data);
            				$sql = "INSERT INTO Services (name, api_name,memory, vcpus, storage, day_cost, month_cost, year_cost, region) VALUES ('" . $values . "')";

            				if ($conn->query($sql) === true) {
//             					echo "Data inserted successfully";
                                                //return some value to know what happen
            				} 
            				else {
            					//echo "Error inserting data: " . $conn->error;
            					//return some value to know what happen
            				}
            }  
        }
        
        function get_file_data_and_insert_in_database($min_price_per_day = "default", $max_price_per_day = "default", $min_memory =  "default",$max_memory =  "default", $regions_names = "default"){
        		$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "myDB";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			  //die("Connection failed: " . $conn->connect_error);
			  //return some value to know what happen
			}
			
			$query="SELECT * FROM Services";
			$result = $conn->query($query);
			if($result->num_rows == 0)//when database is empty get information from csv file
			{
				get_csv_and_fill_database($conn);
			}
			
			
			$query = "SELECT *, (((day_cost - month_cost) / month_cost)*100) as percentage_change_month ,(((day_cost - year_cost) / year_cost)*100) as percentage_change_year FROM Services WHERE day_cost > '{$min_price_per_day}' AND day_cost < '{$max_price_per_day}' AND memory > '{$min_memory}' AND memory < '{$max_memory}' AND region = '{$regions_names}'";
			
			
			if($regions_names == "default"){
			 $query = "SELECT *, (((day_cost - month_cost) / month_cost)*100) as percentage_change_month ,(((day_cost - year_cost) / year_cost)*100) as percentage_change_year FROM Services";
			}
			
			$result = $conn->query($query);

			if ($result !== false && $result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
			  	$row_data= $row["name"] . "\n" . $row["api_name"] . "\n" . $row["memory"] . "\n" . $row["vcpus"] . "\n" . $row["storage"] . "\n" . $row["day_cost"] . "\n" . $row["month_cost"] . "\n" . $row["year_cost"] . "\n" . $row["region"] . "\n" . $row["percentage_change_month"] . "\n" . $row["percentage_change_year"] ;
			    echo $row_data;
			    echo "\n";
			  }
			}
			else {
			  echo "0 results";
			  echo "\n";
			}
			
			
			$conn->close();
        }
        
        function get_information_for_news()
        {
        	        $servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "myDB";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			  //die("Connection failed: " . $conn->connect_error);
			  //return some value to know what happen
			}

			$query = "SELECT name, day_cost, month_cost, year_cost  FROM Services ";

            		$resultSet = $conn->query($query);

			if($resultSet->num_rows > 0) {
				$sentence = array();

			    	while($row = $resultSet->fetch_assoc()) {
			        	$sentence = "The service " .$row['name'] . " has a price of " .$row['day_cost'] . ". A month ago the price was " .$row['month_cost'] . ". A year ago the price was " .$row['year_cost'] . ".\n";
			        echo $sentence;
			    	}
            		}
            		else echo "No news";
			
			$conn->close();
        }
        
        function clear_data_from_database()
        {
        	        $servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "myDB";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			  //die("Connection failed: " . $conn->connect_error);
			  //return some value to know what happen
			}
			
			$query="DELETE FROM Services";
			$conn->query($query);
			$conn->close();
        }
        
        
        
        
        
        create_database();
        create_database_table();
        
        if($_POST['wizard']=="wizard_btn")
        {
                $correct="1";
        	if($_POST['min_price_per_day'] == ""){
        		echo "Invalid minimum price per day\n";
        		$correct="0";
        	}
        	if($_POST['max_price_per_day'] == ""){
        		echo "Invalid maximum price per day\n";
        		$correct="0";
        	}
        	if($_POST['min_price_per_day'] == ""){
        		echo "Invalid minimum memory\n";
        		$correct="0";
        	}
        	if($_POST['min_price_per_day'] == ""){
        		echo "Invalid maximum mempry\n";
        		$correct="0";
        	}
        	
        	if($correct == "1")get_file_data_and_insert_in_database($_POST['min_price_per_day'],$_POST['max_price_per_day'],$_POST['min_memory'],$_POST['max_memory'],$_POST['regions_names']);
        }
        else if($_POST['export']=="export_btn")
        {
        	export($_POST['textfile']);
        }
        else if($_POST['import']=="import_btn")
        {
        	clear_data_from_database();
         	get_file_data_and_insert_in_database();
        }
        else if($_POST['news']=="news_btn")
        {
        	get_information_for_news();
        }
        else if($_POST['onlyget'] == "onlyget")
        {
        	get_file_data_and_insert_in_database();
        }
        
        
        
    ?>
