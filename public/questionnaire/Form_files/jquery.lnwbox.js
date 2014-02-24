(function($){

$.lnwbox = $.fn.lnwbox = function(data){
	var config = {
		html		:	null,
		selector	:	null,
		action		:	'',
		slide		:	false,
		back		:	true,
		backColor	:	'#000',
		backOpacity	:	0.3,
		callback	:	null,
		addClass	:	'',
		ajaxurl		:	null,
		ajaxdata	:	{},
		ajaxcache	:	false,
		position	:	'absolute',
		vertical	:	'middle',
		horizontal	:	'left',
		top			:	'height',
		left		:	0,
		theme		:	'simple',
		box			:	9,
		active		:	'opposite',	// 'all','condition','opposite','none'
		activeby	:	(typeof lnwTool == 'object')?lnwTool.editMode:false,
		canClose	:	true
	};
	if(typeof data == 'object'){
		$.extend(config, data);
		$('body').data('lnwbox',config);
	}else if(typeof data == 'string'){
		if($('body').data('lnwbox') != null && $('body').data('lnwbox') != undefined){
			config = $('body').data('lnwbox');
		}
		config.action = data;
	}
	if(config.active=='all' || (config.active=='condition' && config.activeby) || (config.active=='opposite' && !config.activeby)){
		var divbox;
		var divbody;
		if($('#lnwbox').length==0){
			divbox = $('<div/>').attr('id','lnwbox').hide();
			//divboxbox = $('<div/>').addClass('lnwbox-box');
			var html = '';
			if(config.box==9){
				html += '<table cellpadding="0" cellspacing="0" align="center" class="box9">';
				html += '<tbody>';
				html += '<tr class="boxTop"><td class="boxLeft"></td><td class="boxCenter"><div class="close">CLOSE</div></td><td class="boxRight"></td></tr>';
				html += '<tr class="boxMiddle"><td class="boxLeft"></td><td class="boxCenter" id="lnwboxBody"></td><td class="boxRight"></td></tr>';
				html += '<tr class="boxBottom"><td class="boxLeft"></td><td class="boxCenter"></td><td class="boxRight"></td></tr>';
				html += '</tbody>';
				html += '</table>';
			}else if(config.box==3){
				html += '<div class="box3">';
				html += '<div class="boxTop"></div>';
				html += '<div class="boxMiddle" id="lnwboxBody"></div>';
				html += '<div class="boxBottom"></div>';
				html += '</div>';
			}else if(config.box==1){
				html += '<div class="box1">';
				html += '<div id="lnwboxBody"></div>';
				html += '</div>';
			}
			divbox.html(html);
			divbox.appendTo(document.body);
			divbody = $('#lnwboxBody');
		}else{
			divbox = $('#lnwbox');
			divbody = $('#lnwboxBody');
		}
		divbox.addClass('position-'+config.position);
		//divbox.attr('theme',config.theme).attr('position',config.position);
		if(config.addClass!=''){
			divbox.addClass(config.addClass);
		}
		if(config.ajaxurl!=null){
			divbody.addClass('loading preload_48');
			divbody.html('');
			$.ajax({
				type: 'POST',
				url: config.ajaxurl,
				cache: config.ajaxcache,
				data: config.ajaxdata,
				success: function(data) {
					divbody.removeClass('loading preload_48');
					divbody.html(data);
					//divbody.width($(divbody).children().width());
					//divbody.height($(divbody).children().height());
					$.lnwboxPosition(divbox,config);
				}
			});
		}else if(config.html!=null){
			divbody.html(config.html);
		}else if(config.selector!=null){
			$(divbody).empty();
			$(config.selector).clone().appendTo(divbody);
		}else if(typeof this == 'object' && $(this).length > 0 ){
			var w = $(this).outerWidth(true);
			divbody.empty();
			divbody.width(w);
			divbody.html($(this).html());
		}
		$.lnwboxPosition(divbox,config);
		var divback = null;
		if(config.back){
			if($('#lnwboxBack').length==0){
				divback = $('<div/>').attr('id','lnwboxBack').hide().insertBefore(divbox);
			}else{
				divback = $('#lnwboxBack');
			}
			$(divback).width($(window).width());
			$(window).resize(function(){
				$(divback).width($(window).width());
			});
			if($(document).height()>$(window).height()){
				$(divback).height($(document).height());
			}else{
				$(divback).height($(window).height());
				$(window).resize(function(){
					$(divback).height($(window).height());
				});
			}
			$(divback).css('background-color',config.backColor).css('opacity',config.backOpacity).css('filter', 'alpha(opacity='+(config.backOpacity*100)+')');
		}
		if(config.action!=''){
			$.lnwboxAction(config.action,divbox,divback,config);
			if(config.callback != null){
				config.callback();
			}
		}
		if(config.canClose){
			$('#lnwbox, #lnwbox .close, #lnwboxBack').bind('click.close',function(event){
				if(event.target == this){
					$.lnwboxAction('hide',divbox,divback,config);
					$('#lnwbox, #lnwbox .close, #lnwboxBack').unbind('click.close');
				}
			});
		}
	}
};
$.lnwboxAction = function(action,divbox,divback,config){
	if(action=='show'){
		$(divbox).fadeIn(300);
		$(divback).fadeIn(300);
		$(document).unbind('keydown.lnwbox');
		$(document).bind('keydown.lnwbox',function(event){
			if($(divbox).length==0){
				$(document).unbind('keydown.lnwbox');
			}else{
				if(event.keyCode == 27){
					$(document).unbind('keydown.lnwbox');
					$.lnwboxAction('hide',divbox,divback,config);
				}
			}
		});
	}else if(action=='hide'){
		if(config.action=='showhide'){
			$(divback).add(divbox).remove();
		}else{
			$(divbox).fadeOut(300,function(){
				$(this).remove();
			});
			$(divback).fadeOut(300,function(){
				$(this).remove();
			});
		}
	}else if(action=='showhide'){
		$(document).one('keydown.lnwbox',function(event){
			if(event.keyCode == 27){
				$.lnwboxAction('hide',divbox,divback,config);
			}
		});
		divbox.add(divback).fadeIn(500).delay(2000).fadeOut(500,function(){
			$(divbox).remove();
			$(divback).remove();
			$(document).unbind('keydown.lnwbox',function(event){
				if(event.keycode == 27){
					$.lnwboxAction('hide',divbox,divback,config);
				}
			});
		});
	}else if(action=='update'){
		var divbody = $('.lnwbox-body:first',divbox);
		$.lnwboxPosition(divbox,config);
		divbody.width($(divbody).children().width());
		//divbody.height($(divbody).children().height());
	}
};
$.lnwboxPosition = function(divbox,config){
	var position = config.position;
	var vertical = config.vertical;
	var horizontal = config.horizontal;
	var top = config.top;
	var left = config.left;
	var toptop, leftleft;
	if(vertical == 'middle'){
		toptop = ($(window).height()-31)/2;
	}else{
		toptop = 31;
	}
	if(top == 'height'){
		toptop -= $(divbox).height()/2;
	}else{
		toptop += top;
	}
	if(horizontal == 'center'){
		leftleft = $(window).width()/2;
	}else{
		leftleft = 0;
	}
	if(left == 'width'){
		leftleft -= $(divbox).width()/2;
	}else{
		leftleft += left;
	}
	if(position == 'absolute'){
		css_top = parseInt($(document).scrollTop()+toptop);
		if(css_top < 31) css_top = 31;
		css_left = parseInt($(document).scrollLeft()+leftleft);
		if(css_left < 0) css_left = 0;
		divbox.css('top',css_top+'px');
		divbox.css('left',css_left+'px');
	}else if(position == 'fixed'){
		if(toptop < 31) toptop = 31;
		if(leftleft < 0) leftleft = 0;
		divbox.css('top',parseInt(toptop)+'px');
		divbox.css('left',parseInt(leftleft)+'px');
	}
}
})(jQuery);
