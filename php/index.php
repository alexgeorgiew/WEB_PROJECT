<?php
      
        function export() {
			    
			$htmlContent = file_get_contents("http://localhost/Project/index.html");
				
			$DOM = new DOMDocument();
			$DOM->loadHTML($htmlContent);
			
			$Header = $DOM->getElementsByTagName('th');
			$Detail = $DOM->getElementsByTagName('td');

		       //#Get header name of the table
			foreach($Header as $NodeHeader) 
			{
				$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
			}
			//print_r($aDataTableHeaderHTML); die();

			//#Get row data/detail table without header name as key
			$i = 0;
			$j = 0;
			foreach($Detail as $sNodeDetail) 
			{
				$aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
				$i = $i + 1;
				$j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
			}
			//print_r($aDataTableDetailHTML); die();
			
			//#Get row data/detail table with header name as key and outer array index as row number
			for($i = 0; $i < count($aDataTableDetailHTML); $i++)
			{
				for($j = 0; $j < count($aDataTableHeaderHTML); $j++)
				{
					$aTempData[$i][$aDataTableHeaderHTML[$j]] = $aDataTableDetailHTML[$i][$j];
				}
			}
			$aDataTableDetailHTML = $aTempData; unset($aTempData);
			//print_r($aDataTableDetailHTML);
			
			
			
			    $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
			    $results = print_r($aDataTableDetailHTML, true);
			    fwrite($myfile, $results);
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
        
        function get_data_from_database($min_price_per_day = "default", $max_price_per_day = "default", $min_memory =  "default",$max_memory =  "default", $regions_names = "default"){
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
			
			
			$query = "SELECT *, ((day_cost - month_cost) / month_cost) as percentage_change_month ,((day_cost - year_cost) / year_cost) as percentage_change_year FROM Services WHERE day_cost > '{$min_price_per_day}' AND day_cost < '{$max_price_per_day}' AND memory > '{$min_memory}' AND memory < '{$max_memory}' AND region = '{$regions_names}'";
			
			
			if($regions_names == "default"){
			 $query = "SELECT *, ((day_cost - month_cost) / month_cost) as percentage_change_month ,((day_cost - year_cost) / year_cost) as percentage_change_year FROM Services";
			}
			
			$result = $conn->query($query);

			if ($result->num_rows > 0) {
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
			
			$query="SELECT name,day_cost,month_cost,year_cost  FROM Services";
			$result = $conn->query($query);

			if ($result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
			  	$row_data= $row["name"] . "\n" . $row["day_cost"] . "\n" . $row["month_cost"] . "\n" . $row["year_cost"] ;
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
        	
        	if($correct == "1")get_data_from_database($_POST['min_price_per_day'],$_POST['max_price_per_day'],$_POST['min_memory'],$_POST['max_memory'],$_POST['regions_names']);
        }
        else if($_POST['export']=="export_btn")
        {
        	export();
        }
        else if($_POST['import']=="import_btn")
        {
         	get_data_from_database();
        }
        else if($_POST['news']=="news_btn")
        {
        	get_information_for_news();
        }
        
        
        
    ?>
