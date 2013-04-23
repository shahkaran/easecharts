var base_url="http://localhost/chart_CI/";
var charts=new Array();
/*
function DrawChartWithoutOptions
Date:13/04/2013
*/
function DrawChartWithoutOptions(div_id,chart_obj_id)
{
	var url=base_url+"index.php/Chart_data/getChartData/"+chart_obj_id;
	
	$.getJSON(url,function(data){
		DrawChart(div_id,data);	
	});
	
	
}

/*
function DrawChartWithOptions

Author:karan Shah
Date:10/04/2013
*/
function DrawChartWithOptions(div_id,chart_obj_id)
{
	var url=base_url+"index.php/Chart_data/getChartData/"+chart_obj_id;
	
	$.getJSON(url,function(data){
		drawChartOptions(div_id,data)
		DrawChart(div_id,data);	


	//bind functions to menu
	$("#"+div_id+"_table").hide();

	$("[name='btn_only_chart']").live("click",function(e) {
		e.preventDefault();
      	var id=$(this).attr("value");
	    $("#"+id).slideDown();
		$("#"+id+"_table").slideUp();		
		$("[id='"+div_id+"_view'] ul img").each(function(index, element) {
					$(this).remove();
		});
		
		$(this).append("<img src='"+base_url+"images/z-green-tick.png' width=16px height=16px >");
    });

	$("[name='btn_only_tabular']").live("click",function(e) {
		e.preventDefault();
      	var id=$(this).attr("value");
	    $("#"+id).slideUp();
		$("#"+id+"_table").slideDown();		
		$("[id='"+div_id+"_view'] ul img").each(function(index, element) {
					$(this).remove();
		});

		$(this).append("<img src='"+base_url+"images/z-green-tick.png' width=16px height=16px >");
    });
	
	$("[name='btn_show_both']").live("click",function(e) {
		e.preventDefault();
      	var id=$(this).attr("value");
	    $("#"+id).slideDown();
		$("#"+id+"_table").slideDown();		
		$("[id='"+div_id+"_view'] ul img").each(function(index, element) {
					$(this).remove();
		});
		$(this).append("<img src='"+base_url+"images/z-green-tick.png' width=16px height=16px >");
    });


	$(".export").live("click",function(e) {
			var data=$(this).parent().parent().children("table").html();
			data="<table>"+data+"</table>";
			$(this).attr("action",base_url+"index.php/chart_misc/exportData");
			$(this).children("input[type='hidden']").val(data);
			console.log($(this).children("input[type='hidden']").val());
	});

	$("[name='btn_full_screen']").live("click",function(e) {
		e.preventDefault();
        var div_name=$(this).attr("value");
		
		$('#'+div_name).css("width", $(window).width()-30)
		$('#'+div_name).css("height",$(window).height()-30);		
		$('#'+div_name).css("border","#000 thick solid");
		$('#'+div_name).css("background-color","#000");
		$('#'+div_name).css("z-index",500);
		$('#'+div_name).css("top",0);
		$('#'+div_name).css("left",0);		
		$('#'+div_name).css("position","fixed");		
		$('#'+div_name).css("margin",0);				
		
		$('#'+div_name).click(function(e) {
			$('#'+div_name).css("width", 600)
			$('#'+div_name).css("height",400);		
			$('#'+div_name).css("border","none")
			$('#'+div_name).css("background-color","#FFF");
			$('#'+div_name).css("z-index",1);
			$('#'+div_name).css("position","");		

			ch.setSize(600,400);
	    });
		var ch=charts[div_name];
		ch.setSize($(window).width()-50,$(window).height()-50);
    	});

	}); 		
	
}

