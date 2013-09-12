$(document).ready(function(){	
	user_table = $('#user-table').dataTable( {
		"sDom": "<r><t><i><p>",
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"iDisplayLength": 15,
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "key", "value": "element" } );
		},
		//"sAjaxSource": "{{action('UnifiController@getUserTable')}}",
		"aoColumns":[
			{"mDataProp":"name","sDefaultContent": ""},	
			{"mDataProp":"email"},
			{	
				"sDefaultContent": "0",
				"mRender": function (data, type, full) {			
					return full.online+ full.offline;
				},
				"sClass":'text-center'
			},	
			{	
				"sDefaultContent": '<span class="text-alert">None</span>',
				"bSortable": false,
				"mRender": function (data, type, full) {		
					var tmp = {};
					return $('<div>').append($('<a class="modal-button text-success" href="#" >Show Devices</a>').attr('data-id',full.google_id).attr('data-name',full.name)).html();
				},
				"sClass":'text-center'
			},
			{"mDataProp":"status","sDefaultContent": 0,'bVisible':false}
		]
	} );
	user_table.fnFilter( 1,4);
	$('#user .search').keyup(function(){
		user_table.fnFilter( $(this).val() );
	});
	$('#user .toggle').on('change',function(){
		if($(this).attr('id')=='toggle-online') user_table.fnFilter( 1,4);
		else if($(this).attr('id')=='toggle-offline') user_table.fnFilter( 0,4);
		else if($(this).attr('id')=='toggle-all') user_table.fnFilter( '',4);
	});
	$('#user').on('click','.modal-button',function(event){
		
		$('#device-list').empty();
		$('#history-list').empty();
		var google_id = $(this).attr('data-id');
		
		$('#owner').html($(this).attr('data-name')+"'s Device");
		authorized(google_id);
		userhistory(google_id);
		
		$('.overlay').fadeIn('fast').click(function(){ $(this).fadeOut('fast');$('.modal').fadeOut('fast');});
		$('.modal').fadeIn('fast');
		
		$('.modal-tab').hide();
		$('.modal-item-list').removeClass('selected');
		$('#modal-alert').show();
	});
	
	$('body').on('click','.close',function(){
		$('.overlay').fadeOut('fast');
		$('.modal').fadeOut('fast');
	});
	$('#device-list, #history-list').on('click','.device-item-list',function(){
		$('.device-item-list').removeClass('selected');
		$(this).addClass('selected');
		selected_mac = $(this).attr('data-mac');
		$('.modal-item-list[href="modal-user"]').click();
		
	});

	$('#modal-list').on('click','.modal-item-list',function(event){
		event.preventDefault();
		if($('.device-item-list.selected').length >0){
			$('.modal-item-list').removeClass('selected');
			$('.modal-tab').hide();
			$('#'+$(this).attr('href')).show();
			$(this).addClass('selected');
			if($(this).attr('href')=='modal-user'){
				device();
			}
			else if($(this).attr('href')=='modal-statistic'){
				dailystat();
			}
			else if($(this).attr('href')=='modal-history'){
				history();
			}
		}
	});
});