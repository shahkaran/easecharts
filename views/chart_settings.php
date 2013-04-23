<!-- start content -->
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
			<div id="table-content">
<!----

main content starts from here



--->

  
<div id="settings_area">
  <div id="tables" title="Tables">
    <?php
    foreach($tables as $table_name=>$table)
    {
        echo "<div class='table' value='$table_name'>".$table_name."<div class='fields'><p>*</p>";
        foreach($table as $field)
            echo "<p datatype='$field->type' max='$field->max_length'>".$field->name."</p>";

        echo "</div></div>";
    }

    ?>
    </div>

    <div id="query_canvas">
    	<div class="nav1">
        	<ul>
            	<li><a href="#" id="btn_show_table">Show Table</a></li>
				<li><a href="#" id="btn_make_join">Perform Join</a></li>
                <li><a href="#" id="btn_simple_query">Paste Query Directly</a></li>              
            </ul>
       	  </div>
    </div>

<!---
<table width="100%">
	<tr id="settings_area">
    	<td>

<div id="tables" title="Tables"> 
            <?php
			foreach($tables as $table_name=>$table)
			{
				echo "<div class='table' value='$table_name'>".$table_name."<div class='fields'><p>*</p>";
				foreach($table as $field)
					echo "<p datatype='$field->type' max='$field->max_length'>".$field->name."</p>";
				
				echo "</div></div>";
			}
		
			?>
</div>

		  
        	<div id="query_canvas">
            
            </div>
        </td>
     </tr>
</table>
-->			
    <div id="fields_display">
    <input type="hidden" id="no_of_field" value="7"/>
    <table class="multiple_fields">
    <tr id="field_name">
    <th>Field:</th>
    <td class="f1"><input type="text" name="field"/><input type="hidden" name="type" /><input type="hidden" name="alias"/></td>
    <td class="f2"><input type="text" name="field"/><input type="hidden" name="type" /><input type="hidden" name="alias"/></td>
    <td class="f3"><input type="text" name="field"/><input type="hidden" name="type" /><input type="hidden" name="alias"/></td>
    <td class="f4"><input type="text" name="field"/><input type="hidden" name="type" /><input type="hidden" name="alias"/></td>    
    <td class="f5"><input type="text" name="field"/><input type="hidden" name="type" /><input type="hidden" name="alias"/></td>        
    <td class="f6"><input type="text" name="field"/><input type="hidden" name="type" /><input type="hidden" name="alias"/></td>        
    <td class="f7"><input type="text" name="field"/><input type="hidden" name="type" /><input type="hidden" name="alias"/></td>                
    </tr>
    <tr id="table_name">
    <th>Table name:</th>
    <td class="f1"><input type="text" name="table"/></td>
    <td class="f2"><input type="text" name="table"/></td>
    <td class="f3"><input type="text" name="table"/></td>
    <td class="f4"><input type="text" name="table"/></td>    
    <td class="f5"><input type="text" name="table"/></td>    
    <td class="f6"><input type="text" name="table"/></td>    
    <td class="f7"><input type="text" name="table"/></td>            
    </tr>
    <tr id="show_or_not">
    <th>Show</th>
    <td class="f1"><input type="checkbox" name="show" class="normal" /></td>
    <td class="f2"><input type="checkbox" name="show" class="normal"/></td>
    <td class="f3"><input type="checkbox" name="show" class="normal"/></td>
    <td class="f4"><input type="checkbox" name="show" class="normal"/></td>    
    <td class="f5"><input type="checkbox" name="show" class="normal"/></td>        
    <td class="f6"><input type="checkbox" name="show" class="normal"/></td>        
    <td class="f7"><input type="checkbox" name="show" class="normal"/></td>        
    </tr>
    <tr id="criteria">
    <th>Criteria</th>
    <td class="f1"><input type="text" name="criteria"/></td>
    <td class="f2"><input type="text" name="criteria"/></td>
    <td class="f3"><input type="text" name="criteria"/></td>
    <td class="f4"><input type="text" name="criteria"/></td>    
    <td class="f5"><input type="text" name="criteria"/></td>    
    <td class="f6"><input type="text" name="criteria"/></td>    
    <td class="f7"><input type="text" name="criteria"/></td>                
    </tr>
	<tr id="group_by">
    <th>Group by</th>
    <td class="f1"><input type="checkbox" name="groupby" class="normal" /></td>
    <td class="f2"><input type="checkbox" name="groupby" class="normal" /></td>
    <td class="f3"><input type="checkbox" name="groupby" class="normal" /></td>
    <td class="f4"><input type="checkbox" name="groupby" class="normal" /></td>            
    <td class="f5"><input type="checkbox" name="groupby" class="normal" /></td>                
    <td class="f6"><input type="checkbox" name="groupby" class="normal" /></td>                
    <td class="f7"><input type="checkbox" name="groupby" class="normal" /></td>    
    </tr>
    <tr id="func">
    <th>Function</th>
    <td class="f1"><select name="func" class="normal">
    					<option value=""></option>
                     </select>
    </td>
    <td class="f2"><select name="func" class="normal">
    					<option value=""></option>
                     </select>
    </td>
    <td class="f3"><select name="func" class="normal" >
    					<option value=""></option>
                     </select>
    </td>
    <td class="f4"><select name="func" class="normal">
    					<option value=""></option>
                     </select>
    </td>
    <td class="f5"><select name="func" class="normal">
    					<option value=""></option>
                     </select>
    </td>
    <td class="f6"><select name="func" class="normal">
    					<option value=""></option>
                     </select>
    </td>
    <td class="f7"><select name="func" class="normal">
    					<option value=""></option>
                     </select>
    </td>

    </tr>
    <tr id="having">
    <th>Having</th>
    <td class="f1"><input type="text" name="having" /></td>
    <td class="f2"><input type="text" name="having" /></td>
    <td class="f3"><input type="text" name="having" /></td>
    <td class="f4"><input type="text" name="having" /></td>
    <td class="f5"><input type="text" name="having" /></td>
    <td class="f6"><input type="text" name="having" /></td>
    <td class="f7"><input type="text" name="having" /></td>            
    </tr>            
    <tr id="sort">
    <th>Sort</th>
    <td class="f1"><select name="sort" class="normal"><option value=""></option><option value="asc">Ascending</option><option value="desc">Descending</option></select></td>
    <td class="f2"><select name="sort" class="normal"><option value=""></option><option value="asc">Ascending</option><option value="desc">Descending</option></select></td>
    <td class="f3"><select name="sort" class="normal"><option value=""></option><option value="asc">Ascending</option><option value="desc">Descending</option></select></td>
    <td class="f4"><select name="sort" class="normal"><option value=""></option><option value="asc">Ascending</option><option value="desc">Descending</option></select></td>    
    <td class="f5"><select name="sort" class="normal"><option value=""></option><option value="asc">Ascending</option><option value="desc">Descending</option></select></td>        
    <td class="f6"><select name="sort" class="normal"><option value=""></option><option value="asc">Ascending</option><option value="desc">Descending</option></select></td>    
    <td class="f7"><select name="sort" class="normal"><option value=""></option><option value="asc">Ascending</option><option value="desc">Descending</option></select></td>        
    </tr>
    </table>
