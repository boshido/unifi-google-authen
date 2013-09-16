$(document).ready(function(){	
	var google = $('#authorize .google.typeahead').typeahead({                              
		name: 'google', 
		valueKey: 'name',
		limit :10,
		remote: {
			url:'/unifi/typeahead-google?search=%QUERY'
		},                                          
		template: '<p style="overflow:hidden;text-overflow: ellipsis;white-space:nowrap!important;">{{fname}} {{lname}}</p>',
		engine: Hogan                                                  
	});
	google.on('typeahead:selected',function(evt,data){
		$('#google-fname').val(data.fname);
		$('#google-lname').val(data.lname);
		$('#google-email').val(data.email);
		$('#google-id').val(data.google_id);
	});
	
	var device = $('#authorize .device.typeahead').typeahead({                              
		name: 'device', 
		valueKey: 'hostname',
		limit :10,
		remote: {
			url:'/unifi/typeahead-device?search=%QUERY'
		},                                          
		template: '<p style="overflow:hidden;text-overflow: ellipsis;white-space:nowrap!important;">{{hostname}}</p>',
		engine: Hogan                                                  
	});
	device.on('typeahead:selected',function(evt,data){
		if(typeof(data.hostname) != 'undefined')$('#device-name').val(data.hostname);
		$('#device-mac').val(data.mac);
	});
});