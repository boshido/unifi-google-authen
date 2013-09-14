var device_table;
$(document).ready(function(){	
	
	device_table = $('#device-table').dataTable( {
		"sDom": "<r><t><i><p>",
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"iDisplayLength": 15,
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "key", "value": "element" } );
		},
		//"sAjaxSource": "{{action('UnifiController@getUserTable')}}",
		"aoColumns":[
			{"mDataProp":"hostname","sDefaultContent": ""},	
			{
				"sDefaultContent": 'Pending',
				"mRender": function (data, type, full) {			
					if(full.is_auth)return 'Authorized';
					else return 'Pending';			
				},
				"sClass":'text-center'
			},
			{"mDataProp":"ip","sDefaultContent": ""},	
			{
				"sDefaultContent": 'None',
				"mRender": function (data, type, full) {			
					
					return getUnit(full.tx_bytes);
				},
				"sClass":'text-right'
			},
			{
				"sDefaultContent": 'None',
				"mRender": function (data, type, full) {			
					
					return getUnit(full.rx_bytes);
				},
				"sClass":'text-center'
			},
			{
				"sDefaultContent": 'None',
				"mRender": function (data, type, full) {			
					
					return getUnit(full['bytes.r'])+'/sec';
				},
				"sClass":'text-center'
			},
			{	
				"sDefaultContent": '<span class="text-alert">None</span>',
				"bSortable": false,
				"mRender": function (data, type, full) {		
					var tmp = {};
					return $('<div>').append($('<a class="modal-button text-success" href="#" >Show Information</a>').attr('data-mac',full.mac)
					.attr('data-id',full.google_id).attr('data-name',full.name)).html();
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
		if($(this).attr('id')=='toggle-authorized') device_table.fnFilter( 1,7);
		else if($(this).attr('id')=='toggle-pending') device_table.fnFilter( 0,7);
		else if($(this).attr('id')=='toggle-all-device') device_table.fnFilter( '',7);
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