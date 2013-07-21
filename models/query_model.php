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

			  $mysql_data_type_hash = array(
			    1=>'tinyint',
			    2=>'smallint',
			    3=>'int',
			    4=>'float',
			    5=>'double',
			    7=>'timestamp',
			    8=>'bigint',
			    9=>'mediumint',
			    10=>'date',
			    11=>'time',
			    12=>'datetime',
			    13=>'year',
			    16=>'bit',
			    //252 is currently mapped to all text and blob types (MySQL 5.0.51a)
			    253=>'varchar',
			    254=>'char',
			    246=>'decimal'
			);


			$query_info=$_POST;
			unset($query_info["datatypes"]);
			unset($query_info["original"]);
			$this->db->insert("queries",$query_info);
			
			$id=$this->db->insert_id();
			$selected_fields=$_POST["select_clause"];
			$alias_datatypes_str=$_POST["datatypes"];		
			
			$fields_array=explode(",",$selected_fields);
			$mysqli = new mysqli($this->db->hostname,$this->db->username,$this->db->password,$this->db->database);
			if ($mysqli->connect_errno) {
			    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
			$op=$mysqli->query($_POST["original"]);
			$fields=$op->fetch_fields();
			$metadata_array=array();
			foreach($fields as $fld)
			{
				$fld_info=array();
				$fld_info["id"]=$id;
				$fld_info["field_name"]=$fld->name;
				$fld_info["field_type"]=$mysql_data_type_hash[$fld->type];
				array_push($metadata_array,$fld_info);

			}

			$this->db->insert_batch("query_metadata",$metadata_array);
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
