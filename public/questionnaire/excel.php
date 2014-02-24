<?php
$formid=$_GET["formid"];

$host="localhost";
$user="root";
$pass="root";
$dbname="querstion_questionnaire";
$connect = mysql_connect($host,$user,$pass);
mysql_select_db("querstion_questionnaire");
mysql_query("SET NAMES UTF8") ; 

require_once 'excel/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
// header table
$sql=" SELECT * FROM main_form WHERE form_id =form_id";
$select = mysql_query($sql, $connect) ;
 
$count = 0;
$objPHPExcel->setActiveSheetIndex(0);
while($row = mysql_fetch_array($select))
  {
      
  	$text2 = $row['form_question'];
 
      

  }
$pieces = explode("/", $text2);
for($i=0; $i<count($pieces); $i++)
{
	$objPHPExcel->getActiveSheet()->setCellValue($chars[$count++]."1", $pieces[$i]);

}

// data row
$data = 0;
$i = 2;

mysql_query("SET NAMES UTF8") ; 
$result = mysql_query("select * from data$formid");
while($row = mysql_fetch_array($result))
{   
    
	
    
    for($j=0;$j<$count;$j++)
    {
     $objPHPExcel->getActiveSheet()->setCellValue($chars[$data++].$i, $row[$j]);                               
    }
     
	$i++ ;
    $data = 0;
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');

$sql=" SELECT form_name FROM main_form WHERE form_id =$formid";

  $select = mysql_query($sql, $connect) ;
  while($row = mysql_fetch_array($select))
    {
         $strname = $row['form_name'] ;
        
    }


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/x-msexcel;');
header("Content-Disposition: inline;filename=$strname.xls");
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
 ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header("Pragma: no-cache");


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;


?>