<input type="button" value=" Show Query" class="green" id="btn_create_query"/>
	</div>
    
    <div id="joins">
    </div>


    <div id="results">
        <textarea id="query" readonly="readonly">
        </textarea>
        <input type="button" value="Execute Query" class="green" id="btn_execute_query"/>
            <div id="output">
            </div>
            <br/>	
        <input type="text" class="inp-form" id="queryName" />
        <input type="button" value="Save This Query" class="green" id="btn_save_query"/>
    </div>
</div>

<div id="join-dialog" title="Perform join">
    <div id="tb1">
	    Table 1:<select id="table1_list" class="normal"></select>
    	<br/><br/>
    </div>
    <div id="tb2">
	    Table 2:<select id="table2_list" class="normal"></select><br/><br/>
    </div>
    <div id="join_type">
        Type of join:
        <select id="join_list">
        <option value="-1">Select</option>
        <option value="natural">Natural join</option>
        <option value="left">left outer join</option>
        <option value="right">right outer join</option>
        <option value="on">inner join on</option>
        </select><br/>
        <br/>
    </div>
    <div id="join_condition">
        Condition for join<br/><br/>
        <table align="left">
        <tr>
        <td>Field 1</td>
        <td><select id="field1"></select></td>
        </tr>
        <tr></tr>
        <tr>
        <td>Operator</td>
        <td><select id="operator">
            <option value="=">=</option>
            <option value=">=">>=</option>    
            <option value="<="><=</option>    
            <option value="!=">>!=</option>        
            <option value="like">like</option>        
        </select></td>
        </tr>
        <tr></tr>
        <tr>
        <td>Field 2</td>
        <td><select id="field2"></select></td>
        </tr>
        </table>
    </div>
    <br/>
    <input type="button" value="Add join" id="btn_add_join"/>
</div>
    <div id="Errors">
    </div>
    <div id="simple_query">
        <div id="paste_query">
            Paste Your Query here:
            <textarea id="simple_query_plain">
            </textarea>
            <input type="button" class="green" id="btn_process_query" value="Process" />
        </div>
    <div id="query_divided">
        <hr />
        Select : <textarea id="query_select"></textarea><br/>
        From : <textarea id="query_from"></textarea><br/>
        Where : <textarea id="query_where"></textarea><br/>     
        Group by : <textarea id="query_groupby"></textarea><br/>
        Having : <textarea id="query_having"></textarea><br/>       
        Order By : <textarea id="query_orderby"></textarea><br/>    
        
        <input type="button" id="btn_save_simple" value=" Its Perfect !! Save It "/>
    </div>
   	</div>
	<div id="editing">
    	<div id="alias_editing" class="editing">
        	<table>
            <tr>
            <td>
            Alias : 
            </td>
            <td>
            <input type="text" />
            </td>
            </tr>
            </table>
        </div>
        <div id="datatype_editing" class="editing">
        	<table>
            <tr>
            <td>
            Select datatype:
            </td>
            <td>
        	<select>
            	<option value="int">int</option>
                <option value="varchar">varchar (textual)</option>
                <option value="datetime">date/time</option>
            </select>
            </td>
            </tr>
            </table>
        </div>
        <div class="green" id="saveChanges">Save</div>

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
