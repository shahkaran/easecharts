<div id="content"><script type="text/javascript">
$(document).ready(function(){
	//hide setting buttons
	$("#settings").hide();
	$("#settings_icon").click(function(e) {
        	$("#settings").slideToggle();
    });
	//intialize data source id with negative value to indicate that no data source is selected till now
	var data_source_id=-1;

	//hide the query row and output row 
	// to be shown when appropriate button is clicked
	// also hide when data source is changed
	$(".hide_query,.hide_output").hide();
	$("#source").change(function(e) {
        	$(".hide_query,.hide_output").hide();
    });
	
	//show preview of charts 
	function show_chart(e)
	{

		 var chart_type=$(this).text();
		 chart_type=chart_type.toLowerCase();
		 chart_type=chart_type.replace(" ","_");
		 
		 var height=$(".preview_window").height();
		 var width=$(".preview_window").width();
		 
		 $(".preview_window").html("<img src=\"<?php echo base_url();?>images\\"+chart_type+".png\" />");
		 
		 $(".preview_window img").css("height",height-10);
		 $(".preview_window img").css("width",width-10);
		 if (e.type=="click")
		 	$(this).attr("clicked","true");
		else
			$(this).attr("clicked","false");
		
	}
	
	
	$(".chart_type li").hover(show_chart,function(){
		if ($(this).attr("clicked")=="false")
			$(".preview_window").html("&nbsp;");		
		
	});
	
	$(".chart_type li").click(show_chart);
	//fetch dataSource names using ajax and add each data source name in dropdown 
	$.getJSON("<?php echo base_url();?>index.php/query_master/getDataSources",{},function(data){
		var options_array=data;
		var html_to_add="";
		$.each(options_array,function(index,obj){
			html_to_add+="<option value='"+obj.id+"'>"+obj.id+". "+obj.name;
			
		});
		$("#source").append(html_to_add);
		
	});
	
	//load the new query window
	$("#new_query").click(function(e) {
        document.location.href="<?php echo base_url() ?>index.php/query_master/NewQuery";
    });

	//code for navigating through wizard steps
	// and doing certain things on reaching a particular step
	
	//wizard steps are actually multiple div given wizard class which has display property as none
	
	//next prev button have number of page where they are used to navigate
	
	$(".wizard-navigation-next,.wizard-navigation-prev").click(function(e) {
    	
		//get page no from the button		
		var page=$(this).attr("goto");

		//remove wizard current class from all div making all steps invisible
		$(".wizard-current").removeClass("wizard-current");
		
		//assign wizard-current class to the page where we need to navigate
		$("#step"+page).addClass("wizard-current");
		
		//make changes to wizard flow divs 
		$(".step-no").removeClass("step-no")
					 .addClass("step-no-off")
					 .next("div.step-dark-left").removeClass("step-dark-left").addClass("step-light-left")
 					 .next("div.step-dark-right").removeClass("step-dark-right").addClass("step-light-right");
		$(".step-no-off:contains("+page+")").addClass("step-no").removeClass("step-no-off")
					 .next("div.step-light-left").removeClass("step-light-left").addClass("step-dark-left")
	 				 .next("div.step-light-right").removeClass("step-light-right").addClass("step-dark-right");
					 
		//page 3 is of selecting fields for categories and series
		
		if (page==3)
		{
			//first we check if data source has changed from previous selection or not
			if ($("#source").val()!=data_source_id)
			{
				//fecth data source id 
				data_source_id=$("#source").val();
				
				//fetch type of chart
				var type=$("#chart_type").val();
				
				//set category_placeholder , series_placeholder and field_for_selection div to their original style
				// and also enable dropping on category placeholder
				$("#category_placeholder").removeClass().addClass("placeholder").droppable("enable");
				$("#category_placeholder").html("Add Category");				
				
				$("#series_placeholder").removeClass().addClass("placeholder");
				$("#field_for_selection").children("ul.btn_list_container").remove();
				$("#series_placeholder").html("Add Series");				
								
				//fetch fields with their datatype using ajax 
				$.getJSON("<?php echo base_url();?>index.php/query_master/getFieldsInfo/"+$("#source").val(),{},function(data){
					
					if (data.length>0)
					{
						//if no of fields returned are more then 0 then add them into field_for_selection div 
						// and make them droppable
						var list="";
						list+="<ul class='btn_list_container'>";
						$.each(data,function(index,field){
							list+="<li datatype='"+field.field_type+"' class='query_fields'>"+field.field_name+"</li>";	
							
						});
						list+="</ul>";
						$("#field_for_selection").append(list);
						$("#field_for_selection").children("ul.btn_list_container").children("li").draggable({
							revert:"invalid"
						});
		
					}
					
				});
			}
		}
		//page no 5 is for preview of chart generated
		if (page==5)
		{
			//fetch output of query 
			$.getJSON("<?php echo base_url()?>index.php/query_master/executeQueryJson/"+data_source_id,{},function(data){
				
				//get chart type , category name selected , series fields selected , title , subtitle provided and if legend is to be shown or not 
				
				var type=$("#chart_type").val();
				var category_name=$("#category_placeholder").children("div.data").text();
				var series_name=$("#series_placeholder").children("div.data");

				//get all series name
				var series=new Array();	
				$.each(series_name,function(index,value){
					series.push($(value).text());
					
				});
				
				var options=getChartOptions("chart_area",type);
				var head_title=$("#chart_headtitle").val();
				var title=$("#chart_title").val();
				var sub_title=$("#chart_subtitle").val();
				var chart_object=$("#chart_objectid").val();
				var show_legend=$("#chart_showLegend").attr("checked");
				var show_data_label=$("#chart_datalabels").attr("checked");
				var show_sorting=$("#chart_sorting").attr("checked");
				var show_exporting=$("#chart_exporting").attr("checked");
				var show_printing=$("#chart_printing").attr("checked");
				var show_tabular=$("#chart_tabular").attr("checked");																

				var cat_name=category_name;

				options.title.text=title;
				options.subtitle.text=sub_title;	
				
				if (type!=="pie")
				{
						var series_main=new Array();
						//array to be used for series name and data
						
						$.each(series,function(index,value){
							//series[index]=value.split(".")[1];
							
							series_main[index]=new Object();
							series_main[index].name=series[index];
							var datas=new Array();
							$.each(data[series[index]],function(index,value){
								datas[index]=parseFloat(value);
								
							});
							
							series_main[index].data=datas;
						});
						options.xAxis.categories=data[cat_name];
						options.series=series_main;
				}
				else
				{	
					options.series[0]=new Array();
					options.series[0]["type"]="pie";
					options.series[0]["data"]=new Array();
					$.each(data[series[0]],function(index,value){
						options.series[0]["data"][index]=new Array();
						options.series[0]["data"][index].push(data[cat_name][index]);
						options.series[0]["data"][index].push(parseFloat(value));					
					});
						
			
				}
				  //step 4	  
	           	if (!show_legend)
					options.legend.enabled=false;
					
				if (!show_data_label)
					options.plotOptions[type]["dataLabels"].enabled=false;
					
				if (!show_exporting)
					options.exporting.buttons.exportButton.enabled=false;

				if (!show_printing)
					options.exporting.buttons.printButton.enabled=false;

				//with options
/*				var html=   '<div class="main_m">'+
							'<div class="title">'+head_title+'</div>'+
							'<div class="nav1">'+
	 						'<ul>'+
							'<li><a href="#" name="btn_full_screen" value="'+chart_object+'">Full Screen</a></li>';
							
				if (show_sorting)			
					html+=  '<li id="'+chart_object+'"><a href="#">Column Sort</a>'+
							'<ul></ul>'+
							'<input type="hidden" id="'+chart_object+'" />'+
							'</li>'+
							'<li id="'+chart_object+'"><a href="#">Row Sort</a>'+
							'<ul></ul>'+
							'<input type="hidden" id="'+chart_object+'" />'+
							'</li>'+
							'<li id="'+chart_object+'"><a href="#">Order</a>'+
							'<ul>'+
							'<li><a href="#" name="btn_asc_order" value="'+chart_object+'" >Ascending</a></li>'+
							'<li><a href="#" name="btn_desc_order" value="'+chart_object+'">Descending</a></li>'+
							'<input type="hidden" id="'+chart_object+'" />'+
							'<input type="hidden" id="'+chart_object+'" />'+
							'</ul>'+
							'</li>';

			if (show_tabular)
				html+=      '<li><a href="#" name="btn_show_data" value="'+chart_object+'">Show</a>'+
							'<ul>'+
							'<li><a href="#" name="btn_only_chart" value="'+chart_object+'" >Only Chart</a></li>'+
							'<li><a href="#" name="btn_only_tabular" value="'+chart_object+'" >Only Tabular Data</a></li>'+
							'<li><a href="#" name="btn_show_both" value="'+chart_object+'" >Both</a></li>'+				
							'</ul></li>';
				
				html+=      '<li><a href="#" name="btn_settings" value="'+chart_object+'">Settings</a></li>'+
							'</ul>'+
							'</ul>'+
							'</div>'+
							'</div>'+
							'<div id="chart_area" class="'+chart_object+'"></div>'+
							'<div id="'+chart_object+'_table"></div>';
*/
	var html='<div class="chart_area chart" ><div class="main_m">'+
							'<div class="title">'+head_title+'</div>'+
							'<div class="nav1">'+
	 						'<ul>'+
							'<li><a href="#" name="btn_full_screen" value="'+chart_object+'"><img src="'+base_url+'images/Full screen.png" title="View Chart in full screen" height=25px/></a></li>';
							
		if (show_sorting)			
					html+=  '<li id="'+chart_object+'_sort"><a href="#"><img src="'+base_url+'images/sorting_a-z-icon.png" title="Sort Data" height=25px/></a>'+
							'<ul></ul>'+
							'<input type="hidden" id="'+chart_object+'_sort_value" />'+
							'<input type="hidden" id="'+chart_object+'_csort_value" />'+
							'<input type="hidden" id="'+chart_object+'_order_value" />'+
							'<input type="hidden" id="'+chart_object+'_corder_value" />'+							

							'</li>';
		if (show_tabular)
				html+=      '<li id="'+chart_object+'_view"><a href value="'+chart_object+'"><img src="'+base_url+'images/eye2.png" title="View Options" height=25px/></a>'+
							'<ul>'+
							'<li><a href="#" name="btn_only_chart" value="'+chart_object+'" >Only Chart</a></li>'+
							'<li><a href="#" name="btn_only_tabular" value="'+chart_object+'" >Only Tabular Data</a></li>'+
							'<li><a href="#" name="btn_show_both" value="'+chart_object+'" >Both</a></li>'+				
							'</ul></li>';
				
				html+=     '</ul>'+
							'</ul>'+
							'</div>'+
							'</div>'+
							'<div id="chart_area" class="'+chart_object+'" chart_obj="'+chart_object+'"></div>'+
							'<div id="'+chart_object+'_table"></div>'+
							'</div>'+
							'</div>';                 
                $("#chart_options").html(html);
				
			 chart= new Highcharts.Chart(options);

			});
			
		}
		if (page==6)
		{
			var obj_text=$("#chart_objectid").val();
			$("#codeof_chart_with").text("<script> DrawChartWithOptions([ID of div],\""+obj_text+"\") <\/script> ");	
			$("#codeof_chart_without").text("<script> DrawChartWithoutOptions([ID of div],\""+obj_text+"\") <\/script> ");	
		}
		
		
		
		
    });
	
	$("#saveChart").click(function(e){
		var source=data_source_id;
		var type=$("#chart_type").val();

		var category_name=$("#category_placeholder").children("div.data").text();
		var series_name=$("#series_placeholder").children("div.data");

		//get all series name
		var series=new Array();	
		$.each(series_name,function(index,value){
			series.push($(value).text());
			
		});


	
		var head_title=$("#chart_headtitle").val();
		var title=$("#chart_title").val();
		var sub_title=$("#chart_subtitle").val();
		var chart_object=$("#chart_objectid").val();
		var show_legend=$("#chart_showLegend").attr("checked");
		var show_data_label=$("#chart_datalabels").attr("checked");
		var show_sorting=$("#chart_sorting").attr("checked");
		var show_exporting=$("#chart_exporting").attr("checked");
		var show_printing=$("#chart_printing").attr("checked");
		var show_tabular=$("#chart_tabular").attr("checked");		
		var do_transpose=$("#do_transpose").attr("checked");
		
		var chart_name=$("#name").val();
		
		$.post("<?php echo base_url();?>index.php/Chart_data/AddNew",
			{
				"source":source,
				"type":type,
				"category":category_name,
				"series":series.toString(),
				"obj_id":chart_object,
				"head_title":head_title,
				"title":title,
				"subtitle":sub_title,
				"show_legend":(show_legend ? 1 : 0),
				"show_datalabels":(show_data_label ? 1 : 0),
				"show_sorting":(show_sorting ? 1 : 0),				
				"enable_exporting":(show_exporting ? 1 : 0),
				"enable_printing":(show_printing ? 1 : 0),	
				"enable_tabulardata":(show_tabular ? 1 : 0),							
				"do_transpose":do_transpose				
			},function(data){
				if(data=="Added")
					document.location.href="<?php echo base_url() ?>index.php/ChartMaster";	
			
		
		});
	});
	
	$("#get_query").click(function(e) {
		var id=$("#source").val();
        //ToDo: check if query is parameterised or not
		//if parameterised then ask how to handle the parameters
		
		$("#query_string").load("<?php echo base_url();?>index.php/query_master/executeStoredQuery/"+id);
		$(".hide_query").show();
    });
	
	$("#execute_query").click(function(e) {

        $.post("<?php echo base_url();?>index.php/chart_misc/executeQuery",{query:$("#query_string").text()},function(data){
			$("#op").html(data);
			$(".hide_output").show();
		});
    });

	$(".chart_type>li").click(function(e) {
		$(this).siblings("li").css("background","");
		$(this).css("background","#9C0");
		var type=$(this).text();
		type=type.replace(" chart","");
		type=type.toLowerCase();
		$("#chart_type").val(type);
    });
	
	$("#category_placeholder").droppable({
		accept:".query_fields",
		drop:function(event,ui){
			var field_name=$(ui.draggable).text();
			var data_type=$(ui.draggable).attr("datatype");
			if (!$(this).hasClass("placeholder-filled"))
			{
				$(this).addClass("placeholder-filled");
				$(this).css("height","55px");

				$(this).droppable("disable");
				$(this).text("");
				$(this).append("<div class=\"placeholder_header\">Category<br/>Field</div><div class=\"data\" datatype='"+data_type+"'>"+field_name+"<img src='<?php echo base_url();?>images/cross.png' height='20px'  class='cross'></div>");
			}
			else
			{
				field_name="<br />"+field_name;
				$(this).children("div.data").append(field_name);
			}
			$(ui.draggable).remove();
			$(".cross").click(function(e) {
				var text=$(this).parent().text();
				var data_type=$(this).parent().attr("datatype");
				var to_add="<li datatype='"+data_type+"' class='query_fields'>"+text+"</li>";
				
				$("#field_for_selection").children("ul.btn_list_container").append(to_add);
				$("#field_for_selection").children("ul.btn_list_container").children("li").draggable({
					revert:"invalid"
				});
				
				$(this).parent().remove();
				$("#category_placeholder").droppable("enable");
				$("#category_placeholder").removeClass().addClass("placeholder");
				$("#category_placeholder").html("Add Category");				
			});

		}
		
	});

	$("#series_placeholder").droppable({
		accept:".query_fields[datatype!='varchar']",
		drop:function(event,ui){
			var field_name=$(ui.draggable).text();
			var data_type=$(ui.draggable).attr("datatype");			
			var type=$("#chart_type").val();
			if (!$(this).hasClass("placeholder-filled"))
			{
				$(this).addClass("placeholder-filled");
				$(this).css("line-height","100%");			
				$(this).text("");
				if (type==="pie")
					$(this).droppable("disable");
				$(this).append("<div class=\"placeholder_header\">Series<br/>Field</div><div class=\"data\" datatype='"+data_type+"' >"+field_name+"<img src='<?php echo base_url();?>images/cross.png' height='20px' class='cross' ></div>");
			}
			else
			{
				
				field_name="<div class='data'>"+field_name+"<img src='<?php echo base_url();?>images/cross.png' height='20px' class='cross'></div>";
				$(this).append(field_name);
			}	

			$(ui.draggable).remove();
			$(".cross").click(function(e) {
				var text=$(this).parent().text();
				var data_type=$(this).parent().attr("datatype");
				var to_add="<li datatype='"+data_type+"' class='query_fields'>"+text+"</li>";
				$("#series_placeholder").droppable("enable");
				
				$("#field_for_selection").children("ul.btn_list_container").append(to_add);
				$("#field_for_selection").children("ul.btn_list_container").children("li").draggable({
					revert:"invalid"
				});
				
				$(this).parent().remove();
			});
			
		}
		
		
	});

});
</script><div class="clear">&nbsp;</div></div>