function DrawChart(div_id,data)
{
	
	var chart_types=["line","column","bar","pie"];

	//$("#chart"+tmp+"_cell .title").text(data.name);
	var type=chart_types[data.type-1];
	var chart_no=data.chart_no;
	var show_legend=(data.show_legend==1 ? true : false);
	var show_data_label=(data.show_datalabels==1 ? true : false);
	var show_exporting=(data.enable_exporting==1 ? true : false);
	var show_printing=(data.enable_printing==1 ? true :false);
	
	var options=getChartOptions(div_id,type);
	
	options.title.text=data.title;
	options.subtitle.text=data.subtitle;	
	
	if (!show_legend)
		options.legend.enabled=false;
		
	if (!show_data_label)
		options.plotOptions[type]["dataLabels"].enabled=false;
		
	if (!show_exporting)
		options.exporting.buttons.exportButton.enabled=false;
	
	if (!show_printing)
		options.exporting.buttons.printButton.enabled=false;
	
	
	var data_source_id=new Number(data.source);
	
	var chart_no=data.chart_no;
	$.getJSON(base_url+"index.php/query_master/executeQueryJson/"+data_source_id,{},function(chart_data){
	
		var category_name="";
		
		var series_name=new Array();
	
		$.ajax({
			async:false,
			type:"GET",
			url:base_url+"index.php/Chart_data/getCategoryName/"+chart_no,
			success: function(category){
							category_name=category;
				}
		});
	
		$.ajax({
			async:false,
			type:"GET",
			dataType:"json",
			url:base_url+"index.php/Chart_data/getSeriesName/"+chart_no,
			success: function(series){
							series_name=series;
				}
		});
			
		
		var cat_name=category_name;
		
		if (type==="pie")
		{
			options.series[0]=new Array();
			options.series[0]["type"]="pie";
			options.series[0]["data"]=new Array();
			$.each(chart_data[series_name[0]],function(index,value){
				options.series[0]["data"][index]=new Array();
				options.series[0]["data"][index].push(chart_data[cat_name][index]);
				options.series[0]["data"][index].push(parseFloat(value));					
			});
		}
		else
		{
			var series_main=new Array();
			//array to be used for series name and data
		
		
		  $.each(series_name,function(index,value){
			  series_main[index]=new Object();
			  series_main[index].name=series_name[index];
			  var datas=new Array();
			
			$.each(chart_data[series_name[index]],function(index,value){
				datas[index]=parseFloat(value);
			
			});
			
			series_main[index].data=datas;
		  });
		  
		options.xAxis.categories=chart_data[cat_name];
		options.series=series_main;
	
	  }
		  //step 4	  
	  chart= new Highcharts.Chart(options);
	 
 	  charts[div_id]=chart;  
	  
	  generateTable(chart,$("#"+div_id+"_table"));
	  
	  $.each(chart.series,function(index,item){
		  if (item.visible)
		  {
			  $("#"+div_id+"_sort ul").append("<li><a href='#' name='btn_sort_"+div_id+"'>"+item.name+"</a></li>");
		  }
	  });
	  
	  $("[name='btn_sort_"+div_id+"']").click(function(e) {
    			e.preventDefault();
				
				var div_id=$(this).attr("name");
				div_id=div_id.slice(div_id.lastIndexOf("_")+1);
				
				
				$("[name='btn_sort_"+div_id+"'] img").each(function(index, element) {
					$(this).remove();
				});
				$(this).append("<img src='"+base_url+"images/z-green-tick.png' width=16px height=16px >");
				
				var order=$("#"+div_id+"_order_value").val();
				if (order=="")
					order="asc";
				
				
				sorting(charts[div_id],$(this).text(),order);
				$("#"+div_id+"_sort_value").val($(this).text());
				generateTable(charts[div_id],$("#"+div_id+"_table"));				
		
		
	  
	    
	  });
		
	});

}

