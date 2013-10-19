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
			{
				"sDefaultContent": '',
				"mRender": function (data, type, full) {	
					if(typeof(full.name) != 'undefined')return full.name;
					else return full.mac;
				},
				"sClass":'table-overflow-hidden'
			},	
			{"mDataProp":"ip"},
			{	
				"sDefaultContent": '<span class="text-alert">Disconnected</span>',
				"mRender": function (data, type, full) {			
					return full.state == 1 ? '<span class="text-success">Connected</span>' : '<span class="text-alert">Disconnected</span>';
				},
				"sClass":'text-center'
			},
			{
				"sDefaultContent": '0.00',
				"mDataProp": function ( source, type, val ) {	
					if(typeof(source.tx_bytes) != 'undefined'){
						if (type === 'set') {
						  source.download_rendered = getUnit(source.tx_bytes);
						  return;
						}
						else if (type === 'display' || type === 'filter') {
						  return source.download_rendered;
						}
						// 'sort' and 'type' both just use the raw data
						return source.tx_bytes;
					}
					return;
				},
				"sClass":'text-right'
			},
			{
				"sDefaultContent": '0.00',
				"mDataProp": function ( source, type, val ) {	
					if(typeof(source.rx_bytes) != 'undefined'){
						if (type === 'set') {
						  source.upload_rendered = getUnit(source.rx_bytes);
						  return;
						}
						else if (type === 'display' || type === 'filter') {
						  return source.upload_rendered;
						}
						// 'sort' and 'type' both just use the raw data
						return source.rx_bytes;
					}
					return;
				},
				"sClass":'text-right'
			},
			{"mDataProp":"ng-channel","sDefaultContent": "","sClass":'text-center'},
			{
				"sDefaultContent": '',
				"bSortable": false,
				"mRender": function (data, type, full) {			
					if(full.state == 1 ) return '<a class="text-alert" style="cursor:pointer" onclick="restartap(\''+full.mac+'\',1)">Restart</a>'
				},
				"sClass":'text-center'
			}
		]
	} );
	$('#ap .search').keyup(function(){
		ap_table.fnFilter( $(this).val() );
	});
});