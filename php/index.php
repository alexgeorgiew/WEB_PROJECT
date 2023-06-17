<?php
        function wizard() {
            			echo "This is WIZARD BTN that is selected";
        }
        function export() {
			    echo "This is Export BTN that is selected";
			    
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
        
        if($_POST['wizard']=="wizard_btn")
        {
        	wizard();
        }
        
        if($_POST['export']=="export_btn")
        {
        	export();
        }
    ?>
