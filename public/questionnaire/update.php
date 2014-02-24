<?php
$arr =($_POST['myData']) ;
$arrlog =($_POST['myLog']) ;
$formid=($_POST['EditID']);


include("connect.php");
mysql_select_db($dbname, $connect);
mysql_query("SET NAMES UTF8") ;




//$strForm .= "<"input type = hidden" value="" >";//
$strForm = "<div class=\"wrapper\">";//
$strForm .= "<div class=\"header\">";
$strForm .= "<img style=\"float: left;\" border='0' alt=\"title\" src=\"./Form_files/title_pic.png\">";
$strForm .= "<div class=\"form_title\" style=''>$arr[form_title]</div>";
$strForm .= "<div class=\"form_description\" style=''>$arr[form_description]</div>";
$strForm .= "</div>";
$strForm .= "<div class=\"container\">";//
$strForm .= "<div class=\"field_container\">";//

$strlabel = "" ;

$count = 0 ;
foreach($arr['field_configs'] as $data)
{  $count++;
    

    if($data['field_type'] == 'text')
    {
          $strForm .= "<div class=field_preview>";
         $strForm .= "<div class=label>$data[field_label]</div>";
         $strForm .= "<div class=\"input_container\">";
         $strForm .= "<input type=\"text\" name=\"$data[field_type]$count\" value=\"$data[field_value]\" id=\"$data[field_require]\" size=\"20 \" required >";
         $strForm .= "</div>";
         $strForm .= "</div>";
	
	$strlabel .="<td class=\"center\" >$data[field_label]</td>";
         
       
    }
    if($data['field_type'] == 'paragraph_text')
    {
          $strForm .= "<div class=field_preview>";
         $strForm .= "<div class=label>$data[field_label]</div>";
         $strForm .= "<div class=\"input_container\">";
         $strForm .= "<textarea  class=\"$data[field_size]\" name=\"$data[field_type]$count\"  required></textarea>";
         $strForm .= "</div>";
         $strForm .= "</div>";

	$strlabel .="<td class=\"center\" >$data[field_label]</td>";

         
    }
    if($data['field_type'] == 'multiple_choice')
    {   
       
         $strForm .= "<div class=field_preview>";
         $strForm .= "<div class=label>$data[field_label]</div>";
         $strForm .= "<div class=\"input_container\">";
         $strForm .= "<div>";
         foreach($data['field_value'] as $key=>$datadiv){
                
             $strForm .= "<div align=\"left\">";
             $strForm .= "<label>";   
             $strForm .= "<input type=\"radio\" name=\"$data[field_type]$count\" value=\"$datadiv[value]\"  ";
             if($key=='0'&&$datadiv['selected']==true){
             
                 $strForm .="checked=\"$datadiv[selected]\" "  ;
             }
             $strForm .= ">$datadiv[value]";              
             $strForm .="</label>";
             $strForm .= "</div>";
         }
         $strForm .= "</div>";
         $strForm .= "</div>";
         $strForm .= "</div>";

	$strlabel .="<td class=\"center\" >$data[field_label]</td>";

    }
    if($data['field_type'] == 'drop_down')
    {
          $strForm .= "<div class=field_preview>";
         $strForm .= "<div class=label>$data[field_label]</div>";
         $strForm .= "<div class=\"input_container\">";
         $strForm .= "<select name=\"$data[field_type]$count\">";
         foreach($data['field_value'] as $key=>$datadiv){
                 
                 $strForm .= "<option  value=\"$datadiv[value]\" ";
                 if($key=='0'&&$datadiv['selected']==true){
             
                 $strForm .="selected=\"$datadiv[selected]\" "  ;
             }
                 
                 $strForm .= ">$datadiv[value]</option>";
        
         }
         $strForm .= "</select>";
         $strForm .= "</div>";
         $strForm .= "</div>";

	$strlabel .="<td class=\"center\" >$data[field_label]</td>";
  
        
    }
    if($data['field_type'] == 'checkboxes')
    {
          $strForm .= "<div class=field_preview>";
         $strForm .= "<div class=label>$data[field_label]</div>";
         $strForm .= "<div class=\"input_container\">";
         $strForm .= "<div>";
         foreach($data['field_value'] as $key=>$datadiv)
         {
                 $num++;
                 $au ="_";
             $strForm .= "<div align=\"left\">";
             $strForm .= "<label>";   
             $strForm .= "<input type=\"checkbox\" name=\"$data[field_type]$count$au$num\" value=\"$datadiv[value]\" >$datadiv[value]</label>";
             $strForm .= "</div>";

        	$strlabel .="<td class=\"center\" >$data[field_label]</td>";
        
         }
         $strForm .= "</div>";
         $strForm .= "</div>";
         $strForm .= "</div>";
        
    }

    if($data['field_type'] == 'likert')
    {
         $strForm .= "<div class=field_preview>";
         $strForm .= "<div class=label>$data[field_label]</div>";
         $strForm .= "<div class=\"input_container\">";
         $strForm .= "<table class=\"$data[field_type]\" cellspacing=0 cellpadding=0 border=0>";
         $strForm .= "<thead>";
         $strForm .= "<tr class=\"likert-header\" >";
         $strForm .= "<td statement=\"statement\" ></td> ";
         foreach($data['field_value'] as $key=>$result){
                       
                   foreach($result as $datachoices){
                       if($key == 'choices'){
                        $strForm .="<td valign=\"middle\"  align=\"center\" choice=\"choice\">$datachoices[value]</td>";
                       }

                   }
                      
                   
         }
         $strForm .= "</tr>";
         $strForm .= "</thead>";
         $strForm .= "<tbody>";
         $num1=0;
         foreach($data['field_value']['statements'] as $datastatements)
         {
              $num1++ ;
              $au="_";
                $strForm .="<tr class=\"odd\">" ;
                $strForm .="<td valign=\"middle\"  align=\"left\" statement=\"statement\">$datastatements[value]</td>" ;
                foreach($data['field_value']['choices'] as $datachoices)
                {
                  
                    $strForm .="<td valign=\"middle\" align=\"center\" \">";
                    $strForm .="<input type=\"radio\" name=\"$data[field_type]$count$au$num1\" value=\"$datachoices[value]\" required></td>";
                    $strForm .="</td>" ;
                    
                }
                $strForm .="</tr>" ;
		
		$strlabel .="<td class=\"center\" >$datastatements[value]</td>";
                
         }
         
         $strForm .= "</tbody>";
         $strForm .= "</table>";
         $strForm .= "</div>";
         $strForm .= "</div>";
       
        
    }
   
}

   
  