function drawChartOptions(div_id,data)
{
		var chart_object=data.obj_id;
		var show_sorting=(data.show_sorting==1 ? true : false);
		var show_tabular=(data.enable_tabulardata==1 ? true :false);
		var head_title=data.head_title;
		
		var html='<div class="chart_area chart" ><div class="main_m">'+
							'<div class="title">'+head_title+'</div>'+
							'<div class="nav1">'+
	 						'<ul>'+
							'<li><a href="#" name="btn_full_screen" value="'+div_id+'"><img src="'+base_url+'images/Full screen.png" title="View Chart in full screen" height=25px/></a></li>';
							
		if (show_sorting)			
					html+=  '<li id="'+div_id+'_sort"><a href="#"><img src="'+base_url+'images/sorting_a-z-icon.png" title="Sort Data" height=25px/></a>'+
							'<ul></ul>'+
							'<input type="hidden" id="'+div_id+'_sort_value" />'+
							'<input type="hidden" id="'+div_id+'_csort_value" />'+
							'<input type="hidden" id="'+div_id+'_order_value" />'+
							'<input type="hidden" id="'+div_id+'_corder_value" />'+							

							'</li>';
		if (show_tabular)
				html+=      '<li id="'+div_id+'_view"><a href value="'+div_id+'"><img src="'+base_url+'images/eye2.png" title="View Options" height=25px/></a>'+
							'<ul>'+
							'<li><a href="#" name="btn_only_chart" value="'+div_id+'" >Only Chart</a></li>'+
							'<li><a href="#" name="btn_only_tabular" value="'+div_id+'" >Only Tabular Data</a></li>'+
							'<li><a href="#" name="btn_show_both" value="'+div_id+'" >Both</a></li>'+				
							'</ul></li>';
				
				html+=     '</ul>'+
							'</ul>'+
							'</div>'+
							'</div>'+
							'<div id="'+div_id+'" class="main_chart" chart_obj="'+chart_object+'"></div><br/>'+
							'<div id="'+div_id+'_table"></div>'+
							'</div>'+
							'</div>';

		 var parent=$("#"+div_id).parent()
 		 $("#"+div_id).remove();
		 parent.append(html);
}


