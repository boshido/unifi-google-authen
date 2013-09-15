var ap_table;
$(document).ready(function(){	
	
	ap_table = $('#ap-table').dataTable( {
		"sDom": "<r><t><i><p>",
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"iDisplayLength": 15,
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "key", "value": "element" } );
		},
		"aoColumns":[
			{"mDataProp":"name","sDefaultContent": ""},	
			{"mDataProp":"ip"},
			{	
				"sDefaultContent": "Disconnected",
				"mRender": function (data, type, full) {			
					return full.state == 1 ? 'Connected' : + "Disconnected";
				},
				"sClass":'text-center'
			},
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
				"sClass":'text-right'
			},
			{"mDataProp":"ng-channel","sDefaultContent": "","sClass":'text-center'},
			{
				"sDefaultContent": 'None',
				"bSortable": false,
				"mRender": function (data, type, full) {			
					
					return '<a class="text-alert" style="cursor:pointer" onclick="restartap(\''+full.mac+'\',1)">Restart</a>'
				},
				"sClass":'text-center'
			}
		]
	} );
	$('#ap .search').keyup(function(){
		ap_table.fnFilter( $(this).val() );
	});
});