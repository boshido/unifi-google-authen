/*! Copyright 2011 PatLee (http://www.webpatle.com/blog/jquery/lnwajax)
 *
 * Version: 1.0.0
 *
 * Requires: 1.4.x
 *
 */

(function($){
	$.lnwajax = new function(){
		var queue = {};
		var sendnow = {};
		var action = {};
		var time = {};
		var temp = {};
		var delay = {};
		var tempcompare = function(name,temp){
			var _var_compare = function(var1,var2){
				if(var1 == var2) return true;
				var var_type = typeof var1;
				if(var_type != typeof var2) return false;
				if(var_type == 'string'){
					if(var1 != var2) return false;
				}
				if(var_type == 'undefined') return true;
				if(var_type == 'array'){
					if(var1.length != var2.length) return false;
				}
				if(var_type == 'array' || var_type == 'object'){
					for(key in var1){
						if(_var_compare(var1[key],var2[key]) == false) return false;
					}
					return true;
				}
				return false;
			}
			if(temp == null) return false;
			return _var_compare(temp[name],temp);
		};
		var active = function(name){
			if(queue[name].length > 0){
				var opts = queue[name][0];
				var setting = $.extend({},opts,{
					success: function(data, textStatus, XMLHttpRequest){
						sendnow[name] = null;
						if(opts.success != null){
							opts.success(data, textStatus, XMLHttpRequest);
						}
						queue[name].shift();
						active(name);
						time[name] = new Date().getTime();
						delay[name] = 1500;
					},
					error: function(XMLHttpRequest, textStatus, errorThrown){
						delay[name] = 0;
						if(opts.error != null){
							opts.error(XMLHttpRequest, textStatus, errorThrown);
						}
						queue[name].shift();
						active(name);
						time[name] = new Date().getTime();
				  }
				})
				sendnow[name] = $.ajax(setting);
			}
		};
		this.clear = function(name){
			if(sendnow[name] != null){
				sendnow[name].abort();
			}
			queue[name] = new Array();
		}
		this.run = function(name,isclear,opts,tmp){
			var defalut_opts = {
				type: 'POST',
				data: 'text'
			}
			opts = $.extend({},defalut_opts,opts);
			if(tmp != null && tempcompare(name,tmp) && (time[name] == null || delay[name] == null || time[name] >= new Date().getTime()-delay[name])){
				time[name] = new Date().getTime();
				if(opts.abort != null){
					opts.abort();
				}
			}else{
				time[name] = new Date().getTime();
				delay[name] = 1500;
				temp[name] = tmp;
				if(queue[name] == null){
					queue[name] = new Array();
				}
				if(isclear){
					this.clear(name);
				}
				queue[name].push(opts);
				if(queue[name].length == 1){
					if(opts.start != null){
						opts.start();
					}
					active(name);
				}
			}
		}
	}
})(jQuery);


function lnwajax_response(response,success,error){
	if(response === null || response === '' || typeof response === 'undefined'){
		response = {};
		response.key = 'CONNECTION_TIMEOUT';
		response.data = '';
		if(error != null && typeof error == 'function'){
			error(response.key,response.data);
		}else{
			alert(response.key);
		}
	}else if(response.success){
		if(success != null && typeof success == 'function'){
			success(response.data);
		}
	}else{
		if(error != null && typeof error == 'function'){
			error(response.key,response.data);
		}else{
			alert(response.key);
		}
	}
    return false;
}