/*
Function generateChart

Author:Karan Shah
Date:09/02/2013


*/
function generateChart(location,chart_type,url)
{
	var options=getChartOptions(location,chart_type);
	var chart= new Highcharts.Chart(options);
	$.ajax({
		url:url,
		async:false,
		dataType:"json",
		success: function(data){

		  for (loop_x in data.series)
		  {	
			for (loop_y in data.series[loop_x].data)
			{
				data.series[loop_x].data[loop_y]=parseFloat(data.series[loop_x].data[loop_y]);
			}
		  }
		  
		  //step 3 
		  options.xAxis.categories=data.categories;
		
		  options.series=data.series;
			
		  //step 4	  
		  chart= new Highcharts.Chart(options);
		}
	});	
	
	return chart;
}
/*
function generateGanttChart
Author:Karan Shah
Date:27/02/2013*/
function generateGanttChart(location,url)
{
var chart;
var mcolors=['#4572A7', 
	'#AA4643', 
	'#89A54E', 
	'#80699B', 
	'#3D96AE', 
	'#DB843D', 
	'#92A8CD', 
	'#A47D7C', 
	'#B5CA92'];	
$.ajax({
		url:url,
		async:false,
		dataType:"json",
		success: function(tasks){
			var series = [];
			$.each(tasks.reverse(), function(i, task) {
					var starting_date_elements=task.starting.split("/");
					var ending_date_elements=task.ending.split("/");
					
					task.starting=Date.UTC(starting_date_elements[0],
											starting_date_elements[1]-1,
											starting_date_elements[2],
											starting_date_elements[3],
											starting_date_elements[4],
											starting_date_elements[5]);
					task.ending=Date.UTC(ending_date_elements[0],
										 ending_date_elements[1]-1,
										 ending_date_elements[2],
										 ending_date_elements[3],
										 ending_date_elements[4],
										 ending_date_elements[5]);
				
					var duration=task.ending-task.starting;
					var days=duration / (1000 * 3600 * 24);
					var completed_duration=(days*task.completed)/100;
					var completed_date=new Date(task.starting);
					completed_date.setDate(completed_date.getDate()+completed_duration);
	
					var compeleted_color="black"
					var completed_item={
						showInLegend: false, 
						name:"Completed",
						color: compeleted_color,
						data:[]
					}
				
					completed_item.data.push({
							x: task.starting,
							y: i,
							from:task.starting,
							to:completed_date,
							percentage:task.completed
						}, {
							x: completed_date,
							y: i,
							from:task.starting,
							to:completed_date,
							percentage:task.completed			
						});
						
				
					var item = {
						name: task.name,
						data: [],
						color:mcolors[i]
					};
				
				
					item.data.push({
							x: completed_date,
							y: i,
				
							from:completed_date,
							to:task.ending,
						}, {
							x: task.ending,
							y: i,
							color:'red',			
							from:task.starting,
							to:task.ending,
						});
        
        // add a null value between intervals
/*        if (task.intervals[j + 1]) {
            item.data.push(
                [(interval.to + task.intervals[j + 1].from) / 2, null]
            );
        }
*/
   
			series.push(completed_item);
		    series.push(item);
		});

	chart = new Highcharts.Chart({
		
			chart: {
				renderTo: location,
				zoomType: 'x'
			},
			scrollbar: {
				enabled: true
			},
			title: {
				text: 'Daily Activities'
			},
		
			xAxis: {
				tickPixelInterval: 100,
				type: 'datetime',
				minorTickInterval: 'auto',
				maxZoom: 48 * 3600 * 1000
		
			},
		   scrollbar: {
        enabled: true
    },

			yAxis: {
				tickInterval:1,
				labels: {
					formatter: function() {
						if (this.value>=0 && this.value<tasks.length)
							return tasks[this.value].name;
						else
							return '';

					}
				},
				startOnTick: false,
				endOnTick: false,
				title: {
					text: 'Tasks'
				},
					minPadding: 0.2,
						maxPadding: 0.2
			},
		
			legend: {
				enabled: true,
				labelFormatter: function() {
					var int_true_index=Math.floor(this.index/2);
					return this.name+"<br>"+Highcharts.dateFormat('%d/%m/%Y', tasks[int_true_index].starting)  +
						' - ' + Highcharts.dateFormat('%d/%m/%Y', tasks[int_true_index].ending)+"<br>100% completed";

/*					return this.name+"<br>"+Highcharts.dateFormat('%d/%m/%Y', tasks[int_true_index].starting)  +
						' - ' + Highcharts.dateFormat('%d/%m/%Y', tasks[int_true_index].ending)+"<br>"+tasks[int_true_index].completed+"% completed";*/
				},
				 itemMarginBottom:10,
				 reversed:true
			},
		
			tooltip: {
			 backgroundColor: {
					  linearGradient: [0, 0, 0, 60],
					  stops: [
						  [0, '#FFFFFF'],
						  [1, '#E0E0E0']
					  ]
				  },
				borderWidth: 1,
				borderColor: '#AAA',
				formatter: function() {
					if (this.series.name=="Completed")
						return '<b>'+ tasks[this.y].name + '</b><br/>' + this.percentage+"% completed";	
					else
						return '<b>'+ tasks[this.y].name + '</b><br/>' +
						Highcharts.dateFormat('%d/%m/%Y', this.point.options.from)  +
						' - ' + Highcharts.dateFormat('%d/%m/%Y', this.point.options.to); 
				}
			},
		
			plotOptions: {
				line: {
					lineWidth: 19,
					marker: {
						enabled: false,
						symbol:null,
						radius:0
					},
					dataLabels: {
						enabled: true,
						align: 'left',
						formatter: function() {
							return this.point.options && this.point.options.label;
						}
					},
					events: {
						  legendItemClick: function (event) {
								var int_true_index=this.index-1;
								if (this.visible)
									this.chart.series[int_true_index].hide();
								else
									this.chart.series[int_true_index].show();
						  }
					}
				}
			},
		
			series: series
		
		});
}});
return chart;	
}
/*
function generatGanttTable
Author:Karan Shah
Date:27/02/2013
*/
function generateGanttTable(chart,table_container)
{
	var table_txt="<table border=1px><tr><td>Task name</td><td>Starting date</td><td>Ending date</td><td>Completed %</td></tr>";
	var completed=false;
	var name="";
	var from=0;
	var to=0;
	var per=0;
	$.each(chart.series.reverse(),function(index,item){
		if (completed)
		{
			from=item.data[0].from;
			per=item.data[0].percentage;
			var abc=new Date();
			
			table_txt+="<tr>";
			table_txt+="<td>"+name+"</td>";
			table_txt+="<td>"+new Date(from).toLocaleDateString()+"</td>";		
			table_txt+="<td>"+new Date(to).toLocaleDateString()+"</td>";		
			table_txt+="<td>"+per+"% </td>";		
			table_txt+="</tr>";
			completed=false;
		}
		else
		{
			name=item.name;
			to=item.data[0].to;
			completed=true;
		}
			
	});
	$(table_container).html(table_txt);
}
/*
Function generateTable

Author:Karan Shah
Date:1/02/2013

Parameter: Object (Chart object to be used)
		   Object (Element where table is to be generated)	

Return: None

*/
function generateTable(obj_chart,obj_tableContainer)
{
	var table_name=obj_tableContainer.attr("id");
	var name=table_name.slice(0,table_name.indexOf("_"));

	var sort_variable_name="#"+name+"_sort_value";
	var order_variable_name="#"+name+"_order_value";
	var column_sort_variable_name="#"+name+"_csort_value";
	var column_order_variable_name="#"+name+"_corder_value";	
	
	var sortby=$(sort_variable_name).val();
	var orderby=$(order_variable_name).val();
	var column_sortby=$(column_sort_variable_name).val();
	var column_orderby=$(column_order_variable_name).val();
		
	var imageurl="";
	var column_imageurl="";
	
	if (orderby=="asc")
		imageurl="url("+base_url+"/images/table/table_sort_arrow_desc.gif) right no-repeat";
	else if (orderby=="desc")
		imageurl="url("+base_url+"/images/table/table_sort_arrow.gif) right no-repeat";
	
	if (column_orderby=="asc")
		column_imageurl="url("+base_url+"/images/table/table_sort_arrow_desc.gif) right no-repeat";
	else if (column_orderby=="desc")
		column_imageurl="url("+base_url+"/images/table/table_sort_arrow.gif) right no-repeat";
	
	
	
	if (typeof obj_chart.options.chart.type !== "undefined")
		var txt_table="<table border=1px><tr><td>&nbsp;</td>";
	else
		var txt_table="<table border=1px>";
	
	if (typeof obj_chart.options.chart.type !== "undefined"){
		
	var arr_txt_categories=obj_chart.xAxis[0].categories;
		$.each(arr_txt_categories,function(index,item){
			txt_table+="<td><a href='#' name='column_sort'>"+item+"</a></td>";
		});
		
		txt_table+="</tr>";
	
	
	$.each(obj_chart.series,function(index,item){
		if (item.visible)
		{
		  txt_table+="<tr><td class='sorting'><a href='#' name='row_sort'>"+item.name+"</a></td>";
		  $.each(item.data,function(index,item){
			  txt_table+="<td>"+item.y+"</td>";
		  });
		  txt_table+="</tr>";		
		}
	});
	
	}
	else
	{
		$.each(obj_chart.series,function(index,item){
			if (item.visible)
			{
			  var header="<tr>",data="<tr>";
			  $.each(item.data,function(index,item){
				  header+="<td>"+item.name+"</td>";
				  data+="<td>"+item.y+"</td>";
			  });
			  header+="</tr>";
			  data+="</tr>";
			  txt_table+=header+data;		
			}
		});	
		
	}
	
	
	txt_table+="</table><br><center><form action='#' method='POST' class='export'><input type='hidden' name='data'/><input type='submit' value='Export Tabular Data' /></form>";
	
	obj_tableContainer.html(txt_table);
	
	$(obj_tableContainer).children("table").css("width","50%");
	$(obj_tableContainer).children("table").css("border-collapse","collapse");	

	$(obj_tableContainer).children("table").children("tbody").children("tr").children("td").css("padding","2px");
	if (typeof obj_chart.options.chart.type !== "undefined")
		$(obj_tableContainer).children("table").children("tbody").children("tr:first").addClass("table-header-options");	
	$(obj_tableContainer).children("table").children("tbody").children("tr:odd").addClass("alternate-row");	
	$(obj_tableContainer).children("table").children("tbody").children("tr").children("td").children("[text='"+sortby+"']").css("background",imageurl);	
	$(obj_tableContainer).children("table").children("tbody").children("tr").children("td").children("[text='"+column_sortby+"']").css("background",column_imageurl);							
	
	
		$("[name='row_sort']").live("click",function(e){
			e.preventDefault();
			e.stopPropagation();
			var category=$(this).text();
			var table=$(this).parents("div [id$='_table']")[0];
			var table_name=table.id;
			//GET CHART name from above name
			var name=table_name.slice(0,table_name.indexOf("_"));
			
			//check order
			var image=$(this).css("background-image");
			if (image.search("table_sort_arrow.gif")==-1)
				order="desc"
			else
				order="asc"
			
			$("#"+name+"_order_value").val(order);
			
			//get order by 
			var ch=charts[name];
			//and call sort and generatetabe;
			sorting(ch,$(this).text(),order);
			$("#"+name+"_sort_value").val($(this).text());
			$(this).unbind();			
			generateTable(ch,$("#"+name+"_table"));				
		});
		
		$("[name='column_sort']").live("click",function(e){
				e.preventDefault();
				var category=$(this).text();
				var table=$(this).parents("div [id$='_table']")[0];
				var table_name=table.id;
				//GET CHART name from above name
				var name=table_name.slice(0,table_name.indexOf("_"));
				
				//check order
				var image=$(this).css("background-image");
				if (image.search("table_sort_arrow.gif")==-1)
					order="desc"
				else
					order="asc"
				
				$("#"+name+"_corder_value").val(order);
				
				
				var ch=charts[name];				
				//and call sort and generatetabe;
				columnsorting(ch,$(this).text(),order);
				$("#"+name+"_csort_value").val($(this).text());
				$(this).unbind();				
				generateTable(ch,$("#"+name+"_table"));				
		});


}
/*
function sorting
Author:Karan Shah
Date: 16/02/2013


Parameters:
-> chart object
-> Category name on which sorting is to be done
*/

