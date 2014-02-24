<!DOCTYPE html>
<html>
<head>
	<title>Chart Analysis</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
	
	<script type="text/javascript">

          var form_id = "<?php echo $_REQUEST['formid']; ?>";
          var url = "aaa.php?formid=" + form_id;
          console.log(url);
        $( document ).ready(function() {

          
/*
       
 */

        // $.ajaxSetup({ scriptCharset: "utf-8" , contentType: "application/json; charset=utf-8"});

            $.getJSON(url, function (datas) {
            		var index = 0;

                    for( var y in datas){
                       
                        if(datas[y].type == "multiple_choice")
                        {   
                                var renderId = "multiple_choice" + index ;
                                 $("#graph").append("<div id='" + renderId + "'></div>");

                                var options = {
                                    chart: {
                                             renderTo: renderId,
                                             plotBackgroundColor: null,
                                             plotBorderWidth: null,
                                             plotShadow: false
                                           },
                                    title: {
                                             text: datas[y].label
                                           },
                                    tooltip: {
                                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                            },
                                    plotOptions: {
                                                    pie: {
                                                            allowPointSelect: true,
                                                            cursor: 'pointer',
                                                            dataLabels: {
                                                                        enabled: true,
                                                                        color: '#000000',
                                                                        connectorColor: '#000000',
                                                                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                                                                    }
                                                }
                                             },
                                                credits: {enabled: false},
                                    series: [datas[y].value]
                                    };
                            var chart = new Highcharts.Chart(options);  
                            //console.log(datas) ;  
                        }
                        if(datas[y].type == "drop_down")
                        {   
                                var renderId = "drop_down" + index ;
                                 $("#graph").append("<div id='" + renderId + "'></div>");
                                var options = {
                                    chart: {
                                             renderTo: renderId,
                                             plotBackgroundColor: null,
                                             plotBorderWidth: null,
                                             plotShadow: false
                                           },
                                    title: {
                                             text: datas[y].label
                                           },
                                    tooltip: {
                                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                            },
                                    plotOptions: {
                                                    pie: {
                                                            allowPointSelect: true,
                                                            cursor: 'pointer',
                                                            dataLabels: {
                                                                        enabled: true,
                                                                        color: '#000000',
                                                                        connectorColor: '#000000',
                                                                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                                                                    }
                                                }
                                             },
                                                credits: {enabled: false},
                                    series: [datas[y].value]
                                    };
                            var chart = new Highcharts.Chart(options);  
                            //console.log(datas) ;  
                        }
                        if(datas[y].type == "checkboxes")
                        {   

                        	var renderId = "checkboxes" + index ;
                            $("#graph").append("<div id='" + renderId + "'></div>");
                            var id = "#" + renderId;
                            $(id).highcharts({
                                                        chart: 
                                                        {
                                                        	
                                                            type: 'column',
                                                            margin: [ 50, 50, 100, 80]
                                                        },
                                                        title: 
                                                        {
                                                            text: datas[y].label
                                                        },
                                                        xAxis: 
                                                        {
                                                                categories: 
                                                                              datas[y].valuestr 
                                                                            ,
                                                                    labels: 
                                                                    {
                                                                        rotation: -45,
                                                                        align: 'right',
                                                                        style: 
                                                                        {
                                                                           fontSize: '13px',
                                                                            fontFamily: 'Verdana, sans-serif'
                                                                        }
                                                                    }
                                                        },
                                                        yAxis: 
                                                        {
                                                             min: 0,
                                                             title: 
                                                             {
                                                                text: ''
                                                             }
                                                        },
                                                        legend: 
                                                        {
                                                            enabled: false
                                                        },
                                                        tooltip: 
                                                        {
                                                            pointFormat: 'Population : <b>{point.y:.1f} </b>',
                                                        },
                                                           credits: {enabled: false},
                                                series: [{
                                                            name: 'Population',
                                                            data: datas[y].valueint,

                                                            dataLabels: {
                                                                            enabled: true,
                                                                            rotation: -90,
                                                                            color: '#FFFFFF',
                                                                            align: 'right',
                                                                            x: 4,
                                                                            y: 10,
                                                                            style: 
                                                                            {
                                                                                fontSize: '13px',
                                                                                fontFamily: 'Verdana, sans-serif',
                                                                                textShadow: '0 0 3px black'
                                                                            }
                                                                         }
                                                        }]
                                                    });
                        }
                        if(datas[y].type == "likert")
                        {   
                        	var renderId = "likert" + index ;
                            $("#graph").append("<div id='" + renderId + "'></div>");
                            var id = "#" + renderId;
                            $(id).highcharts({
                                                        chart: 
                                                        {
                                                            type: 'column',
                                                            margin: [ 50, 50, 100, 80]
                                                        },
                                                        title: 
                                                        {
                                                            text: datas[y].label
                                                        },
                                                        xAxis: 
                                                        {
                                                                categories: 
                                                                              datas[y].valuestr 
                                                                            ,
                                                                    labels: 
                                                                    {
                                                                        rotation: -45,
                                                                        align: 'right',
                                                                        style: 
                                                                        {
                                                                           fontSize: '13px',
                                                                            fontFamily: 'Verdana, sans-serif'
                                                                        }
                                                                    }
                                                        },
                                                        yAxis: 
                                                        {
                                                             min: 0,
                                                             title: 
                                                             {
                                                                text: ''
                                                             }
                                                        },
                                                        legend: 
                                                        {
                                                            enabled: false
                                                        },
                                                        tooltip: 
                                                        {
                                                            pointFormat: 'Population : <b>{point.y:.1f} </b>',
                                                        },
                                                           credits: {enabled: false},
                                                series: [{
                                                            name: 'Population',
                                                            data: datas[y].valueint,

                                                            dataLabels: {
                                                                            enabled: true,
                                                                            rotation: -90,
                                                                            color: '#FFFFFF',
                                                                            align: 'right',
                                                                            x: 4,
                                                                            y: 10,
                                                                            style: 
                                                                            {
                                                                                fontSize: '13px',
                                                                                fontFamily: 'Verdana, sans-serif',
                                                                                textShadow: '0 0 3px black'
                                                                            }
                                                                         }
                                                        }]
                                                    });
                        }   

                       // console.log(datas) ; 
                        ++index;
                    }   // end for    
            
            });

        });

    

	</script>
</head>
<body bgcolor = "#ffe587" >
	<div id="graph" style="max-width: 700px; height: 700px; margin-left: auto; margin-right: auto; "  ></div>
</body>
</html>