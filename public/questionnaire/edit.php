﻿<?
 session_start();
if($_SESSION["valid"]=='yes')
{
$formid=$_GET["formid"];

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

$sql=" SELECT * FROM main_form WHERE form_id =$formid";

$select = mysql_query($sql, $connect) ;



while($row = mysql_fetch_array($select))
{
  
    $text2 = $row['form_log'];
    $text = html_entity_decode($text2);


}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Questionnaire</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />




<script type="text/javascript" async="" src="./Form_files/ga.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/jquery-1.4.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="./Form_files/jquery-ui-1.8.2.custom.min.js"></script>




<script language="JavaScript" type="text/javascript">
    function base_url(uri) { return "http://www.lnwform.com/" + (uri === undefined ? "" : uri); }
    function site_url(uri) { return "http://www.lnwform.com/" + (uri === undefined ? "" : uri); }
    function template_url(uri) { return "http://www.lnwform.com/system/application/views/templates/lnwform/default" + (uri === undefined ? "" : uri); }
    $(document).ready(function () {
        $.ajaxSetup({
            dataType: "html",
            cache: false
        });
        $(this).ajaxSend(function (e, xhr, settings) {
            settings.data += '&ajaxxxx=true';
        });


        // ********************//
        //alert(teststr);
        var form_name = $("div[id='header_h1']").find("b.form_name").html();
        $("div.form_name").find("input[type='text']").val(form_name);

        var form_title = $("div[class='field_element ui-corner-all field_element_header_selected']").find("div.form_title").html();
        $("div.form_title").find("input[type='text']").val(form_title);

        var form_description = $("div[class='field_element ui-corner-all field_element_header_selected']").find("div.form_description").html();
       $("div.form_description").find("textarea").html(form_description);

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
var LNWACCOUNTS_COOKIE_DATA = 'UXhRNwEmVzIFOQE1AG9ZLwMkUjcDaAU_XWxUOFR8Bl4BJ1c3UGNVWQc_VTZUJ1dvA3hbMlJhXHMDKgImVDBRblVoVDMENgZ1WlRWIAUxUXFRc1E_AT9XOwUDATMAZVl2AzxSdgM8BTNdaFRmVDgGMwFkV2ZQY1VjBzVVZlRhV2QDY1tlUmNcZANnAjNUMVE2VTRUZQRsBjlaOlYxBWRRYVE3UWYBcld5BX4BOwBiWTcDaVIhA2oFIl1WVD9UbAYjAWpXcFA0VT8HZlVqVDVXZQN4WyxScVwOA3MCdFQ3UWNVdVQzBDwGXlp_VjoFOVFnUSJRbAE-VyAFMAE2AC1ZdgNyUj0DaQUzXXtUdFQyBjABY1dlUDlVNwdlVWZUNldsA2hbLFJxXDQDfgJwVCFRY1UjVGwENgZ0WmdWPwUp';
</script>
<!--[if IE]>
	<style type="text/css">
		body { font-size: 11px; }
	</style>
<![endif]-->
</head>

<body style="margin: 0px;">

    <input type ="hidden" value="<? echo $formid ?>" id="edit" >
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
                <a onclick="show_lnwmsg_message();" ></a>
                    
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
		
				<div style="clear: both;"></div>
	</div>
	<div style="position: relative; float: left; height: 0px;" align="right">
		<div class="menu_user_select" style="right: 565px;top: 70px;">&nbsp;</div>
	</div>
	<div style="clear: both; border-top: 1px solid white;"></div>
</div>
<div class="menu_header_user_bottom">
	<div class="breadcrumb" style="text-indent: 30px;">
				<a style="margin-right:30px;" href='Main.php'><span style="font-size:8px;">&lt&lt </span>ย้อนกลับ</a>
	</div>
</div>
<div style="clear: both;"></div>


	
<script language="javascript" type="text/javascript">
	function init() {
		$('.field_container').sortable({
			start: function(event, ui) {
				lnw_console('start');
			},
			sort: function(event, ui) {
				lnw_console('sort');
			},
			change: function(event, ui) {
				lnw_console('change');
			},
			beforeStop: function(event, ui) {
				lnw_console('beforeStop');

				reset_right_menu();
			},
			stop: function(event, ui) {
				lnw_console('stop');
			},
			update: function(event, ui) {
				lnw_console('update');
			},
			receive: function(event, ui) {
				lnw_console('receive');
			},
			remove: function(event, ui) {
				lnw_console('remove');
			},
			over: function(event, ui) {
				lnw_console('over');
			},
			out: function(event, ui) {
				lnw_console('out');
			},
			activate: function(event, ui) {
				lnw_console('activate');
			},
			deactivate: function(event, ui) {
				lnw_console('deactivate');
			},

			//connectWith: '',
			//containment: '',
			cursor: 'move',
			delay: 100,
			dropOnEmpty: true,
			//handle: 'span',
			//opacity: 0.9,
			//placeholder: 'product-ui-state-highlight',
			revert: true,
			tolerance: 'pointer'
		});

		$('.choices').add($('.statements')).sortable({
			start: function(event, ui) {
				lnw_console('start');
			},
			sort: function(event, ui) {
				lnw_console('sort');
			},
			change: function(event, ui) {
				lnw_console('change');
			},
			beforeStop: function(event, ui) {
				lnw_console('beforeStop');
			},
			stop: function(event, ui) {
				lnw_console('stop');

				reset_element();
			},
			update: function(event, ui) {
				lnw_console('update');
			},
			receive: function(event, ui) {
				lnw_console('receive');
			},
			remove: function(event, ui) {
				lnw_console('remove');
			},
			over: function(event, ui) {
				lnw_console('over');
			},
			out: function(event, ui) {
				lnw_console('out');
			},
			activate: function(event, ui) {
				lnw_console('activate');
			},
			deactivate: function(event, ui) {
				lnw_console('deactivate');
			},

			//connectWith: '',
			//containment: '',
			cursor: 'move',
			delay: 100,
			dropOnEmpty: true,
			handle: 'span',
			//opacity: 0.9,
			//placeholder: 'product-ui-state-highlight',
			revert: true,
			tolerance: 'pointer'
		});
	}

	$(document).ready(function() {
		$.ajaxSetup({
			dataType: "html",
			cache: false
		});

	

				$('#right_menu, #form_menu').hide();

		$('#top_menu ul li').each(function(){
			$(this).hover(function(){
				$(this).removeClass('menu-b');
				$(this).addClass('menu-b_hover');
			},function(){
				$(this).removeClass('menu-b_hover');
				$(this).addClass('menu-b');
			});
		});
		$(".main_logo").css("position","fixed");
		init();
		$("#save_b").ajaxStart(function(){
			$(this).val('Saving');
			$(this).css('background','#aaaaaa');
			$(this).css('color','#ffffff');
			$(this).attr("disabled","disabled");
		});
		$("#save_b").ajaxStop(function(){
			$(this).val('Complete');
		});

		//step_focus(2);
	});
</script>


<table class="wrapper2" cellpadding="0" cellspacing="0" style="width: 100%;">
	<? echo $text ; ?>
</table>

<script language="JavaScript" type="text/javascript">
    function save_form() {
        /*  var form_id = '';
        var ajaxdata = {form_config: $.toJSON(read_all_config())};
        alert(ajaxdata);


        $.lnwbox({
        ajaxurl:site_url('form/box/form_name/' + form_id),
        ajaxdata:ajaxdata,
        action:"show"
        });
        return false;*/

        //console.log(sc);
        
        var log = $('.wrapper2').html();
        
        var ajaxdata = read_all_config();
        //console.log(ajaxdata);
        var edit = $('#edit').val();
        
		if(confirm('คุณแน่ใจว่าต้องการ Save หรือไม่ ?')){
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "update.php",
            data: { myData: ajaxdata, EditID: edit, myLog: log },
            //contentType: "application/json; charset=utf-8",
            success: function (data) {
                if (data == 1) {
                   
                  window.location = "Main.php";

                }

            },
            error: function (e) {
                //console.log(e.message);
                //alert("error");
            }
        });
		}


    }

    function form_name_submit() {
        var url = site_url('form/save');
        var form_config = read_all_config();
        form_config.form_name = $('#form_name_input').val();
        var data = {
            form_config: $.toJSON(form_config)
        };
        if ($('input[name=form_id]').length) {
            data.form_id = $('input[name=form_id]').val();
        }
        lnw_console(data);
        $.post(url, data, null, 'script');
        return false;
    }

    

</script>

	
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