function sorting(chart,category,order)
{
	var int_series_length=chart.series.length;
	var arr_indices=new Array();
	if (typeof order === "undefined")
		order="asc"
	//fetch series related to category specified
	$.each(chart.series,function(index,item){
		if (item.name==category)
		{
			var data_length=item.data.length;

			var arr_tobe_sorted=new Array();
			for (loop_i=0;loop_i<data_length;loop_i++)
			{
				arr_indices[loop_i]=loop_i;
				arr_tobe_sorted[loop_i]=item.data[loop_i].y;
			}
				
			
			
			for (loop_i=0;loop_i<data_length-1;loop_i++)
				for (loop_j=loop_i+1;loop_j<data_length;loop_j++)
				{
					var condition;
					if (order=="asc")
						condition=arr_tobe_sorted[loop_i] > arr_tobe_sorted[loop_j];
					else if (order=="desc")
						condition=arr_tobe_sorted[loop_i] < arr_tobe_sorted[loop_j];
					if (condition)
					{
						var temp=arr_tobe_sorted[loop_i];
						arr_tobe_sorted[loop_i]=arr_tobe_sorted[loop_j];
						arr_tobe_sorted[loop_j]=temp;
						
						temp=arr_indices[loop_i];
						arr_indices[loop_i]=arr_indices[loop_j];
						arr_indices[loop_j]=temp;
					}
				}

		}
	});
	var categories_length=chart.xAxis[0].categories.length;
	var arr_new_categories=new Array();
	for (loop_i=0;loop_i<categories_length;loop_i++)
		arr_new_categories[loop_i]=chart.xAxis[0].categories[arr_indices[loop_i]];
		

	for (loop_i=0;loop_i<int_series_length;loop_i++)
	{
	
		var arr_new_data=new Array();


		$.each(chart.series[loop_i].data,function(index,value){
			arr_new_data[index]=chart.series[loop_i].data[arr_indices[index]].y;
		});
		
		chart.series[loop_i].setData(arr_new_data,false);

	}
	chart.xAxis[0].setCategories(arr_new_categories);
	chart.redraw();	

	
}
/*
function columnsorting
Author:Karan Shah
Date: 22/02/2013


Parameters:
-> chart object
-> Category name on which sorting is to be done
-> order :- asc or desc
*/

