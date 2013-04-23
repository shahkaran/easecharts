<?php
	/**
	 *@category Model:Chart_model
	 *@Author : Karan Shah
	 *@Date : 25/01/2013
	 */

	class Chart_model extends CI_Model
	{
		/*Load database in constructor*/
		function Chart_model()
		{
			parent::__construct();
			$this->load->database();
		}
		/*
		GetData function
		@author:Karan Shah
		@Date: 07/02/2013
		
		*/
		function GetData($str_tablename)
		{
			$fields = $this->db->list_fields($str_tablename);
			$data=array();
			$arr_categories=array();
			$is_first=true;
			foreach($fields as $field)
				  if (!$is_first)
					  array_push($arr_categories,$field);
				  else
					$is_first=false;	  
			$data["categories"]=$arr_categories;

			/*
			Get all data
			and place in series array
			each object of series array has "name" property having name 
			and "data" property having array of values
			*/
			
			$this->db->select("*");
			$this->db->from($str_tablename);
			$str_data_query=$this->db->get();
			
			$arr_series_data=array();
			
			$str_query_result=$str_data_query->result_array();
			foreach($str_query_result as $arr_data_query)
			{
				$arr_data=array();
				$arr_data["name"]=$arr_data_query[$fields[0]];
				$arr_data["data"]=array();
			
				for ($loop_i=1;$loop_i<count($arr_data_query);$loop_i++)
					array_push($arr_data["data"],$arr_data_query[$fields[$loop_i]]);
			
				
				array_push($arr_series_data,$arr_data);
			}
			
			$data["series"]=$arr_series_data;

			echo json_encode($data);
		}
		/*
		function getGanttData()
		Author:Karan SHah
		Date: 26-02-2013*/
		function getGanttData()
		{
			$query=$this->db->query("SELECT name, DATE_FORMAT(`starting`,'%Y/%m/%d/%H/%i/%S')  AS  'starting', DATE_FORMAT(`ending`,'%Y/%m/%d/%H/%i/%S')  AS  'ending', compeleted
FROM  `gannt_data` limit 0,6");
			$data=array();
			foreach($query->result() as $result)
			{
				$record=array();
				$record["name"]=$result->name;
				$record["starting"]=$result->starting;
				$record["ending"]=$result->ending;
				$record["completed"]=$result->compeleted;
				array_push($data,$record);
			}
			echo json_encode($data);
		}

		/*
		function getGanttData()
		Author:Karan SHah
		Date: 26-02-2013*/
		function getGanttData2()
		{
			$query=$this->db->query("SELECT name, DATE_FORMAT(`starting`,'%Y/%m/%d/%H/%i/%S')  AS  'starting', DATE_FORMAT(`ending`,'%Y/%m/%d/%H/%i/%S')  AS  'ending', compeleted
FROM  `khushalganttchart` order by no");
			$data=array();
			foreach($query->result() as $result)
			{
				$record=array();
				$record["name"]=$result->name;
				$record["starting"]=$result->starting;
				$record["ending"]=$result->ending;
				$record["completed"]=$result->compeleted;
				array_push($data,$record);
			}
			echo json_encode($data);
		}
		
		/*
		GetData function
		@author:Karan Shah
		@Date: 07/02/2013
		
		Note: Not being used as sorting is implemented on client side
		*/
/*		function GetDataSorted($str_tablename,$sortcolumn)
		{
			$fields = $this->db->list_fields($str_tablename);
			$data=array();
			$arr_categories=array();
			$is_first=true;
			foreach($fields as $field)
				  if (!$is_first)
					  array_push($arr_categories,$field);
				  else
					$is_first=false;	  
			

			$this->db->select("*");
			$this->db->from($str_tablename);
			$this->db->where($fields[0],$sortcolumn);
			$str_sort_by=$this->db->get();
			
				
			
			$arr_str_sort=$str_sort_by->row_array();
			unset($arr_str_sort[$fields[0]]);
			asort($arr_str_sort);
			
			$str_first_field=$fields[0];
			$fields=array_keys($arr_str_sort);
			array_unshift($fields,$str_first_field);
			
			
			//take a  temporary array having all values of fields except first 
			//and assign to categories
			
			$temp_categories=$fields;
			array_shift($temp_categories);
			
			$data["categories"]=$temp_categories;
			
			
			//Get all data
			//and place in series array
			//each object of series array has "name" property having name 
			//and "data" property having array of values
			

			$this->db->select(implode(array_values($fields),","));
			$this->db->from($str_tablename);
			$str_data_query=$this->db->get();
			
			//echo $this->db->last_query();
		
			$arr_series_data=array();
			
			$str_query_result=$str_data_query->result_array();

			//var_dump($str_query_result);
				
			foreach($str_query_result as $arr_data_query)
			{
				$arr_data=array();
				$arr_data["name"]=$arr_data_query[$fields[0]];
				$arr_data["data"]=array();
			
				for ($loop_i=1;$loop_i<count($arr_data_query);$loop_i++)
					array_push($arr_data["data"],$arr_data_query[$fields[$loop_i]]);
			
				
				array_push($arr_series_data,$arr_data);
			}
			
			$data["series"]=$arr_series_data;

			echo json_encode($data);
		}
*/
		
	}

?>