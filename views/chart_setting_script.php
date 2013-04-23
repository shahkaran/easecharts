<script type="text/javascript">
var different_joins=0;
var joins_array=new Array();
var alias_storing_location;
var datatype_storing_location;

$(document).ready(function(){
//$("#tables").sortable();
jsPlumb.importDefaults({
    ConnectorZIndex:1
});

$("#results").hide();
$("#editing").hide();
$("editing div").hide();
$("#simple_query").hide();
$("#query_divided").hide();

var parameterised=false;

var select_array=new Array();
var from_array=new Array();
var condition_array=new Array();
var groupby_array=new Array();
var sortby_array=new Array();
var having_array=new Array();
var datatype_for_derived=new Array();
// for join
var final_join_string=""
//parameters
var parameters=new Array();

// for copy - paste query
var is_copy_paste=false;
var select_str="";
var from_str="";
var where_str="";
var groupby_str="";
var having_str="";
var orderby_str="";


var int_function_array=["","sum","min","max","avg","count"];
var varchar_function_array=["","count"];


var joined=false;
var join_string="";

var no_of_tables=0;

function findJoin(tablename,position,starting)
{
	var main_string=joins_array[different_joins];
	if (tablename==null && position==null)
		join_info=starting;
	else
		join_info=$("#joins > input:hidden").siblings("["+position+"='"+tablename+"']").filter("[joined='false']");
	
	if (join_info.length>0)
	{
		var element="`"+join_info.attr("table1")+"`";
		var element2="`"+join_info.attr("table2")+"`";
	
		var join_string="";
		
		if ($.inArray(element,from_array)>-1 && $.inArray(element2,from_array)>-1)
		{
				
				join_string+=element;

	
				var join_type=join_info.val();
				if (join_type=="natural")
					join_string+=" natural join ";
				if (join_type=="left")
					join_string+=" left outer join ";
				if (join_type=="on")
					join_string+=" join ";
					
				join_info.attr("joined",true);
				
				join_string+=element2;			
													
				if (join_type!="natural")
				{
					var field1=join_info.attr("field1");
					var field2=join_info.attr("field2");
					var operator=join_info.attr("operator");	
					
					join_string+=" on "+field1+operator+field2;
				}
		}
		if (position==null || position=="table2")
			main_string=join_string.replace("`"+tablename+"`","")+main_string;
		else
			main_string=main_string+join_string.replace("`"+tablename+"`","");
		joins_array[different_joins]=main_string;
		findJoin(element.replace(/`/g,""),"table2");
		findJoin(element2.replace(/`/g,""),"table1");
	}
}
function saveQuery(e) {
	
	var queryName=$("#queryName").val();
	queryName=(queryName=="" ? "chart" : queryName);
	
	if (datatype_for_derived === null)
	{
		if (datatype_for_derived.length<=0)	
			datatype_for_derived=null;
	}
	else
		datatype_for_derived=null;
		
	var data_to_send={
		"name":queryName,		
		"select_clause":select_array.toString(),
		"from_clause":final_join_string,
		"where_clause":condition_array.join(" and "),
		"group_clause":groupby_array.toString(),
		"order_clause":sortby_array.toString(),
		"having_clause":having_array.toString(),
		"parameterised":(parameterised ? 1 : 0),
		"datatypes":datatype_for_derived
	}
	if (is_copy_paste)
		var data_to_send={
			"name":queryName,		
			"select_clause":select_str,
			"from_clause":from_str,
			"where_clause":where_str,
			"group_clause":groupby_str,
			"order_clause":orderby_str,
			"having_clause":having_str,
			"parameterised":(parameterised ? 1 : 0),
			"datatypes":datatype_for_derived
		}

    
	$.post("<?php echo base_url(); ?>index.php/query_master/SaveQuery",data_to_send,function(data){
		if (data=="Saved Successfully")
		{
			alert("Your Query is saved successfully with name "+queryName);
			//ToDo:
			//redirect to home page
			if (is_copy_paste)
				$("#simple_query").dialog("close");	
		}
		$("#output").html(data);
	});
}

$.each(int_function_array,function(index,element){
	int_function_array[index]="<option value='"+element+"'>"+element+"</option>";
});

$.each(varchar_function_array,function(index,element){
	varchar_function_array[index]="<option value='"+element+"'>"+element+"</option>";
});


$("#tables").dialog({
	height:300,
	width:300
	
});
$("#join-dialog").dialog({
	autoOpen:false,
	width:400,
	modal:true
});

$("#btn_show_table").click(function(e) {
	e.preventDefault();
    $("#tables").dialog("open");
});

$("#btn_simple_query").click(function(e) {
    $("#simple_query").attr("title","Add Query Directly");
	$("#tables").dialog("close");

    $("#simple_query").dialog({width:500,height:300,modal:true});
	
	$("#btn_process_query").click(function(e) {
		$("#query_divided").show();        
		var full_query=$("#simple_query_plain").val();
		var has_select=full_query.indexOf("select");
		var has_from=full_query.indexOf("from");		
		var has_where=full_query.indexOf("where");	
		var has_groupby=full_query.indexOf("group by");		
		var has_having=full_query.indexOf("having");						
		var has_orderby=full_query.indexOf("order by");		

		is_copy_paste=true;
		if (has_select==-1 || has_from==-1)
		{
			alert("Inproper query<br/>Please write proper query");	
		}
		else
		{
			select_str=full_query.substring(has_select+7,has_from-1);
			
			//get from clause
			
			var end_for_from;
			if (has_where!=-1)
				end_for_from=has_where-1;
			else if (has_groupby!=-1)
				end_for_from=has_groupby-1;
			else if (has_having!=-1)
				end_for_from=has_having-1;
			else if (has_orderby!=-1)
				end_for_from=has_orderby-1;
			else
				end_for_from=full_query.length;
			
			from_str=full_query.substring(has_from+4,end_for_from);

			
			
			
			//get where clause
			if (has_where!=-1)
			{
				var end_for_where;
				
				if (has_groupby!=-1)
					end_for_where=has_groupby-1;
				else if (has_having!=-1)
					end_for_where=has_having-1;
				else if (has_orderby!=-1)
					end_for_where=has_orderby-1;
				else
					end_for_where=full_query.length;
				
				where_str=full_query.substring(has_where+5,end_for_where);
			}
			
			
			//get groupby
			if (has_groupby!=-1)
			{
				var end_for_groupby;
				
				if (has_having!=-1)
					end_for_groupby=has_having-1;
				else if (has_orderby!=-1)
					end_for_groupby=has_orderby-1;
				else
					end_for_groupby=full_query.length;
				
				groupby_str=full_query.substring(has_groupby+8,end_for_groupby);
			}
			
			if (has_having!=-1)
			{
				var end_for_having;
				
				if (has_orderby!=-1)
					end_for_having=has_orderby-1;
				else
					end_for_having=full_query.length;
				
				having_str=full_query.substring(has_having+6,end_for_having);
			}
			
			if (has_orderby!=-1)
				orderby_str=full_query.substring(has_orderby+8,full_query.length);
			
			$("#query_select").val(select_str);
			$("#query_from").val(from_str);			
			$("#query_where").val(where_str);			
			$("#query_groupby").val(groupby_str);			
			$("#query_having").val(having_str);						
			$("#query_orderby").val(orderby_str);									
			
			
			$("#btn_save_simple").click(saveQuery);
		}

			
    });

});

$("#btn_make_join").click(function(e){
	
		e.preventDefault();
	if ($(".table[in_query='true']").length>0)
	{
		
		$("#join-dialog").dialog("open");
	
		var data="<option value=\"-1\">Select</option>";
		$(".table[in_query='true']").each(function(index, element) {
			data+="<option value='"+$(element).attr("value")+"'>"+$(element).attr("value")+"</option>";
		});
		$("#table1_list,#table2_list").text("");
		$("#table1_list,#table2_list").append(data);
		
		$("#tb2").hide();
		$("#join_type").hide();	
		$("#join_condition").hide();
		$("#btn_add_join").hide();	
	}
	else
	{
		$("#Errors").attr("Title","Error");
		$("#Errors").html("No tables are in query window.<br/>Joining Not possible");	
		$("#Errors").dialog();
	}
});

$("#table1_list").change(function(e) {
    	$("#tb2").show();
		$("#join_type").hide();	
		$("#join_condition").hide();
		$("#join_list").val(-1);
		$("#btn_add_join").hide();		
});

$("#table2_list").change(function(e) {
    	$("#join_type").show();
		$("#join_condition").hide();
		$("#join_list").val(-1);		
		$("#btn_add_join").hide();		
});

$("#join_list").change(function(e) {
    var option_selected=$(this).val();
	$("#btn_add_join").show();
	if (option_selected!="natural")
	{	
		$("#join_condition").show();
		var table1=$("#table1_list").val();
		var table2=$("#table2_list").val();		
		
		$("#field1,#field2").text("");
		$(".table[in_query='true']").filter("[value='"+table1+"']").children("div").children("p").each(function(index, element) {
			var text=$(element).text();
			if (text!="*")
	            $("#field1").append("<option value='"+table1+"."+text+"'>"+table1+"."+text+"</option>");
        });

		$(".table[in_query='true']").filter("[value='"+table2+"']").children("div").children("p").each(function(index, element) {
			var text=$(element).text();
			if (text!="*")
	            $("#field2").append("<option value='"+table2+"."+text+"'>"+table2+"."+text+"</option>");
        });
		
	}else
		$("#join_condition").hide();		

});

$("#btn_add_join").click(function(e) {
		var table1=$("#table1_list").val();
		var table2=$("#table2_list").val();		
		var connection_type=$("#join_list").val();
		
		
		
		var connection1 = jsPlumb.connect({
				source:$(".table[in_query='true']").filter("[value='"+table1+"']"), 
				target:$(".table[in_query='true']").filter("[value='"+table2+"']"),
				paintStyle:{ strokeStyle:"black", lineWidth:5  },
				hoverPaintStyle:{ strokeStyle:"#abcdef", lineWidth:7 },
				endpoint:"Blank",
				anchor:"Continuous"
/*				connector:"Straight",
				Endpoint:"Blank",
				anchors:["RightMiddle", "LeftMiddle" ],
				endpointStyle:{ fillStyle: "yellow" }
*/
		});	
		jsPlumb.bind("click", function(conn, originalEvent) {
				if (confirm("Delete connection from " + conn.sourceId + " to " + conn.targetId + "?"))
					jsPlumb.detach(conn); 
			});	
		
		
		var option="<input type='hidden' value='"+connection_type+"' table1='"+table1+"' table2='"+table2+"'";
		if (connection_type!="natural")
		{
			var field1=$("#field1").val();
			var field2=$("#field2").val();
			var operator=$("#operator").val();	
			
			option+="field1='"+field1+"' field2='"+field2+"' operator='"+operator+"'";
		}
		
		option+=" joined='false' />";
		$("#joins").append(option);
		
		$("#join-dialog").dialog("close");
});
$(".table").dblclick(function(e) {
			var element=$(this).clone(true,true);

			element.css("width","150px");

			element.unbind("dblclick");

			element.attr("in_query",true);
			element.prepend("<img src='<?php echo base_url();?>images/cross.png' height='20px'  class='cross' style='position: absolute;top: 0px;right: 0px;'>");
			element.find(".fields").removeClass("fields").find("p").addClass("table_fields");
			no_of_tables+=1;
			$("#query_canvas").append(element);
			//			used when position was not absolute 
			// had to specify absolute position for jsPlumb
			//			var top_position=(element.offset().top-(225+(no_of_tables*10)))*-1;
			//			var left_position=no_of_tables*10;

			var top_position=($("#settings_area").offset().top+(no_of_tables*10)+40);
			var left_position=($("#settings_area").offset().left+(no_of_tables*10));
			element.css("top",+top_position+"px");
			element.css("left",left_position+"px");
			element.css("z-index","5");
			jsPlumb.Defaults.Container = $("body");
/*
			
			*****
			commented as this was used for proper containment of tables before change in css
			*****
			*****
//				containment:[offset,parent_top,1050,parent_bottom],

			This line was used in below draggable options along with this values 
			
			****
			
			var parent_top=$("#query_canvas").offset().top;
			var offset=35;
			var parent_width=$("#query_canvas").width();
			var parent_bottom=$("#query_canvas").offset().top+$("#query_canvas").height()-25-element.height();
*/


			element.css("position","absolute");
//			element.find(".table_fields").css("position","absolute");
			jsPlumb.draggable(element,{containment:"parent",cancel: ".cross"});
			$(".cross").click(function(e) {
                $(this).parent().remove();
            });
			element.resizable();
	
			element.find(".table_fields").draggable(
				{ 
					opacity: 0.7,
					helper:"clone",
					start:function(event,ui){
						var el=$(ui.helper);
						el.css("width","auto");
						var datatype=el.attr("datatype");
					/*	$(".table_fields").filter("[datatype='"+datatype+"']").droppable({
							drop:function(event,ui){
								
 							var connection1 = jsPlumb.connect({
										source:$(this), 
										target:ui.draggable,
										connector:"Straight",
										Endpoint:"Blank",
										anchors:["RightMiddle", "LeftMiddle" ],
										endpointStyle:{ fillStyle: "yellow" }
  
								});
								jsPlumb.recalculateOffsets($(this).parent());	

								
							}
							
						});*/
					}
				});

		
			


});

//enable query_canvas area to be location for dropping table


$("#query_canvas").droppable({
	//on drop show the fields by removing "fields" class and adding "table_fields" class
	//also enable each field to draggable
	accept:"[in_query='true']",
	drop: function( event, ui ) {
		/*	var element=$(this).clone(true,true);
			element.css("width","150px");
			element.draggable({revert:"valid"});	
			element.unbind("dblclick");
			$("#query_canvas").append(element);
			element.attr("in_query",true);
			element.find(".fields").removeClass("fields").find("p").addClass("table_fields");
			element.find(".table_fields").draggable({ opacity: 0.7, helper: "clone" });		*/
		}
        
});

//enable each group of textbox's and checkbox to be location for dropping field

function accept_fields(event,ui){
			var element=ui.draggable;
		
			var no_fields=$("#no_of_field").val()+1;

			//add blank inputs for new record
			$(".multiple_fields tr").each(function(index, element) {
				var new_column=$(element).find("td:eq(1)").clone(true,true);
				new_column.removeClass().addClass("f"+no_fields);
				$(element).append(new_column);
			});

		
			var field_name=element.text();
			var table_name=element.parents("div.table").attr("value");
			var field_type=element.attr("datatype");
			var dropped_class=$(this).attr("class").split(" ",1)[0];
			$("."+dropped_class+" [name='field']").val(field_name);
			$("."+dropped_class+" [name='type']").val(field_type);
			$("."+dropped_class+" [name='table']").val(table_name);
			$("."+dropped_class+" input[name='show']").attr("checked","checked");
			
			
			if (field_type=="int")
				$("."+dropped_class+" [name='func']").html(int_function_array.join(""));
			else
				$("."+dropped_class+" [name='func']").html(varchar_function_array.join(""));
			//$("."+dropped_class+" input[type='checkbox']").attr("checked","checked");
			
			
	
	
}


$(".f1,.f2,.f3,.f4").droppable({
	//on drop get field name , table name , field type
	accept:".table_fields",
	drop:accept_fields
});

$("[name='field']").dblclick(function(e) {
	var dropped_class=$(this).parent().attr("class").split(" ",1)[0];

	var old_datatype=$(this).siblings("input[name='type']").val();
	
	alias_storing_location=$("."+dropped_class+" input[name='alias']");
	datatype_storing_location=$("."+dropped_class+" input[name='type']");
	
    $("#editing").attr("title","Add/Edit alias and datatype");
	$("#alias_editing,#datatype_editing").css("display","inline-block");
	$("#datatype_editing select").find("option[value='"+old_datatype+"']").attr("selected","selected");
	$("#editing").dialog({
		width:300,
		height:300
		,modal:true
	});
	$("#saveChanges").live("click",function(e){
		var alias=$("#alias_editing input").val();
		var new_datatype=$("#datatype_editing select").val();
		
		alias_storing_location.val(alias);
		datatype_storing_location.val(new_datatype);
		
		$("#editing").dialog("close");
	})
	
	
	
});

$("#btn_create_query").click(function(e) {
	is_copy_paste=false;
    var select_query="select ";
	select_array=new Array();
	from_array=new Array();
	condition_array=new Array();
	groupby_array=new Array();
	sortby_array=new Array();
	having_array=new Array();
	parameters=new Array();
	datatype_for_derived=new Array();
	$("#joins > input:hidden").attr("joined",false);

	different_joins=0;
	joins_array=new Array();	
	
	var no_fields=$("#no_of_field").val();

	for (var loop_i=1;loop_i<=no_fields;loop_i++)
	{
		var field_name=$(".f"+loop_i+" input[name='field']").val();
		var table_name=$(".f"+loop_i+" input[name='table']").val();
		var field_type=$(".f"+loop_i+" input[name='type']").val();
		var alias=$(".f"+loop_i+" input[name='alias']").val();
		var do_show=$(".f"+loop_i+" input[name='show']").attr("checked");
		var criteria=$(".f"+loop_i+" input[name='criteria']").val();
		var groupby=$(".f"+loop_i+" input[name='groupby']").attr("checked");
		var func=$(".f"+loop_i+" [name='func']").val();
		var having=$(".f"+loop_i+" input[name='having']").val();
		var sorting=$(".f"+loop_i+" [name='sort']").val();
		
		
		if(do_show)
		{	
			if (field_name!="")
			{
				var check_for_alias=field_name.split(" ");
				var final_expr="";

				/*
				does not work in certain case
				
				
				if(table_name!="[expression]" && field_name.search(/[\[\]]/)>-1)
				{
					field_name=field_name.replace(/\[/g,"`");
					field_name=field_name.replace(/\]/g,"`");
					final_expr=field_name;

					if (func!="")
						final_expr=func+"("+final_expr+")";
				}*/
				var field_name_for_checking=field_name.replace(/"/g,"");
				field_name_for_checking=field_name_for_checking.replace(/\)/g,"\\)");
				field_name_for_checking=field_name_for_checking.replace(/\(/g,"\\(");				
				var is_expression=$(".table[in_query='true']:contains("+table_name+")").find(".table_fields:contains("+field_name_for_checking+")").length==0
				if ((table_name!="[expression]" && is_expression) ||(table_name=="[expression]"))
				{
					final_expr=field_name;
					
					if (func!="")
						final_expr=func+"("+final_expr+")";
				}
				else
				{
					if (field_name!="*")
						field_name="`"+field_name+"`";
					if (func!="")
						final_expr=func+"(`"+table_name+"`."+field_name+")";
					else
						final_expr="`"+table_name+"`."+field_name;
				}
				if (alias!="")
				{
					final_expr=final_expr+" as \""+alias+"\"";
					datatype_for_derived.push({field:alias,datatype:field_type});
				}
				
				select_array.push(final_expr);
			}
		}
		
		if ($.inArray("`"+table_name+"`",from_array)==-1 && table_name!="")
			if (table_name!="[expression]")
				from_array.push("`"+table_name+"`");
		if (criteria!="" && field_name!="*")
		{
		var reg=new RegExp(/@[a-z,A-Z,0-9,_]+@/g);
			var answer=reg.exec(criteria);
			if (answer!==null)
				parameterised=true;
			while (answer!==null)
			{
				parameters.push(answer);
				answer=reg.exec(criteria);
			}

			
			var condition_string="`"+table_name+"`."+field_name;
			if (field_type=="int")
				condition_string+="="+criteria;
			if(field_type=="varchar")
				condition_string+=" like \""+criteria+"\"";
			condition_array.push(condition_string);
		}
		if (groupby)
			groupby_array.push("`"+table_name+"`."+field_name);
		
		if (sorting!="")
		{
			if (sorting=="asc")	
			 sortby_array.push("`"+table_name+"`."+field_name);
			else
			 sortby_array.push("`"+table_name+"`."+field_name+" desc");
			
		}
		
		if (having!="" && field_name!="*")
		{
			var condition_string;
			if (table_name!="[expression]")
				condition_string="`"+table_name+"`."+field_name;
			else
				condition_string=field_name;
			
			var reg=new RegExp(/@[a-z,A-Z,0-9,_]+@/g);
			var answer=reg.exec(criteria);
			if (answer!==null)
				parameterised=true;
			while (answer!==null)
			{
				parameters.push(answer);
				answer=reg.exec(criteria);
			}

				
			if (field_type=="int")
				condition_string+="="+having;
			if(field_type=="varchar")
				condition_string+=" like \""+having+"\"";
			having_array.push(condition_string);
		}
		
	}

	joins_array[different_joins]="";
	findJoin(null,null,$("#joins > input:hidden:first"));
	if ($("#joins > input:hidden[joined='false']").length>0)
	{
		different_joins+=1;
		joins_array[different_joins]="";		
		findJoin(null,null,$("#joins > input:hidden[joined='false']:first"));	
	}

	
/*
method 2:
loop through joins and add properly in string

	$.each($("#joins > input:hidden"),function(index,join_info){
		
		var element="`"+join_info.attr("table1")+"`";
		var element2="`"+join_info.attr("table2")+"`";

		join_string+="`"+element+"` ";		
		if ($.inArray(element,from_array) && $.inArray(element2,from_array))
		{
				join_string+="`"+element+"` ";
	
				var join_type=joins.val();
				if (join_type=="natural")
					join_string+="natural join";
				if (join_type=="left")
					join_string+="left outer join";
				if (join_type=="on")
					join_string+="join";
					
				join_string+="`"+element2+"` ";				
				if (join_type!="natural")
				{
					var field1=joins.attr("field1");
					var field2=joins.attr("field2");
					var operator=joins.attr("operator");	
					
					join_string+=" on "+field1+operator+field2;
				}
			
			
		}
		
	});
*/	
/*
Method 1 : loop through all tables and  see if there is join between them
	for (var loop_i=0;loop_i<no_of_tables;loop_i++)
	{
		for (var loop_j=loop_i+1;loop_j<no_of_tables-1;loop_j++)
		{
			var element=from_array[loop_i].replace(/`/g,"");
			var element2=from_array[loop_j].replace(/`/g,"");
			var join_info=$("#joins > input:hidden").filter("[table1='"+element+"']").filter("[table2='"+element2+"']");
			var join2_info=$("#joins > input:hidden").filter("[table1='"+element2+"']").filter("[table2='"+element+"']");
			
			var joins=(join_info.length>0 || join2_info.length>0 ? (join_info.length>0 ? join_info : join2_info) : null);
			if (joins!=null)
			{
				joined=true;
				join_string+="`"+element+"` ";
	
				var join_type=joins.val();
				if (join_type=="natural")
					join_string+="natural join";
				if (join_type=="left")
					join_string+="left outer join";
				if (join_type=="on")
					join_string+="join";
					
				join_string+="`"+element2+"` ";				
				if (join_type!="natural")
				{
					var field1=joins.attr("field1");
					var field2=joins.attr("field2");
					var operator=joins.attr("operator");	
					
					join_string+=" on "+field1+operator+field2;
				}
			}
			else
				join_string+="`"+element+"`,"+"`"+element2+"`";
		}
	}
	
*/
//	$("#joins > input:hidden").filter("[table1='column_data']").filter("[table2='column_support']")
	
	select_query+=select_array.toString();

	final_join_string=joins_array.join(",");
	if (final_join_string.length>0)
		$.each(from_array,function(index,element){
			if (final_join_string.search(element)==-1)
				final_join_string+=","+element;
		});
	else
		final_join_string=from_array.join(",");

	select_query+=" from "+final_join_string;
	if (condition_array.length>=1)
		select_query+=" where "+condition_array.join(" and ");
		
	if (groupby_array.length>=1)
		select_query+=" group by "+groupby_array.join(",");
	if (having_array.length>=1)
		select_query+=" having "+having_array.join(",");
	if (sortby_array.length>=1)
		select_query+=" order by "+sortby_array.join(",");
	
	$("#query").val("");
	$("#query").val(select_query);
	//$("#settings_area").slideUp();*/
	$("#results").slideDown();
});

$("#query").dblclick(function(e) {
    $("#simple_query").attr("title","Edit Query");

    $("#simple_query").dialog({width:500,height:300,modal:true});
	
	$("#query_divided").show();        
	$("#paste_query").hide();
	
	select_str=select_array.toString();
	from_str=final_join_string;
	where_str=condition_array.join(" and ");
	group_str=groupby_array.toString();
	orderby_str=sortby_array.toString();
	having_str=having_array.toString();
	
	is_copy_paste=true;
	
	$("#query_select").val(select_str);
	$("#query_from").val(from_str);			
	$("#query_where").val(where_str);			
	$("#query_groupby").val(groupby_str);			
	$("#query_having").val(having_str);						
	$("#query_orderby").val(orderby_str);		
	
	$("#btn_save_simple").click(function(e) {
		
		select_str=$("#query_select").val();
		from_str=$("#query_from").val();			
		where_str=$("#query_where").val();			
		groupby_str=$("#query_groupby").val();			
		having_str=$("#query_having").val();						
		orderby_str=$("#query_orderby").val();	

		var new_qry;
		new_qry="select "+select_str+" from "+from_str;
		if (where_str!="")
			new_qry+=" where "+where_str;
		if (groupby_str!="")
			new_qry+=" group by "+groupby_str;
		if (having_str!="")
			new_qry+=" having "+having_str;
		if (orderby_str!="")
			new_qry+=" order by "+orderby_str;
		
		
		$("#query").val(new_qry);
		$("#btn_execute_query").click();		
        
    });
});
$("#btn_execute_query").click(function(e){
	var query=$("#query").val();
	if (parameterised)
	{
		$.each(parameters,function(index,value)
		{
			var answer=prompt("Enter value for parameter : "+value[0].replace(/@/,""),"");	
			query=query.replace(value,answer);
		});		
	}

	$.post("<?php echo base_url(); ?>index.php/chart_misc/executeQuery",{"query":query},function(data){
		$("#output").html(data);
	});
});

$("#btn_save_query").click(saveQuery);
});
</script>