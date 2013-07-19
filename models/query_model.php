<?php
	/**
	 *@category Model:Chartdata_model
	 *@Author : Karan Shah
	 *@Date : 25/01/2013
	 */

	class query_model extends CI_Model
	{
		function query_model()
		{
			parent::__construct();
			$this->load->database();
		}
			
	
		/*function SaveQuery
		@author:Karan Shah
		@Date:09/03/13
		*/
		function SaveQuery()
		{
	
			$this->db_info=$this->load->database("info",true);
			
			$query_info=$_POST;
			unset($query_info["datatypes"]);
			$this->db->insert("queries",$query_info);
			
			$id=$this->db->insert_id();
			$selected_fields=$_POST["select_clause"];
			$alias_datatypes_str=$_POST["datatypes"];		
			
			$fields_array=explode(",",$selected_fields);
			$metadeta_array=array();
			foreach($fields_array as $table_fields)
			{
				$is_aliased=(strpos($table_fields," as "));
				if ($is_aliased)
				{
					$alias=stristr($table_fields,"as ");
					$alias=substr($alias,3);
					$alias=trim($alias);
					$field_info=array();
					$field_info["id"]=$id;
					$field_info["field_name"]=trim($alias,"\"");
					foreach($alias_datatypes_str as $key=>$alias_info)
					{
						if ($alias_info["field"]==trim($alias,"\""))
						{
								$field_info["field_type"]=$alias_info["datatype"];					
								break;	
						}	
					}
					array_push($metadeta_array,$field_info);
				}
				else
				{
					$field=stristr($table_fields,".");
					$field=substr($field,1);
					$table=stristr($table_fields,".",true);
					$field=str_replace(array(".","`"),"",$field);
					$table=str_replace(array(".","`"),"",$table);
					
					if ($field=="*")
					{
						$fields = $this->db->field_data($table);
		 
						foreach ($fields as $field)
						{
						   $field_info=array();
						   $field_info["id"]=$id;
						   $field_info["field_name"]=$field->name;
						   $field_info["field_type"]=$field->type;				   
						   array_push($metadeta_array,$field_info);  	
						   
						}
						
						
					}
					else
					{
						$query=$this->db_info->query("SELECT `COLUMN_TYPE` FROM `COLUMNS` WHERE `TABLE_NAME` like '".trim($table)."' and `COLUMN_NAME` like '".trim($field)."'");;
						$field_info=array();
						$finfo=$query->first_row('array');
						$field_info["id"]=$id;
						$field_info["field_name"]=$field;
						$field_info["field_type"]=stristr($finfo['COLUMN_TYPE'],"(",true);
						
						array_push($metadeta_array,$field_info);  					
						
					}
				}
			}
			$this->db->insert_batch("query_metadata",$metadeta_array);
			return true;
	
		}
		/*
		function getDataSources
		@author:Karan Shah
		@date: 13/03/2013
		*/
		function getDataSources()
		{
			$this->db->select("id,name");
			$this->db->from("queries");	
			$query=$this->db->get();
			return json_encode($query->result_array());
		}
		
		/*
		function getFieldsInfo
		@author:Karan Shah
		@date: 14/03/2013
		*/
		function getFieldsInfo($id)
		{
			$this->db->select("field_name,field_type");
			$this->db->from("query_metadata");
			$this->db->where("id",$id);
			
			$fields_query=$this->db->get();
			$result=$fields_query->result_array();
			return json_encode($result);
		}

		/*
		function executeStoredQuery
		@author:Karan Shah
		@date: 13/03/2013
		*/
		function executeStoredQuery($id)
		{
			$fetch_query=$this->db->get_where("queries",array("id"=>$id));
			$query_parameters=$fetch_query->row();
			
			$query="";
			if ($query_parameters->select_clause!="")
				$query.="select ".$query_parameters->select_clause;
			if ($query_parameters->from_clause!="")
				$query.=" from ".$query_parameters->from_clause;
			if ($query_parameters->where_clause!="")	
				$query.=" where ".$query_parameters->where_clause;
			if ($query_parameters->group_clause!="")	
				$query.=" groupby ".$query_parameters->where_clause;
			if ($query_parameters->order_clause!="")	
				$query.=" orderby ".$query_parameters->order_clause;
			if ($query_parameters->having_clause!="")	
				$query.=" having ".$query_parameters->having_clause;
				
			return $query;
		}

		/*
		function executeQueryJson
		@author:Karan Shah
		@Date:18/03/2013
		*/
		function executeQueryJson($id)
		{
			$query=$this->executeStoredQuery($id);
			$query_obj=$this->db->query($query);
			$results=$query_obj->result_array();
			$columns=$query_obj->list_fields();
			$final_obj=array();
			foreach($columns as $column)
				$final_obj[$column]=array();
				
			foreach($results as $result)
				foreach($result as $field=>$value)
					array_push($final_obj[$field],$value);
			
			return json_encode($final_obj);
		}


		function getCount()
		{
			$nos=$this->db->count_all('queries');
			return $nos;
		}
		
		function getAllName()
		{
			$this->db->select("name");
			$this->db->from("queries");
			$query=$this->db->get();
			return $query->result_array();	
			
		}
		
		function delete($id)
		{
			$this->db->delete("queries",array('id' => $id));
			return true;
		}
	}
	
?>
