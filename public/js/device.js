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
				"mRender": function (data, type, full) {
					if(full.hostname != null)return full.hostname;
					else return full.mac;
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
			{"mDataProp":"ip","sDefaultContent": ""},	
			{
				"sDefaultContent": 'None',
				"mDataProp": function ( source, type, val ) {		
					if (type === 'set') {
					  source.download_rendered = getUnit(source.tx_bytes);
					  return;
					}
					else if (type === 'display' || type === 'filter') {
					  return source.download_rendered;
					}
					// 'sort' and 'type' both just use the raw data
					return source.tx_bytes;
				},
				"sClass":'text-right'
			},
			{
				"sDefaultContent": 'None',
				"mDataProp": function ( source, type, val ) {		
					if (type === 'set') {
					  source.upload_rendered = getUnit(source.rx_bytes);
					  return;
					}
					else if (type === 'display' || type === 'filter') {
					  return source.upload_rendered;
					}
					// 'sort' and 'type' both just use the raw data
					return source.rx_bytes;
				},
				"sClass":'text-right'
			},
			{
				"sDefaultContent": 'None',
				"mDataProp": function ( source, type, val ) {
					if (type === 'set') {
					  source.activity_rendered = getUnit(source['bytes.r'])+'/sec';
					  return;
					}
					else if (type === 'display' || type === 'filter') {
					  return source.activity_rendered;
					}
					// 'sort' and 'type' both just use the raw data
					return source['bytes.r'];
				},
				"sClass":'text-center'
			},
			{	
				"sDefaultContent": '<span class="text-alert">None</span>',
				"bSortable": false,
				"mRender": function (data, type, full) {		
					if(typeof(full.google_id) != 'undefined'){
						return $('<div>').append($('<a class="modal-button text-success" href="#" >Show Information</a>').attr('data-mac',full.mac)
						.attr('data-id',full.google_id).attr('data-name',full.name)).html();
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
			{"mDataProp":"is_auth","sDefaultContent": 0,'bVisible':false}
		]
	} );
	device_table.fnFilter( 1,7);
	$('#device .search').keyup(function(){
		device_table.fnFilter( $(this).val() );
	});
	$('#device .toggle').on('change',function(){
		if($(this).attr('id')=='toggle-device-offline') device_table.fnFilter( 2,7);
		else if($(this).attr('id')=='toggle-device-authorized') device_table.fnFilter( 1,7);
		else if($(this).attr('id')=='toggle-device-pending') device_table.fnFilter( 0,7);
		else if($(this).attr('id')=='toggle-device-all') device_table.fnFilter( '',7);
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