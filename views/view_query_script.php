<script language="javascript" type="text/javascript">
$(document).ready(function(e) {

$("#chart_dlg table").hide();
$("#confirm").hide();
var type=$("#type").val();
if (type=="query")
	$(".page-heading h1").text("View Queries");
else
	$(".page-heading h1").text("View Charts");

if (type=="query")
{	

	$("[btn='true']").click(function(e) {
		e.preventDefault();

		$("#qry").val("");
		$("#op").val("");
		var id=$(this).attr("no");
		
		$("#qry_no").val(id);
		$.get("<?php echo base_url();?>index.php/query_master/executeStoredQuery/"+id,{},function(data){
			$("#qry").val(data);
			$.post("<?php echo base_url();?>index.php/chart_misc/executeQuery",{query:$("#qry").val()},function(data){
				$("#op").html(data);
				$("#op table").css("margin","0px");
			});
		});
		
		$("#delqry").click(function(e) {
			e.preventDefault();
			var ans=confirm("You are about to delete this query");
			if (ans)
			{
				$.get("<?php echo base_url(); ?>index.php/query_master/delete/"+id,{},function(data){
					alert(data);
					window.location.href="";
					
				});
			
			}
			else
				alert(ans);
        });		
		
	});
}
else
{
	$("#dlg").hide();

	$("[btn='true']").click(function(e) {
		e.preventDefault();
		$("#chart_dlg table").show();
		$("#chart_container").html("<div id='chartss'></div>");
		
		var chart_obj=$(this).text();
		$("#chart_name").val($(this).text());
	   	DrawChartWithOptions("chartss",$(this).text());
		$("#script1").val("<script> DrawChartWithOptions([ID of div],\""+$(this).text()+"\") <\/script> ");
		$("#script2").val("<script> DrawChartWithoutOptions([ID of div],\""+$(this).text()+"\") <\/script> ");		
		
		$("#delchart").click(function(e) {
			e.preventDefault();
			var ans=confirm("You are about to delete this Chart");
			if (ans)
			{
				$.get("<?php echo base_url(); ?>index.php/Chart_data/delete/"+chart_obj,{},function(data){
					alert(data);
					window.location.href="";
					
				});
			
			}
			else
				alert(ans);
        });	
	});
}



});
</script>
