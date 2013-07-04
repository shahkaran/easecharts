<div id="content"><!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div class="page-heading">
		<h1>Charts</h1>
	</div>
	<!-- end page-heading -->

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
		
			<!--  start table-content  -->
			<div id="table-content" style="max-height:500px">

            <input type="hidden" value="<?php echo $type?>" id="type" />
			<table>
            <tr>
            <td width="20%" class="fields_selection">
              <div class="panel_header">
	              Queries
              </div>
			  <div  style="overflow:auto;max-height:500px">
            <?php 
				$i=0;
				for ($i=0;$i<$count;$i++)
				{
			?>
				<a href="#" class="smallbtn" no="<?php echo $i+1 ?>" btn="true"><?php if ($type=="query") 
																											echo $names[$i]["name"];
																										else 
																											echo $names[$i]["obj_id"]; ?></a>
                
			<?php
				if ($i!=0 && $i%5==0)
					echo "<br/>";
				}
			?>
            	</div>
            </td>
            <td  style="vertical-align:top">
                <div id="dlg" title="View Query info" style="max-height:500px;">
                	<input type="hidden" id="qry_no" />
    	            <table width="100%" height="100%">
	    	            <tr>
            		    	<th>Query</th>
			                <td><textarea id="qry" style="width:600px;height:150px;"></textarea></td>
                		</tr>
                		<tr height="40%">
			                <th>Ouput</th>
            			    <td id="op" style="height:300px;vertical-align:top;text-align: left;float: left;width: 100%;"></td>
                		</tr>
                        <tr>
                        <td colspan="2"><a href="#" class="smallbtn" id="delqry">Delete This Query</a></td>
                        </tr>
	                </table>
                </div>
                <div id="chart_dlg" title="View Chart">
               	<input type="hidden" id="chart_name" />
                	<table>
                    <tr>
                    	<td id="chart_container">
                            <div id='chartss'>
                            </div>
                        </td>
                        <td style="vertical-align:top">
                        <h2>Script To Draw This Chart With Options</h2><br />
                        <textarea id="script1" style="width:290px"></textarea><br />
						<h2>Script To Draw This Chart Without Options</h2><br />
                        <textarea id="script2" style="width:290px"></textarea><br/><br/>
                        <a href="#" class="smallbtn" id="delchart">Delete This Chart</a>
                        
                        </td>
                   </tr>
                   </table>
                </div>            
			</td>
            </tr>
            </table>
                <div id="confirm">
                Are you sure want to delete ?
                </div>
            </div>
			<!--  end table-content  -->
	
			<div class="clear"></div>
		 
		</div>
		<!--  end content-table-inner ............................................END  -->
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>
	<div class="clear">&nbsp;</div>

</div>
<!--  end content --><div class="clear">&nbsp;</div></div>