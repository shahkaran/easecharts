<?php
	/**
	 *@category Model:Chartdata_model
	 *@Author : Karan Shah
	 *@Date : 25/03/2013
	 */

	class Chartdata_model extends CI_Model
	{
		/*Load database in constructor*/
		function Chartdata_model()
		{
			parent::__construct();
			$this->load->database();
		}
		
		/*
		function getNoOfCharts
		
		returns no of charts to be shown on homepage
		
		@author:Karan Shah
		@Date: 2/4/2013
		
		*/
		
		
		function getNoOfCharts()
		{
			$nos=$this->db->count_all('charts_info');	
			return $nos;
		}
		
		/*
		function getNames
		*/
		function getNames()
		{
			$this->db->select("obj_id");
			$this->db->from("charts_info");
			$query=$this->db->get();
			return $query->result_array();	
				
			
		}

		/*
		function addNew
		
		add new chart info
		
		@Author:Karan Shah
		@Date:30/03/2013
		*/

		
		function addNew()
		{
			$chart_types=array();
			
			$chart_types[1]="line";
			$chart_types[2]="column";
			$chart_types[3]="bar";
			$chart_types[4]="pie";
			
			$chart_types=array_flip($chart_types);
			
			$charts_info=$_POST;
				

			$charts_info["type"]=$chart_types[$_POST["type"]];
			unset($charts_info["category"]);
			unset($charts_info["series"]);			
			
			$this->db->insert("charts_info",$charts_info);
			
			$chart_id=$this->db->insert_id();	
			
			$chartdata_info=array();
			
			$category_data=array();
			$category_data["chart_no"]=$chart_id;
			$category_data["field_type"]=0;
			$category_data["field_name"]=$_POST["category"];
			
			array_push($chartdata_info,$category_data);
			
			$series_data_array=explode(",",$_POST["series"]);
	
			foreach($series_data_array as $series)
			{
	
			$series_data=array();
			$series_data["chart_no"]=$chart_id;
			$series_data["field_type"]=1;
			$series_data["field_name"]=$series;
				
			array_push($chartdata_info,$series_data);			
			}
			
			$this->db->insert_batch("chartdata_info",$chartdata_info);		
		}
		
		/*
		function getBasicInfo
		@Author: Karan Shah
		@Date: 2/4/2013
		*/
		
		function getBasicInfo($obj_id)
		{
			$query=$this->db->get_where('charts_info', array('obj_id' => $obj_id));
			
			$result=$query->row_array();
			
			echo json_encode($result);
		}
		
		
		/*
		function getCategory
		@Author:Karan Shah
		@Date:2/4/2013
		*/
		
		function getCategory($chartno)
		{
			$query=$this->db->get_where("chartdata_info",array("chart_no"=>$chartno,"field_type"=>0));
			$result=$query->row_array();
			
			echo $result["field_name"];
		}
		
		/*
		function getSeries
		@Author:Karan Shah
		@Date:2/4/2013
		*/
		
		function getSeries($chartno)
		{
			$query=$this->db->get_where("chartdata_info",array("chart_no"=>$chartno,"field_type"=>1));
			$result=$query->result_array();
			
			$series=array();
			
			foreach($result as $row)
			{
				array_push($series,$row["field_name"]);
			}
			
			echo json_encode($series);
		}

		function delete($chart_obj)
		{
			$this->db->delete("charts_info",array("obj_id"=>$chart_obj));
			return true;	
		}
		
	}