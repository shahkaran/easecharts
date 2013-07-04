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
			<div id="table-content" style="height:500px">
<!----

main content starts from here



--->
		<!--  start step-holder -->
        
		<div id="step-holder">
			<div class="step-no">1</div>
			<div class="step-dark-left"><a href="">Data Source</a></div>
			<div class="step-dark-right">&nbsp;</div>
			<div class="step-no-off">2</div>
			<div class="step-light-left">Chart type</div>
			<div class="step-light-right">&nbsp;</div>
			<div class="step-no-off">3</div>
			<div class="step-light-left">Series & Category</div>
			<div class="step-light-right">&nbsp;</div>
			<div class="step-no-off">4</div>
			<div class="step-light-left">Other options</div>
			<div class="step-light-right">&nbsp;</div>
			<div class="step-no-off">5</div>
			<div class="step-light-left">Preview</div>
			<div class="step-light-right">&nbsp;</div>            
			<div class="step-no-off">6</div>
			<div class="step-light-left">Finish</div>
			<div class="step-light-round">&nbsp;</div>

			<div class="clear"></div>
		</div>
		<!--  end step-holder -->
		<div class="wizard-container">
    	    <div id="step1" class="wizard wizard-current">
                <table id="id-form">
	                <tr>
    		            <th width="25%">Select Source&nbsp;&nbsp;&nbsp;</th>
            		    <td style="padding-top:5px"><select id="source"></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="normal" id="do_transpose"/> Transpose Data</td>
                		<td width="25%">
                        <input type="button" value="Show query" class="green" id="get_query"/>
                        &nbsp;&nbsp;&nbsp;
						<img src="<?php echo base_url(); ?>images/Settings.png" id="settings_icon" height="30px" style="vertical-align:top;cursor:pointer"/>
						<div id="settings" >   
                        <input type="button" value="New Query" class="green" id="new_query"/><br /><br />
                        <input type="button" value="Delete query" class="green" id="del_query"/>
                        </div>
						</td>                        
                	</tr>
                	<tr class="hide_query">
                		<th width="25%">Query&nbsp;&nbsp;&nbsp;</th>
                		<td id="query_string"></td>
                		<td width="25%"><input type="button" value="Show output" class="green" id="execute_query"/></td>
	                </tr>
                    <tr class="hide_output">
    		            <th>Query Output Preview&nbsp;&nbsp;&nbsp;</th>
            		    <td colspan="2"><div id="op"></div></td>
                	</tr>
                </table>
				<input type="button" value="next" class="green wizard-navigation-next" goto="2"/>
            </div>
			<div id="step2" class="wizard">
            	<table>
                <tr>
	                <td width="25%">
    		            <ul class="btn_list_container chart_type">
            			    <li>Bar chart</li>
			                <li>Column chart</li>
            			    <li>Line chart</li>
                            <li>Pie chart</li>
                		</ul>
                	</td>
               		<td>Preview:<br /><div class="preview_window">&nbsp;</div></td>
                </tr>
               
                </table>
                <input type="hidden" id="chart_type" value="line" />
     			<input type="button" value="Prev" class="green wizard-navigation-prev" goto="1"/>
                <input type="button" value="next" class="green wizard-navigation-next" goto="3"/>
            </div>
			<div id="step3" class="wizard">
               <table>
               <tr>
               <td width="25%"> 	
                    <div class="fields_selection" id="field_for_selection">
		                <div class="panel_header">
        			        Fields
                		</div>
                	</div>
                </td>
                <td width="75%">
 				<div id="category_placeholder">
                Place Field for category
                
                
                </div>
 				<div id="series_placeholder">
                Place Fields for series
                </div>
           		</td>
            </table>
			<input type="button" value="Prev" class="green wizard-navigation-prev" goto="2"/>
            <input type="button" value="next" class="green wizard-navigation-next" goto="4"/>
            
            </div>

			<div id="step4" class="wizard">
            <table id="options">
            <tr>
	            <th>Head Title 
    	        <input type="text" class="normal" id="chart_headtitle"/></th>
	            <td>Title 
    	        <input type="text" class="normal" id="chart_title"/></td>
            </tr>
            <tr>
	            <th>Sub-title
	            <input type="text" class="normal" id="chart_subtitle"/></th>
	            <th>Chart Object Id
	            <input type="text" class="normal" id="chart_objectid"/></th>

            </tr>
            <tr>
	            <td class="normal"><input type="checkbox" id="chart_showLegend" class="normal"/>
	            Show Legend </td>
	            <th class="normal"><input type="checkbox" id="chart_datalabels" class="normal"/>
	            Show Data Labels </th>
            </tr>
            <tr>
	            <th class="normal"><input type="checkbox" id="chart_sorting" class="normal"/>
	            Enable Sorting on series values </th>
	            <td class="normal"><input type="checkbox" id="chart_exporting" class="normal"/>
	            Enable Exporting</td>

            </tr>
            <tr>
	            <th class="normal"><input type="checkbox" id="chart_printing" class="normal"/>
	           	Enable Printing </th>
	            <td class="normal"><input type="checkbox" id="chart_tabular" class="normal"/>
	            Enable Tabular data</td>

            </tr>

            </table>
			     <input type="button" value="Prev" class="green wizard-navigation-prev" goto="3"/>
                 <input type="button" value="next" class="green wizard-navigation-next" goto="5"/>
            
            </div>
            <div id="step5" class="wizard">
            	<div id="chart_options" class="chart_area">
                    <div id="chart_area">
                    </div>
                </div>
     			<input type="button" value="Prev" class="green wizard-navigation-prev" goto="4"/>
     			<input type="button" value="Next" class="green wizard-navigation-next" goto="6"/>
            </div>
            
            <div id="step6" class="wizard">
				<table>
                <tr>
                	<td rowspan="6"><img src="<?php echo base_url(); ?>images/easecharts_done.png" /></td>
                    <td>Your chart is ready to be used </td>
                </tr>
                <tr>
                	<td>Instructions:<br/><b><font size="+1">Make a div with a specific id and use javascript code mentioned below</font></b></td>
                </tr>
                <tr>
                	<td>To create chart with options component : </td>
                </tr>
                <tr>
                	<td><textarea id="codeof_chart_with" style="width:600px"></textarea></td>
         		</tr>
                <tr>
                	<td>To create chart without options component : </td>
                </tr>
                <tr>
                	<td><textarea id="codeof_chart_without" style="width:600px"></textarea></td>
         		</tr>
                </table>    
     			<input type="button" value="Prev" class="green wizard-navigation-prev" goto="5"/>
     			<input type="button" value="Finish" class="green wizard-navigation-next" id="saveChart"/>                
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
<div class="clear">&nbsp;</div></div>