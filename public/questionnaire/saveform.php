<?php
session_start();
if($_SESSION["valid"]=='yes')
{
    $formid=$_GET["formid"];
    
    $domain=$_SERVER['HTTP_HOST'];
    
    $str=$domain."/showform.php?formid=$formid";
?>
<!DOCTYPE html>
<html lang="en">
    <head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" href="./Form_files/form.css">
<link rel="stylesheet" type="text/css" href="./Form_files/style(1).css">
<link rel="stylesheet" type="text/css" href="./Form_files/y2013.css">
<script language="JavaScript" type="text/javascript" src="./Form_files/jquery.lnwajax.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/jquery.cookie.js"></script>
</head>
<body class="lnwform-unit">
   
    <table cellpadding="0" cellspacing="0" class="table_shadow" align="center" style="width: 700px;">
	    <thead>
		    <tr>
			    <td class="left"></td>
			    <td class="center"></td>
			    <td class="right"></td>
		    </tr>
	    </thead>
	    <tbody>
		    <tr>
			    <td class="left"></td>
			    <td class="center">
				    <div class="wrapper">
	<div class="crown"  >
     
	<b class="icon-link alige ="center"> </b>
    <b >Link Code</b>
</div>
<div class="container">
	<div align="center" style="line-height:30px; font-size: 16px;padding: 50px 0px 50px;">
		
	<span style="font-size: 14px;">เลือก Copy โค้ดด้านล่างนี้<br />เพื่อให้ผู้ใช้กด Link มายังฟอร์มของคุณ</span></br>
        <b>Direct Link</b>:  Link URL นี้สำหรับใช้สำหรับ Email และส่งข้อความทั่วไป
							<br />
		<input class="input_bg" type="text" style="width: 320px; float: initial;" value=<? echo $str ; ?>  readonly="readonly" />
														<br />
        
		<a style="font-size: 13px; text-decoration: underline; color: orange;" href="Main.php">กลับสู่หน้าจัดการฟอร์ม</a>
	</div>
	<div align="center" style="width: 100%;  margin: 0px; padding: 0px;">
		<div id="add_field" style="float: none; ">
			<ul class="button_form_design" style="float: none; margin: 0px; padding: 0px; ">
			    <li style="width: 170px;" onclick="window.location.href='Create.php';"><a><div class="icon-duplicate" style="margin-right:0px;"></div>สร้างแบบฟอร์มของคุณเอง</a></li>
			</ul>
		</div>
		<br />
	Powered by Questionaire</div>
	</div>
</div>				</div>
			</td>
			<td class="right"></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td class="left"></td>
			<td class="center"></td>
			<td class="right"></td>
		</tr>
	</tfoot>
</table>
    <script type="text/javascript">
function show_lnwmsg_message(obj) {
	if(jQuery('#lb').hasClass('show_MessagePopup')){
		jQuery('#lb').removeClass('show_MessagePopup');
		jQuery(document).unbind('mouseup.lbMessage');
	}else{
		var data = {
			data: 'UntYPlF2VzJQbAYyAW4PeQIlVjNUP1JoVmdQPAAoWgJbfQVlUmFXWwc_VDcFdldvVi1bMlFiCCcAKVZyD2tTbFxhXzhcblUmW1VcKlZiAiJScFg2UW9XO1BWBjQBZA8gAj1WclRrUmRWY1BiAGxab1s-BTRSYVdhBzVUZwUwV2RWNltlUWAIMABkVmcPalM0XD1fblw0VWpbO1w7VjcCMlI0WG9RIld5UCsGPAFjD2ECaFYlVD1SdVZdUDsAOFp_WzAFIlI2Vz0HZlRrBWRXZVYtWyxRcghaAHBWIA9sU2FcfF84XGRVDVt-XDBWagI0UiFYZVFuVyBQZQYxASwPIAJzVjlUPlJkVnBQcABmWmxbOQU3UjtXNQdlVGcFZ1dsVj1bLFFyCGAAfVYkD3pTYVwqX2dcblUnW2ZcNVZ6'
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
jQuery(document).ready(function() {
	var data = {
		data: 'UntYPlF2VzJQbAYyAW4PeQIlVjNUP1JoVmdQPAAoWgJbfQVlUmFXWwc_VDcFdldvVi1bMlFiCCcAKVZyD2tTbFxhXzhcblUmW1VcKlZiAiJScFg2UW9XO1BWBjQBZA8gAj1WclRrUmRWY1BiAGxab1s-BTRSYVdhBzVUZwUwV2RWNltlUWAIMABkVmcPalM0XD1fblw0VWpbO1w7VjcCMlI0WG9RIld5UCsGPAFjD2ECaFYlVD1SdVZdUDsAOFp_WzAFIlI2Vz0HZlRrBWRXZVYtWyxRcghaAHBWIA9sU2FcfF84XGRVDVt-XDBWagI0UiFYZVFuVyBQZQYxASwPIAJzVjlUPlJkVnBQcABmWmxbOQU3UjtXNQdlVGcFZ1dsVj1bLFFyCGAAfVYkD3pTYVwqX2dcblUnW2ZcNVZ6'
	};
	jQuery.getJSON("http://lnwmsg.com/jsonp/count_notifications?lnwmsg_jsonp=?", data, function(rdata) {
		if(rdata.success && rdata.count != 0) {
			jQuery('#lbMessage .count').html(rdata.count);
			jQuery('#lbMessage .lbletter.zero').removeClass('zero');
		} else {
			jQuery('#lbMessage .count').html('0');
			jQuery('#lbMessage .lbletter').addClass('zero');
		}
	});
});
var accountsTime = null;
function accounts_update() {
	var data = {
		data: 'UntYPlF2VzJQbAYyAW4PeQIlVjNUP1JoVmdQPAAoWgJbfQVlUmFXWwc_VDcFdldvVi1bMlFiCCcAKVZyD2tTbFxhXzhcblUmW1VcKlZiAiJScFg2UW9XO1BWBjQBZA8gAj1WclRrUmRWY1BiAGxab1s-BTRSYVdhBzVUZwUwV2RWNltlUWAIMABkVmcPalM0XD1fblw0VWpbO1w7VjcCMlI0WG9RIld5UCsGPAFjD2ECaFYlVD1SdVZdUDsAOFp_WzAFIlI2Vz0HZlRrBWRXZVYtWyxRcghaAHBWIA9sU2FcfF84XGRVDVt-XDBWagI0UiFYZVFuVyBQZQYxASwPIAJzVjlUPlJkVnBQcABmWmxbOQU3UjtXNQdlVGcFZ1dsVj1bLFFyCGAAfVYkD3pTYVwqX2dcblUnW2ZcNVZ6',
		version: 6047
	};
	$.getJSON("https://lnwaccounts.com/2/jsonp/update?lnwaccounts_jsonp=?", data, function(response) {
		if(response.success) {
			$.cookie('_lnwaccc', response.cookie, {path: '/'});
						if(response.state_change != undefined && response.state_change) {
				window.location.href = window.location.href;
				return;
			}
		}
		if(accountsTime!=null){
			clearTimeout(accountsTime);
		}
		accountsTime = setTimeout("accounts_update()", 5 * 60 * 1000);
	});
	//$.ajax({
	//	type: 'GET',
	//	data:{},
	//	url:'http://www.lnwform.com/lnwbar/action/session'
	//});
}
$(document).ready(function() {
	accounts_update();
});
function accounts_login(obj, error_handler, success_handler) {
	if(obj.username == undefined || obj.password == undefined) {
		return false;
	}
	if(obj.persistent == undefined) {
		obj.persistent = false;
	}
	if(typeof error_handler == 'object'){
		var handlers = error_handler;
		delete error_handler;
		if(typeof handlers.error == 'function'){
			error_handler = handlers.error;
		}
		if(typeof handlers.success == 'function'){
			success_handler = handlers.success;
		}
		if(typeof handlers.beforesuccess == 'function') {
			beforesuccess_handler = handlers.beforesuccess;
		}
	}
	var data = {
		username: obj.username,
		password: obj.password,
		persistent: obj.persistent,
		data: 'UntYPlF2VzJQbAYyAW4PeQIlVjNUP1JoVmdQPAAoWgJbfQVlUmFXWwc_VDcFdldvVi1bMlFiCCcAKVZyD2tTbFxhXzhcblUmW1VcKlZiAiJScFg2UW9XO1BWBjQBZA8gAj1WclRrUmRWY1BiAGxab1s-BTRSYVdhBzVUZwUwV2RWNltlUWAIMABkVmcPalM0XD1fblw0VWpbO1w7VjcCMlI0WG9RIld5UCsGPAFjD2ECaFYlVD1SdVZdUDsAOFp_WzAFIlI2Vz0HZlRrBWRXZVYtWyxRcghaAHBWIA9sU2FcfF84XGRVDVt-XDBWagI0UiFYZVFuVyBQZQYxASwPIAJzVjlUPlJkVnBQcABmWmxbOQU3UjtXNQdlVGcFZ1dsVj1bLFFyCGAAfVYkD3pTYVwqX2dcblUnW2ZcNVZ6'
	};
	$.getJSON("https://lnwaccounts.com/2/jsonp/login?lnwaccounts_jsonp=?", data, function(response) {
		if(response.resync) {
			window.location.href = site_url('sess/destroy');
		} else if(response.success) {
			if(typeof beforesuccess_handler == 'function') {
				beforesuccess_handler(response);
			}
			if(data.cookie != undefined) {
				$.cookie('_lnwaccc', response.cookie, {path: '/'});
			}
			if(response.redirect) {
				window.location.href = response.redirect_url;
			} else if(typeof success_handler == 'function') {
				success_handler(response);
			} else {
				if(typeof LNWACCOUNTS_CONTINUE_URL != 'undefined'){
					var href = decodeURIComponent((LNWACCOUNTS_CONTINUE_URL + '').replace(/\+/g, '%20'));
					href += (href.indexOf('?') > -1)?'&':'?';
					href += '_lnwaccc='+response.cookie;
					window.location.href = href;
				}else{
					var href = window.location.href;
					href += (href.indexOf('?') > -1)?'&':'?';
					href += '_lnwaccc='+response.cookie;
					window.location.href = href;
				}
			}
		} else {
			if(typeof error_handler == 'function') {
				error_handler(response.message);
			} else {
				$.each(response.message,function(k,v){
					alert(v);
				});
			}
		}
	});
	return false;
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
		data: 'UntYPlF2VzJQbAYyAW4PeQIlVjNUP1JoVmdQPAAoWgJbfQVlUmFXWwc_VDcFdldvVi1bMlFiCCcAKVZyD2tTbFxhXzhcblUmW1VcKlZiAiJScFg2UW9XO1BWBjQBZA8gAj1WclRrUmRWY1BiAGxab1s-BTRSYVdhBzVUZwUwV2RWNltlUWAIMABkVmcPalM0XD1fblw0VWpbO1w7VjcCMlI0WG9RIld5UCsGPAFjD2ECaFYlVD1SdVZdUDsAOFp_WzAFIlI2Vz0HZlRrBWRXZVYtWyxRcghaAHBWIA9sU2FcfF84XGRVDVt-XDBWagI0UiFYZVFuVyBQZQYxASwPIAJzVjlUPlJkVnBQcABmWmxbOQU3UjtXNQdlVGcFZ1dsVj1bLFFyCGAAfVYkD3pTYVwqX2dcblUnW2ZcNVZ6',
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
function lnwbar_handler_login(data) {
	if(data.email == 'EMAIL_NOT_ACTIVATED' || data.email == 'USER_NOT_ACTIVATED') {
		jQuery('#lbLoginPopup .notice').html('<div alert="not_activated">คุณยังไม่ได้ Activated Account นะค่ะ หรือ <a style=""  href="https://lnwaccounts.com/activate/resend?lnw_service=lnwform&continue_url=http%3A%2F%2Fwww.lnwform.com%2Fform%2Fadd">ขอ Activate Code ใหม่ค่ะ</a></div>');
	}else{
		jQuery('#lbLoginPopup .notice').html('<div alert="email_password">Email หรือ รหัสผ่านไม่ถูกต้องค่ะ</div>');
	}
}


function lnwbar_do_logout(form) {
	accounts_logout({
		beforesuccess: lnwbar_handler_success
        
	});
     window.location = "index.php";
	return false;
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
function lnwbar_reportbug(){
	if(typeof jQuery.lnwbox2 == 'undefined'){
		jQuery.lnwbox({
			ajaxurl: 'http' + '://' + 'www.lnwform.com/report/load_form',
			action	: 'show'
		});
	}else{
		jQuery.lnwbox2({
			ajaxurl: 'http' + '://' + 'www.lnwform.com/report/load_form',
			action	: 'show'
		});
	}
}
$('#lbProductPopup').html('<div class=\"lblists\"><a class=\"lblist lnwshop\" onmouseover=\"lb_chooseProduct(\'lnwshop\');\" href=\"http://www.lnwshop.com/\" target=\"_blank\"></a><a class=\"lblist lnwmall\" onmouseover=\"lb_chooseProduct(\'lnwmall\');\" href=\"http://www.lnwmall.com/\" target=\"_blank\"></a><a class=\"lblist lnwmarket\" onmouseover=\"lb_chooseProduct(\'lnwmarket\');\" href=\"http://shopping.lnwshop.com/\" target=\"_blank\"></a><a class=\"lblist lnwform\" onmouseover=\"lb_chooseProduct(\'lnwform\');\" href=\"http://www.lnwform.com/\" target=\"_blank\"></a><a class=\"lblist lnwpic\" onmouseover=\"lb_chooseProduct(\'lnwpic\');\" href=\"http://lnwpic.com/\" target=\"_blank\"></a><a class=\"lblist bloglnw\" onmouseover=\"lb_chooseProduct(\'bloglnw\');\" href=\"http://blog.lnw.co.th/\" target=\"_blank\"></a></div><div class=\"lbdetails\"><a class=\"lbdetail lnwshop\" href=\"http://www.lnwshop.com/\" target=\"_blank\"><div class=\"txt\"><strong>LnwShop.com</strong> สร้างร้านค้าออนไลน์ฟรี ไม่มีค่าบริการายปี ไม่จำกัดจำนวนชิ้นสินค้า ระบบการตกแต่งร้านแนวใหม่ WYSIWYG เทมเพลทน่ารัก สวยงาม พร้อมระบบจัดการหลังร้านครบทุกฟังก์ชั่นการซื้อ-ขาย</div></a><a class=\"lbdetail lnwmall\" href=\"http://www.lnwmall.com/\" target=\"_blank\"><div class=\"txt\"><strong>LnwMall.com</strong> ห้างสรรพสินค้าออนไลน์สุดชิค คัดเฉพาะสินค้าคุณภาพจากร้านค้าภายใน LnwShop สั่งซื้อง่ายแถมรับคะแนนคืนสูงสุด 2% เพื่อใช้เป็นส่วนลดในครั้งต่อไป พร้อมรับประกันได้รับสินค้าถึงมือ 100%</div></a><a class=\"lbdetail lnwmarket\" href=\"http://www.lnwmarket.com/\" target=\"_blank\"><div class=\"txt\"><strong>LnwMarket.com</strong> ตลาดเทพช้อปปิ้งออนไลน์ แหล่งรวมสินค้าที่คุณชื่นชอบ ให้คุณช้อปปิ้งได้ง่ายกว่า ด้วยการแสดงผลแบบต่อเนื่อง ไม่ต้องคลิกลิงก์หน้าใหม่ แค่คุณเลื่อนเมาส์ สินค้าถัดไปก็จะแสดงผลต่อทันที</div></a><a class=\"lbdetail lnwform\" href=\"http://www.lnwform.com/\" target=\"_blank\"><div class=\"txt\"><strong>LnwForm.com</strong> สร้างแบบฟอร์ม แบบสอบถามหรือแบบประเมินง่ายๆ เพียง 3 ขั้นตอน คุณก็สามารถนำฟอร์มไปวางในเว็บไซต์ของคุณ และสามารถเรียกดูข้อมูลที่กรอกผ่านฟอร์มได้ตลอดเวลา</div></a><a class=\"lbdetail lnwpic\" href=\"http://lnwpic.com/\" target=\"_blank\"><div class=\"txt\"><strong>LnwPic.com</strong> เก็บรูปภาพความประทับใจไว้ไม่ให้อยู่เพียงแต่ในความทรงจำ ฝากรูปกับ LnwPic แบบไม่มีจำกัด พร้อมแชร์ให้เพื่อนง่ายนิดเดียว</div></a><a class=\"lbdetail bloglnw\" href=\"http://blog.lnw.co.th/\" target=\"_blank\"><div class=\"txt\"><strong>Blog.Lnw.co.th</strong> บล็อกรวบรวมบทความข่าว โปรโมชั่น และสาระน่ารู้ที่จะทำให้คุณรู้จัก Lnw มากกว่าเคย ร่วมสนุก และค้นหาสิ่งใหม่ๆไปกับบล็อกเทพ</div></a></div>');</script><script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-18903793-10']);
  _gaq.push(['_trackPageview']);

  (function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

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