$strForm .= "</div>";

$strForm .="<div class=\"button_container\">";
$strForm .="<input type=\"submit\" class=\"submit_button\" value=\"Submit\" onclick=\"return confirm('ยืนยันการส่งขอมูล ?');\" />";
$strForm .="<input type=\"reset\" class=\"reset_button\" value=\"Reset\" onclick=\"return confirm('คุณแน่ใจหรือว่าต้องการ Reset data ?');\"/>";
$strForm .="</div>";
$strForm .= "</div>";
$strForm .= "</div>";


$form_log = stripslashes($arrlog);

$form_label1 = htmlentities($strlabel, ENT_QUOTES, "UTF-8");
$form_log1 = htmlentities($form_log, ENT_QUOTES, "UTF-8");
$form_text = htmlentities($strForm, ENT_QUOTES, "UTF-8");




 $sqlcount="SELECT count(*) data from data$formid";
 $count=mysql_query($sqlcount,$connect) ;
 $arr_count = mysql_fetch_array($count);

if($arr_count['data'] > 0)
{
    $save="INSERT INTO main_form (form_id,form_name,form_topic,form_decription,form_text,form_log,form_label) VALUES (NULL ,\"$arr[form_name]\",\"$arr[form_title]\",\"$arr[form_description]\",\"$form_text\",\"$form_log1\",\"$form_label1\")" ;
    $status_update = mysql_query($save, $connect);

    $rs = mysql_query("SELECT form_id FROM main_form order by form_id desc ",$connect);
    $result_arr = mysql_fetch_array($rs);
    $last_id = $result_arr['form_id'];
    
    $createform ="CREATE TABLE data$last_id ( dataID int auto_increment,";
}
else
{
    $save="UPDATE main_form SET form_name='$arr[form_name]',form_topic='$arr[form_title]',form_decription='$arr[form_description]',form_text='$form_text',form_log='$form_log1',form_label='$form_label1' WHERE form_id='$formid'" ;
    //echo $save ;
    $status_update = mysql_query($save, $connect);

    $dorp="DROP TABLE data$formid";
    mysql_query($dorp,$connect) ;

    $rs = mysql_query("SELECT form_id FROM main_form order by form_id desc ",$connect);
    $result_arr = mysql_fetch_array($rs);
    $last_id = $result_arr['form_id'];
    
    $createform ="CREATE TABLE data$formid ( dataID int auto_increment,";
}

if ($status_update)
{   
    
    $count1 = 0 ;
    foreach($arr['field_configs'] as $data)
    {  $count1++ ;
    

        if($data['field_type'] == 'text')
        {
         
            $field = "$data[field_type]$count1";
            $createform .="$field varchar(255),";

        }
        if($data['field_type'] == 'paragraph_text')
        {
         
            $field = "$data[field_type]$count1";
            $createform .="$field varchar(255),";
        }
        if($data['field_type'] == 'multiple_choice')
        {   

            $field = "$data[field_type]$count1";
            $createform .="$field varchar(255),";
        }
        if($data['field_type'] == 'drop_down')
        {
         
            $field = "$data[field_type]$count1";
            $createform .="$field varchar(255),";
        
        }
        if($data['field_type'] == 'checkboxes')
        {
             $numc=0;
            foreach($data['field_value'] as $key=>$datadiv)
            {
              $numc++;
              $au="_" ;
              $field = "$data[field_type]$count1$au$numc";
              $createform .="$field varchar(255),";
        
            }  
        }

        if($data['field_type'] == 'likert')
        {
            $num1=0 ;
            foreach($data['field_value']['statements'] as $datachoices)
            {
              $num1++ ;
              $au1="_" ;
                $field = "$data[field_type]$count1$au1$num1";
                $createform .="$field varchar(255),";    
                
            }
               
        }
   
    }


    $createform .="PRIMARY KEY ( dataID ))";

   // echo $createform ;
    $sql= $createform ;
    if (mysql_query($sql, $connect))
    {
        echo "1";
    }
    else
    {        
        echo "0";
    }

}
else
{
    echo "error";
}
mysql_close($connect);


?>