function columnsorting(chart,category,order)
{
	var int_series_length=chart.series.length;
	var arr_indices=new Array();
	if (typeof order === "undefined")
		order="asc"
		

	$.each(chart.xAxis[0].categories,function(index,item){
			if (item==category)
			{
				var position=index;
				var arr_tobe_sorted=new Array();
				$.each(chart.series,function(index,item){
					arr_tobe_sorted[index]=item.data[position].y;
					arr_indices[index]=index;	
				});
				
				var data_length=arr_tobe_sorted.length;
				
				for (loop_i=0;loop_i<data_length-1;loop_i++)
					for (loop_j=loop_i+1;loop_j<data_length;loop_j++)
					{
						var condition;
						if (order=="asc")
							condition=arr_tobe_sorted[loop_i] > arr_tobe_sorted[loop_j];
						else if (order=="desc")
							condition=arr_tobe_sorted[loop_i] < arr_tobe_sorted[loop_j];
						if (condition)
						{
							var temp=arr_tobe_sorted[loop_i];
							arr_tobe_sorted[loop_i]=arr_tobe_sorted[loop_j];
							arr_tobe_sorted[loop_j]=temp;
							
							temp=arr_indices[loop_i];
							arr_indices[loop_i]=arr_indices[loop_j];
							arr_indices[loop_j]=temp;
						}
					}
			}
		
	});	
		
	console.log(arr_indices);		

	for (loop_i=0;loop_i<int_series_length;loop_i++)
	{
	
		var arr_new_data=new Array();


		var temp_series_name=chart.series[arr_indices[loop_i]].name;

		
		$.each(chart.series[loop_i].data,function(index,value){
			arr_new_data[index]=chart.series[arr_indices[loop_i]].data[index].y;
		});
		
		
		chart.addSeries({
			name:temp_series_name,
			data:arr_new_data
		});
		
//		chart.series[loop_i].setData(arr_new_data,false);

	}
	for (loop_i=0;loop_i<int_series_length;loop_i++)
	{
		chart.series[0].remove();
	}

//	chart.xAxis[0].setCategories(arr_new_categories);

	chart.redraw();	

	
}





