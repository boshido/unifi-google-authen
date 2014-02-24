<?php
session_start();
if($_SESSION["valid"]=='yes')
{

include("connect.php");

if($connect)
{
    //echo "Database Connected.";
}
else
{
    echo "Database Connect Failed.";
}

mysql_select_db($dbname, $connect);
mysql_query("SET NAMES UTF8") ;  

$sql=" SELECT form_name,form_id FROM main_form";

  $select = mysql_query($sql, $connect) ;

  $show ="";
  while($row = mysql_fetch_array($select))
      {
    //echo $arr[form_name];
    $show .="<div class=\"form_item\" theme=\"default\" name=\"$row[form_name]\" date=\"1378519606\" formid=\"11411\">";
    $show .="<div class=\"bg_orange trim_7\" align=\"left\" select=''>";
    $show .="<table cellspacing='0' cellpadding='' style=\"padding-left: 10px; padding-right: 10px; width: 100%;\">";
    $show .="<tbody>";
    $show .="<tr valign=\"top\">";
    $show .="<td onclick=\"form_click(1, $(this).parents('.bg_orange:first'));\" style=\"cursor: pointer; line-height: 35px;\">";
    $show .="<b class=\"icon icon_entry\" style=\"margin:10px 5px 0px 0px; padding: 0px;\"></b>";
    $show .="<span class=\"form_name\">$row[form_name]</span>";
    $show .="<a style=\"margin-left: 10px;\" href=\"Entry.php?formid=$row[form_id]\" >Form Entry</a>";
    $show .="<a style=\"margin-left: 10px;\" href=\"showform.php?formid=$row[form_id]\" target=\"_blank\">ดูแบบฟอร์มนี้</a>";
    $show .="<div class=\"seperator\"></div>";
    $show .="</td>";

    $show .="<td width=\"200px\" >";
    $show .="<div class=\"seperator\"></div>";   
    $show .="<b class=\"pie_chart_red\"style=\"margin:5px 10px  0px; padding: 0px; \"></b>";
    $show .="<a href=\"drawChart.php?formid=$row[form_id]\" target=\"_blank\" >Graph</a>"; 
    $show .="</td>";



    $show .="<td width=\"230px\" >";
    $show .="<div class=\"seperator\"></div>";   
    $show .="<b class=\"icon icon_embeded\"style=\"margin:10px 10px  0px; padding: 0px; \"></b>";
    $show .="<a href=\"saveform.php?formid=$row[form_id]\" target=\"_blank\" >Embeded Code</a>"; 
    $show .="</td>";
  
     

    $show .="<td width=\"150px\">";
    $show .="<div class=\"seperator\"></div>";
    $show .="<div style=\"float: left; cursor: default; padding-left: 15px; \">" ;
    $show .="<a class=\"form_edit\" href=\"edit.php?formid=$row[form_id]\">";
    $show .="<div style=\"margin-top: -5px;\">แก้ไข</div>";
    $show .="</a>";
    $show .="<a class=\"form_delete\" href=\"deleteform.php?formid=$row[form_id]\"onclick=\"return confirm('คุณแน่ใจว่าจะลบ Form นี้หรือไม่ ?');\" >" ;
    $show .="<div style=\"margin-top: -5px;\">ลบ</div>";
    $show .="</a>";
    $show .="</div>";
    $show .="</td>";
    $show .="</tr>";
    $show .="</tbody>";
    $show .="</table>";
    $show .="</div>";
    $show .="</div>";

    $show .="<br>";
  }
 
  mysql_close($connect);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="LnwForm" />



<script type="text/javascript" async="" src="./Form_files/ga.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/jquery-1.4.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/jquery-ui-1.8.2.custom.min.js"></script>



<script language="JavaScript" type="text/javascript">
function base_url (uri) {return "http://www.lnwform.com/"+(uri===undefined?"":uri);}
function site_url (uri) {return "http://www.lnwform.com/"+(uri===undefined?"":uri);}
function template_url (uri) {return "http://www.lnwform.com/system/application/views/templates/lnwform/default"+(uri===undefined?"":uri);}
$(document).ready(function(){
	$.ajaxSetup({
		dataType: "html",
		cache: false
	});
	$(this).ajaxSend(function(e, xhr, settings) {
		settings.data += '&ajaxxxx=true';
	});
});
</script>

<link rel="stylesheet" type="text/css" href="./Form_files/common.css">
<link rel="stylesheet" type="text/css" href="./Form_files/style.css">
<link rel="stylesheet" type="text/css" href="./Form_files/lnwbox.css">
<link rel="stylesheet" type="text/css" href="./Form_files/y2013.css">
<link rel="stylesheet" type="text/css" href="./Form_files/jPicker.css">
<link rel="stylesheet" type="text/css" href="./Form_files/style(1).css">
<link rel="stylesheet" type="text/css" href="./Form_files/form.css">
<script language="JavaScript" type="text/javascript" src="./Form_files/config.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/element.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/choice.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/radio.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/lnwform.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/right_menu.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/jquery.lnwbox.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/library.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/json.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/jpicker-1.0.4.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/jquery.lnwajax.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/action.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/jquery.cookie.js"></script>
<script language="JavaScript" type="text/javascript">
var LNWACCOUNTS_COOKIE_DATA = 'UXgCZFN0VTBRbQI2A2wKfAUiUTQFblNpXWxeMlx0UwsEIlU1UmFVWQc_UTIBcgU9VyxaM1RnAS5Xfl15AWUDPFdqXjlVZ1AjAA5XIQA0BSVRcwJsU21VOVFXAjADZgolBTpRdQU6U2VdaF5sXDBTZgRhVWRSYVVjBzVRYgE0BTZXN1pkVGUBOVczXWwBZANkVzZeb1U9UG8AYFcwAGEFNVE3AjVTIFV7USoCOANhCmQFb1EiBWxTdF1WXjVcZFN2BG9VclI2VT8HZlFuAWAFN1csWi1UdwFTVyddKwFiAzFXd145VW1QCAAlVzsAPAUzUSICP1NsVSJRZAI1Ay4KJQV0UT4Fb1NlXXteflw6U2UEZlVnUjtVNwdjUWUBaQU_VzlaLVR3AWlXKl0vAXQDMVchXmZVZ1AiAD1XPgAs';
</script>
<!--[if IE]>
	<style type="text/css">
		body { font-size: 11px; }
	</style>
<![endif]-->
</head>

<body style="margin: 0px;">
    <div id="lb" >
        <div id="lbWrapper">
      <div id="lbMember">
          <a class="lbuser" onclick="lbMemberPopup();">
              <span class="ico"></span><img src="Form_files/images/noAvatar_20.png" width="20">
              <span class="txt">Admin</span><span style="clear:both;"></span>
          </a>
          <div id="lbMemberPopup" class="lbdd">
              <ul>
                 
                  <li class="memberSignout">
                   <a onclick="lnwbar_do_logout();">
                       <i>
                       </i>
                       <span class="txt">ออกจากระบบ</span>
                   </a>
                  </li>
              </ul>
          </div>
      </div>
            <div id="lbMessage">
                <a onclick="show_lnwmsg_message();"></a>
                    
            </div>
        </div>
    </div>

	<div id="ajax-loading">Loading...</div>
	<div id="alert-error" title=""></div>

	<div id="idHiddenDiv" style="display:none;"><div id='idOpenDialog'></div></div>
	<div class="user_cp">
	<div style="height: 10px; width: 100%; background-color: #444;  border-bottom: 2px solid #888; margin: 0px; padding: 0px;"></div>
<div align="center" class="menu_header_user">
	<div style="float: left; margin: 15px 0px 0px 50px;">
		<div><h1>Form Management</h1></div>
	</div>
	<div style="float: left; margin-left: 5%;" class="menu_user">
		
	</div>
	<div style="position: relative; float: left; height: 0px;" align="right">
		<div class="menu_user_select" style="right: 565px;top: 70px;">&nbsp;</div>
	</div>
	<div style="clear: both; border-top: 1px solid white;"></div>
</div>
<div class="menu_header_user_bottom">
	<div class="breadcrumb" style="text-indent: 30px;">

		Form Management	</div>
</div>
<div style="clear: both;"></div>


	<div align="center" style="margin-top: 10px;">
	<div class="bg_black trim_24">
		<table cellpadding="0" cellspacing="0" style="width: 100%; height: 100%">
			<tr style="height: 48px" valign="top">
				<td onclick="window.location.href = 'Create.php';" tdlink='' tdleft='' style="width: 110px; border-right: 1px solid black; height: 48px;">
					<img style="float: left;" align="middle" src="Form_files/images/add_form.png" alt="add form icon" width="25px" height="25px">
					<a style="float:left; margin-top: 5px;" href="Create.php"><b style="color: orange;">สร้างฟอร์มใหม่</b></a>
				</td>
				<td style="border-left: 1px solid #45484c; border-right: 1px solid black; width: 110px; ">

					

				</td>
				<td style="border-left: 1px solid #45484c; border-right: 1px solid black;" align="left">
									</td>
				<td style="border-left: 1px solid #45484c; border-right: 1px solid black; width: 210px;">
					
					

				</td>
				<td style="width: 185px; border-left: 1px solid #45484c;">
					<img align="top" style="margin-top:3px;" src="Form_files/images/search.png" alt="add form icon" width="20px" height="20px">
					<input type="text" value="ค้นหา" style="color: #666;" onclick="if(this.value == 'ค้นหา')this.value = '';" onkeyup="search_form_item(this.value);" />
				</td>
			</tr>
		</table>
	</div>

	<div style="height: 20px;"></div>
<div id="form_area">
<? echo $show;?>
</div>

<script>
	var form_current = new Array();
	form_current[0] = 1;
	form_current[1] = 0;
	form_current[2] = 0;

	function form_click(form, object_form){
		if(form_current[form - 1] == 1){
			$('#form' + form).slideUp();
			$(object_form).attr('select', '');
			form_current[form - 1] = 0;
		} else {
			$('#form' + form).slideDown();
			$(object_form).attr('select', 'select');
			form_current[form - 1] = 1;
		}
	}

	function selectFormAll(select) {
		if(select == 1){
			$('input[form="input"]').each(function() {
				$(this).attr('checked', 'checked');
			});
		} else {
			$('input[form="input"]').each(function() {
				$(this).attr('checked', '');
			});
		}
	}
	function search_form_item(q) {
		if(q.length == '') {
			$('.form_item').show();
		} else {
			q = q.replace(/\"/, '\\\"');
			$('.form_item').hide();
			$('.form_item[name*="' + q + '"]').show();
		}
	}
	function form_sort(){
		var formitems = $('#form_area .form_item');
		var sortby = $('#form-sortby').val();
		formitems.sort(function(a,b){
			var stra = $(a).attr(sortby);
			var strb = $(b).attr(sortby);
			var result = 0;
			var i;
			for(i = 0; i < stra.length || i < strb.length; i++) {
				if(i >= stra.length || stra[i] < strb[i]) {
					result = 1;
					break;
				} else if(i >= strb.length || stra[i] > strb[i]) {
					result = -1;
					break;
				}
			}
			if($('#form-sortby-reverse').is(':checked')){
				return result;
			}else{
				return -result;
			}
		});
		$.each(formitems,function(index, value){
			$(value).appendTo('#form_area');
		});
	}
	
</script>
<script type="text/javascript">function show_lnwmsg_message(obj) {
	if(jQuery('#lb').hasClass('show_MessagePopup')){
		jQuery('#lb').removeClass('show_MessagePopup');
		jQuery(document).unbind('mouseup.lbMessage');
	}else{
		var data = {
			data: 'ACkHYQYhCm8CPltvAW5aLAQjVDFXPAU_AzIDb1x0UgpWcFIyUmEADAA4UjFWJVZuBH9VPFRnDiFReAElVzNYZ1JvXjlRY1IhBwlTJQI2Xn4AIgdpBjgKZgIEW2kBZFp1BDtUcFdgBW8DNAMzXGNSYFY0UmNSOwBmADdSZFZiVmwEbFVqVDYON1E3ATFXM1huUj5eP1E7UmUHblMyAjdebABgBzEGdQokAnlbYQFjWjQEblQnVz4FIgMIA2hcZFJ3Vj1SdVI2AGoAYVJtVjdWZAR_VSJUdw5cUSEBd1c0WGpScl45UWlSCgciUz8CPl5oAHMHOgY5Cn0CN1tsASxadQR1VDtXPQUzAyUDI1w6UmRWNFJgUjoAZQBgUmxWNVZmBGpVIlR3DmZRLAFzVyJYalIkXmZRY1IgBzpTOgIu'
		};
		jQuery.getJSON("http://lnwmsg.com/jsonp/notifications?lnwmsg_jsonp=?", data, function(rdata) {
			if(rdata.success) {
				var notice = rdata.notifications;
				var lis = '';
				for(var key in notice){
					var nt = notice[key];
					var intype = '';
					switch(nt.type){
						case 'lnwshop/owner/webboard/topic':
						case 'lnwshop/owner/webboard/post':
						case 'lnwshop/user/webboard/post':
							intype = 'chat';
							break;
						case 'lnwshop/owner/contact_us':
							intype = 'message';
							break;
						case 'lnwshop/owner/product/discuss':
						case 'lnwshop/owner/product/review':
							intype = 'review';
							break;
						case 'lnwshop/owner/payment':
							intype = 'payment';
							break;
						case 'lnwshop/user/order':
						case 'lnwshop/owner/order':
						case 'lnwshop/user/shipping':
						case 'lnwshop/owner/shipping':
							intype = 'order';
							break;
						case 'lnwshop/owner/broadcast':
							intype = 'broadcast';
							break;
					}
					var link = '';
					if(typeof nt.extra == 'undefined' || nt.extra == null){

					}else if(typeof nt.extra == 'string'){
						link = ' href="'+jQuery.parseJSON(nt.extra).url+'"';
					}else if(typeof nt.extra.url != 'undefined'){
						link = ' href="'+nt.extra.url+'"';
					}
					lis += '<li status="'+nt.status+'"><a'+link+'><i class="icon_notice in_'+intype+'"></i><span class="txt">'+nt.message+'</span></a></li>';
				}
				jQuery('#lbMessagePopup ul').html(lis);
				jQuery('#lb').addClass('show_MessagePopup');
				jQuery(document).bind('mouseup.lbMessage',function(event){
					if(jQuery(event.target).closest('#lbMessage').length==0){
						jQuery('#lb').removeClass('show_MessagePopup');
						jQuery(document).unbind('mouseup.lbMessage');
					}
				});
			}
		});
	}
}


function accounts_logout(error_handler, success_handler) {
	if(typeof error_handler == 'object'){
		var handlers = error_handler;
		delete error_handler;
		if(typeof handlers.error == 'function'){
			error_handler = handlers.error;
		}
		if(typeof handlers.success == 'function'){
			success_handler = handlers.success;
		}
		if(typeof handlers.beforesuccess == 'function'){
			beforesuccess_handler = handlers.beforesuccess;
		}
	}
	var logout_continue_url = site_url('');
	var data = {
		data: 'ACkHYQYhCm8CPltvAW5aLAQjVDFXPAU_AzIDb1x0UgpWcFIyUmEADAA4UjFWJVZuBH9VPFRnDiFReAElVzNYZ1JvXjlRY1IhBwlTJQI2Xn4AIgdpBjgKZgIEW2kBZFp1BDtUcFdgBW8DNAMzXGNSYFY0UmNSOwBmADdSZFZiVmwEbFVqVDYON1E3ATFXM1huUj5eP1E7UmUHblMyAjdebABgBzEGdQokAnlbYQFjWjQEblQnVz4FIgMIA2hcZFJ3Vj1SdVI2AGoAYVJtVjdWZAR_VSJUdw5cUSEBd1c0WGpScl45UWlSCgciUz8CPl5oAHMHOgY5Cn0CN1tsASxadQR1VDtXPQUzAyUDI1w6UmRWNFJgUjoAZQBgUmxWNVZmBGpVIlR3DmZRLAFzVyJYalIkXmZRY1IgBzpTOgIu',
		logout_continue_url: logout_continue_url
	};
	$.getJSON("https://lnwaccounts.com/2/jsonp/logout?lnwaccounts_jsonp=?", data, function(response) {
		if(response.resync) {
			window.location.href = site_url('sess/destroy');
		} else if(response.success) {
			if(typeof beforesuccess_handler == 'function') {
				beforesuccess_handler(response);
			}
			$.cookie('_lnwaccc', null, {path: '/'});
						if(typeof success_handler == 'function') {
				success_handler(response);
			} else {
				if(response.redirect) {
					window.location.href = response.redirect_url;
				} else {
					window.location.href = logout_continue_url;
				}
			}
		} else {
			if(typeof error_handler == 'function') {
				error_handler(response);
			}
		}
	});
	return false;
}
function lnwbar_handler_success(data) {
     window.location = "index.php";
}

function lnwbar_do_logout(form) {

	accounts_logout({beforesuccess: lnwbar_handler_success});
     window.location = "index.php";
	//return false;
}
function lbProductsPopup(){
	if(jQuery('#lb').hasClass('show_ProductPopup')){
		jQuery('#lb').removeClass('show_ProductPopup');
		jQuery(document).unbind('mouseup.lbProduct');
	}else{
		jQuery('#lb').addClass('show_ProductPopup');
		jQuery(document).bind('mouseup.lbProduct',function(event){
			if(jQuery(event.target).closest('#lbLnw').length==0){
				jQuery('#lb').removeClass('show_ProductPopup');
				jQuery(document).unbind('mouseup.lbProduct');
			}
		});
	}
}
function lb_chooseProduct(product){
	jQuery('#lbProductPopup').attr('class',product);
}
function lbMemberPopup(){
	if(jQuery('#lb').hasClass('show_MemberPopup')){
		jQuery('#lb').removeClass('show_MemberPopup');
		jQuery(document).unbind('mouseup.lbMember');
	}else{
		jQuery('#lb').addClass('show_MemberPopup');
		jQuery(document).bind('mouseup.lbMember',function(event){
			if(jQuery(event.target).closest('#lbMember .lbuser').length==0){
				jQuery('#lb').removeClass('show_MemberPopup');
				jQuery(document).unbind('mouseup.lbMember');
			}
		});
	}
}



</script>
	
</body>
</html>
<?
    }
    else
    {
        	?>
								<script type="text/javascript">
                                    alert('Login Plese');
                                    window.location = "index.php";
								</script>
		    <?
    }
?>