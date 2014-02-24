<?

include("connect.php");

mysql_select_db($dbname, $connect);
mysql_query("SET NAMES UTF8") ;  


 $id_form = $_REQUEST["formid"];

$sql="SELECT * FROM main_form WHERE form_id=$id_form";

$select = mysql_query($sql, $connect) ;

while($row = mysql_fetch_array($select))
{
    $text2 = $row['form_JS'];
}
	
$a = json_decode($text2,true) ;


	//echo "<pre>";
	//echo $text2;

	//print_r($a);
	//echo "</pre>";

$i=1 ;
$data2 = array();
foreach($a as $data)
{	
	if($data['field_type'] == 'text')
    {
		//echo "text".$i ;
		$i++;
	}
	if($data['field_type'] == 'paragraph_text')
    {
		//echo "paragraph_text".$i ;
		$i++;
	}
	if($data['field_type'] == 'multiple_choice')
    {	
    	$temp = array();
    	foreach($data['field_value'] as $key)
    	{
    		

	    	$sqlm = "select count(*) as amount from  `data$id_form` WHERE multiple_choice$i =  '$key[value]'" ;
	 
	    	$select = mysql_query($sqlm, $connect) ;
	    	
    		
			   while($row = mysql_fetch_array($select))
			   {
   				//$temp[] = array("text" => $key[value] , "value" => $row['amount']); 

   				// 				{
       //                          type: 'pie',
       //                          name: 'Browser share',
       //                          data: [
       //                                  ["test",30]
       //                          ]
       //                      }

   				   $temp[] = array($key[value] ,  intval($row['amount'])) ; 

   				//$number = 1+ $row['amount'] ;
   				//echo $number ;
   				
			   }  	 
    	}

    	$series = array("type" => "pie","name" => "multichoice","data" => $temp);
    	$data2[] = array("label"=>"$data[field_label]","type" => "multiple_choice" , "value" => $series);
    	
        $i++;                  
	}
	if($data['field_type'] == 'drop_down')
    {
    	$temp = array();
    	foreach($data['field_value'] as $key)
      	{ 
          $sqlm = "select count(*) as amount from  `data$id_form` WHERE drop_down$i =  '$key[value]'" ;
   
          $select = mysql_query($sqlm, $connect) ;
          while($row = mysql_fetch_array($select))
          {
         

            $temp[] = array($key[value] ,  intval($row['amount'])) ; 

          
          
          }                 
     	}
        $series = array("type" => "pie","name" => "drop_down","data" => $temp);
        $data2[] = array("label"=>"$data[field_label]","type" => "drop_down" , "value" => $series);
	
	   $i++;
	 }
	if($data['field_type'] == 'checkboxes')
    {	$j=1 ;
      
      $temp = array();
    	   foreach($data['field_value'] as $key){
        $sqlm = "select count(checkboxes".$i."_$j) as amount from  `data$id_form` WHERE checkboxes".$i."_$j != ' '   " ;
       
        $select = mysql_query($sqlm, $connect) ;
        while($row = mysql_fetch_array($select))
        {
         
        
           $tempstr[] = $key[value] ;

           $tempint[] = intval($row['amount']) ; 

          
          
        }
                  

        $j++;

      }
     
      $data2[] = array("label"=>"$data[field_label]","type" => "checkboxes" , "valuestr" =>$tempstr ,"valueint" =>$tempint );
	
	  $i++;
	
	}
	if($data['field_type'] == 'likert')
    {	
    	
		$j=1 ;
		
    	foreach($data['field_value']['statements'] as $key)
    	{
    		$y = 0 ;
    		$arrint = array();
             foreach($data['field_value']['choices'] as $key2)
            {
            	 $key3  = strip_tags($key2[value]); //ลบ tag html

              	$sqlm = "select count(likert".$i."_$j) as amount from  `data$id_form` WHERE likert".$i."_$j = '$key3'  " ;         
                $select = mysql_query($sqlm, $connect) ;
              
        		while($row = mysql_fetch_array($select))
        		{
         
        
           			$tempstr2 = $key3 ;
          			$tempint2 = intval($row['amount']) ;
        		} 
        		 
        		   $arrstr[$y]   = $tempstr2;
        		   $arrint[$y]   = $tempint2;
        		 //print_r($temp2[$y]) ;
        		 $y++ ;
            }    
              
                
          	$data2[] = array("label"=>"$key[value]","type" => "likert" , "valuestr" =>$arrstr,"valueint" =>$arrint );
           
          	$j++;  
        }
      	   
	
	$i++;
	}
	
}

  

  
echo json_encode($data2);

?>