/*
get chart options object
Author:Karan Shah
Date:09/02/2013
*/
function getChartOptions(location,type)
{
var options={
			 chart: {
				renderTo: location,
				height:400,
				width:600,
				animation: {
				  duration: 500,
				  easing: 'swing'
				},
				events: {
					//load: Highcharts.drawTable
				}
			 },
			 title: {
				text: ''
			 },
			 subtitle:{
				text:''
			 },
			 xAxis: {
				categories: []
			 },
			legend: {
					layout: 'horizontal',
					backgroundColor: '#000000',
					align: 'bottom',
					verticalAlign: 'bottom',
		
					floating: false,
					shadow: true,
				},		 
			 yAxis: {
				title: {
				   text: ''
				}
			 },
			 series:[],
			 plotOptions: {},
			 exporting:
			 {
           	 	buttons: {
					printButton:{
                    	enabled:true
                	},
                	exportButton: {
                    	enabled:true
                	}
				}
			}
      };
	  if (type!=="pie")
		  options.chart["type"]=type;
	  options.plotOptions[type]={
				  
					  events: {
						  legendItemClick: function (event) {
								if (this.visible)
									 this.hide();
								else
									this.show();
								generateTable(this.chart,$("#"+location+"_table"));				
								 return false;
							  //return false; // <== returning false will cancel the default action
						  }
					  }
				  , 
				  showInLegend: true
			  }	
			var rotate,labelX,labelY;
			if (type==="bar")
			{
				rotate=0;
				labelX=-1;
				labelY=2;
			}
			else if (type=="column")
			{
				rotate=-90;
				labelX=3;
				labelY=3;	
			}
			options.plotOptions[type]["dataLabels"]={
						enabled: true,
						rotation: rotate,
						color: '#000000',
						align: 'right',
						x: labelX,
						y: labelY,
						overflow: 'justify',
						formatter: function() {
							if (type!=="pie")
							  return this.y;
							else
							  return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
						},
						style: {
						  font: 'bold 10px Verdana, sans-serif'
						}
					}
		

	return options;
}

