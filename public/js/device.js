var device_table;
$(document).ready(function(){	
	
	device_table = $('#device-table').dataTable( {
		"sAjaxDataProp": "data",
		"sDom": "<r><t><i><p>",
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"iDisplayLength": 15,
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "key", "value": "element" } );
		},
		"aoColumns":[
			{"sDefaultContent": "None",
				"mDataProp": function ( source, type, val ) {		
					if (type === 'set') {
						var name;
						if(source.hostname != null) name = source.hostname;
						else  name = source.mac;

						if(source.is_online == 1) 
							source.name_rendered = '<img src="/img/admin/online.png" style="margin-right:3px;">'+name;
						else
							source.name_rendered = '<img src="/img/admin/offline.png" style="margin-right:3px;">'+name;
						
					  	return;
					}
					else if (type === 'display' || type === 'filter') {
					  return source.name_rendered;
					}
					if(source.hostname != null)return source.hostname;
					else return source.mac;
				},
				"sClass":'table-overflow-hidden'
			},	
			{
				"sDefaultContent": '<span class="text-alert">Pending</span>',
				"mRender": function (data, type, full) {			
					if(full.is_auth)return '<span class="text-success">Authorized</span>';
					else return '<span class="text-alert">Pending</span>';			
				},
				"sClass":'text-center'
			},
			{"mDataProp":"ip","sDefaultContent": "-","sClass":'text-center'},	
			{
				"sDefaultContent": 'None',
				"mDataProp": function ( source, type, val ) {		
					if (type === 'set') {
						if(typeof(source.tx_bytes) != 'undefined'){
					 		source.download_rendered = getUnit(source.tx_bytes);
					 	}
					 	else{
					 		source.download_rendered = '-';
					 	}
					  return;
					}
					else if (type === 'display' || type === 'filter') {
					  return source.download_rendered;
					}
					// 'sort' and 'type' both just use the raw data
					if(typeof(source.tx_bytes) != 'undefined'){
				  		return source.tx_bytes;
				  	}
				  	else{
				  		return '-';
				  	}
				},
				"sClass":'text-right'
			},
			{
				"sDefaultContent": 'None',
				"mDataProp": function ( source, type, val ) {	

					if (type === 'set') {
						if(typeof(source.rx_bytes) != 'undefined'){
					  		source.upload_rendered = getUnit(source.rx_bytes);
					  	}
					  	else{
					  		source.upload_rendered = '-';
					  	}

					  return;
					}
					else if (type === 'display' || type === 'filter') {
					  return source.upload_rendered;
					}
					// 'sort' and 'type' both just use the raw data
					if(typeof(source.rx_bytes) != 'undefined'){
				  		return source.rx_bytes;
				  	}
				  	else{
				  		return '-';
				  	}
					
				},
				"sClass":'text-right'
			},
			{
				"sDefaultContent": 'None',
				"mDataProp": function ( source, type, val ) {
					
					if (type === 'set') {
						if(typeof(source['bytes.r']) != 'undefined'){
					  		source.activity_rendered = getUnit(source['bytes.r'])+'/sec';
					  	}
					  	else{
					  		source.activity_rendered = '-'
					  	}
					  return;
					}
					else if (type === 'display' || type === 'filter') {
					  return source.activity_rendered;
					}
					// 'sort' and 'type' both just use the raw data
					if(typeof(source['bytes.r']) != 'undefined'){
				  		return source['bytes.r'];
				  	}
				  	else{
				  		return '-';
				  	}
					
				},
				"sClass":'text-center'
			},
			{	
				"sDefaultContent": '<span class="text-alert">None</span>',
				"bSortable": false,
				"mRender": function (data, type, full) {		
					var info = $('<div>').append($('<a class="modal-button text-success" href="#" >Show Info</a>').attr('data-mac',full.mac)
						.attr('data-id',full.google_id).attr('data-name',full.name)).html();
					var block = $('<div>').append($('<a class="text-alert" style="cursor:pointer" onclick="block(\''+full.mac+'\',1)">Block</a>')).html();
					var unblock = $('<div>').append($('<a class="text-alert" style="cursor:pointer" onclick="block(\''+full.mac+'\',0)">Unblock</a>')).html();

					if(typeof(full.google_id) != 'undefined'){

						if(typeof(full.blocked)!='undefined'){
							if(full.blocked)return info + ' | ' + unblock;
							else return info + ' | ' + block;
						}
						else return info + ' | ' + block;
					}
					else if(full.is_auth==1) return '<a class="text-alert" style="cursor:pointer" onclick="unauthorize(\''+full.mac+'\')">Unauthorize</a>'
					else {
						var hostname = full.hostname != null ? full.hostname : full.mac;
						return '<a class="text-success" style="cursor:pointer" onclick="goto_authorize(\''+full.mac+'\',\''+hostname+'\')">Authorize</a> | '+
						'<a class="text-alert" style="cursor:pointer" onclick="block(\''+full.mac+'\',1)">Block</a>'
					}
				},
				"sClass":'text-center'
			},
			{"mDataProp":"is_auth","sDefaultContent": 0,'bVisible':false},
			{"mDataProp":"is_online","sDefaultContent": 0,'bVisible':false}
		]
	} );
	
	device_table.fnFilter( 1,7);
	device_table.fnFilter( 1,8);

	$('#device .search').keyup(function(){
		device_table.fnFilter( $(this).val() );
	});
	$('#device .toggle').on('change',function(){
		if($(this).attr('id')=='toggle-device-offline') {device_table.fnFilter( '',7);device_table.fnFilter( 0,8);}
		else if($(this).attr('id')=='toggle-device-authorized') {device_table.fnFilter( 1,7);device_table.fnFilter( 1,8);}
		else if($(this).attr('id')=='toggle-device-pending') {device_table.fnFilter( 0,7);device_table.fnFilter( 1,8);}
		else if($(this).attr('id')=='toggle-device-all') {device_table.fnFilter( '',7);device_table.fnFilter( '',8);}
	});
	$('#device').on('click','.modal-button',function(event){
		selected_google_id= $(this).attr('data-id');
		
		$('#owner').html($(this).attr('data-name')+"'s Device")
		authorized(selected_google_id,$(this).attr('data-mac'));
		userhistory(selected_google_id);
		
		$('.overlay').fadeIn('fast');
		$('.modal').fadeIn('fast');
		
		$('.modal-tab').hide();
		$('.modal-item-list').removeClass('selected');
		$('#modal-alert').show();
		$('.modal-footer').empty();
	});
});

function goto_authorize(mac,hostname){
	$('.menu-item-list[href="#authorize"]').click();
	$('#device-name').val(hostname);
	$('#device-mac').val(mac);
	
}