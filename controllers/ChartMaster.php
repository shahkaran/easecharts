<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ChartMaster extends CI_Controller 
{
	 /*
	 index function
	 load chart master view
	 */
	public function index()
	{
		$this->load->view("header");
		$this->load->view("js_css_loader");
		$this->load->view("ChartMasterMain");		
		$this->load->view("footer");	
	}
	/*
	*@Author : Karan Shah
	*@Date : 1/03/2013
	Constructor of controller

	*/
	function ChartMaster()
	{
		parent::__construct();
		$this->load->helper('url');
	}	
	
	/*
	Chart Setting function
	*@Author : Karan Shah
	*@Date : 1/03/2013

	THis function is used to show page for creating new chart
	*/
	function NewChart()
	{
		$this->load->database();
		$tables=$this->db->list_tables();
		$tables_array=array();
		foreach($tables as $table)
		{
			$tables_array[$table]=$this->db->field_data($table);
		}
		$data=array("tables"=>$tables_array);
		$this->load->view("header");
		$this->load->view("js_css_loader");		
		$this->load->view("new_chart_script");			
		$this->load->view("new_chart",$data);	
		$this->load->view("footer");		
	}

	/*
	function NewQuery
	@author:Karan Shah
	@Date:19/03/13
	*/
	function NewQuery()
	{
		$this->load->database();
		$tables=$this->db->list_tables();
		$tables_array=array();
		foreach($tables as $table)
		{
			$tables_array[$table]=$this->db->field_data($table);
		}
		$data=array("tables"=>$tables_array);
		$this->load->view("header");
		$this->load->view("js_css_loader");		
		$this->load->view("chart_setting_script");	
		$this->load->view("chart_settings",$data);	
		$this->load->view("footer");				
	}
	
	
	
	/*
	*@Author : Karan Shah
	*@Date : 16/04/2013
	View All Queries 

	*/
	function ViewQueries()
	{
		$this->load->model("query_model");
		
		$no=$this->query_model->getCount();
		$names=$this->query_model->getAllName();
		
		$data=array();
		$data["type"]="query";
		$data["count"]=$no;
		$data["names"]=$names;
		
		$this->load->view("header");
		$this->load->view("js_css_loader");
		$this->load->view("view_query_script");		
		$this->load->view("view_queries",$data);		
		$this->load->view("footer");	
		
	}	
	
	/*
	*@Author : Karan Shah
	*@Date : 16/04/2013
	View All Charts

	*/
	function ViewCharts()
	{
		$this->load->model("Chartdata_model");
		
		$no=$this->Chartdata_model->getNoOfCharts();
		$names=$this->Chartdata_model->getNames();
		
		$data=array();
		$data["type"]="chart";
		$data["count"]=$no;
		$data["names"]=$names;
		
		$this->load->view("header");
		$this->load->view("js_css_loader");
		$this->load->view("view_query_script");		
		$this->load->view("view_queries",$data);		
		$this->load->view("footer");	
		
	}	
	
	

}