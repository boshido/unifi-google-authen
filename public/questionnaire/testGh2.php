<?
$log_txt = array();
// $log_txt = array(
// 	"Paragraph Text" ,
// 	"Text" ,
// 	"Multiple Choices" = array(
// 				    "choice1" ,
// 				    "choice2" ,
// 				    "choice3"
// 				    ) ,
// 	"Likert" => array(
// 			   "row1" => array(
// 						"col1",
// 						"col2",
// 						"col3"
// 					   ),
// 			   "row2" => array(
// 						"col1",
// 						"col2",
// 						"col3"
// 					  ),
// 			   "row3" => array(
// 						"col1",
// 						"col2",
// 						"col3"
// 				           )
// 			 )
// );

$log_txt[] = "Paragraph Text";
$log_txt[] = "Text";

$log_txt["Multiple Choices"] = array(	
										"choice1" ,
									 	"choice2" ,
									 	"choice3" 
									 	);

$log_txt["Table name"] = array(
			   "row1" => array(
						"col1",
						"col2",
						"col3"
					   ),
			   "row2" => array(
						"col1",
						"col2",
						"col3"
					  ),
			   "row3" => array(
						"col1",
						"col2",
						"col3"
				           )
			 );

$log_txt[] = "Text 2";

echo "<pre>";
print_r($log_txt);
echo "</pre>";

echo "<hr>";
echo json_encode($log_txt);
?>