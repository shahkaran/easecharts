<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class chart_misc extends CI_Controller {

	/**
	 *@category Controllers:Chart COntroller
	 *@Author : Karan Shah
	 *@Date : 6/02/2013
	 */
	 
	/*
	*@Author : Karan Shah
	*@Date : 6/02/2013
	Constructor of controller

	*/
	function chart_misc()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	

	/*
	Home page function
	*@Author : Karan Shah
	*@Date : 6/02/2013

	THis function is used to show home page
	*/
	function homepage()
	{

		$this->load->view("header");
		$this->load->view("js_css_loader");		
		$this->load->view("homepage_script");		
		$this->load->view("chart2");	
		$this->load->view("footer");		
	}
	
	/*
	function test
	@author:Karan Shah
	@date:23/03/2013
	
	used for testing new things to be added 
	*/
	function test()
	{
		$this->load->view("test_header");
		$this->load->view("test");	
		
	}
	
	

	
	/*
	function executeQuery
	
	Used to execute any query passed <br />

	@author:Karan Shah
	@Date: 7/03/2013
	*/
	function executeQuery()
	{
		$query=$_POST["query"];
		$this->load->database();
		$query_obj=$this->db->query($query);
		$results=$query_obj->result_array();
		echo "<table width=75% border=2>";
		$columns=$query_obj->list_fields();
		echo "<tr>";
		foreach($columns as $column)
			echo "<th>".$column."</th>";
		echo "</tr>";
		foreach($results as $result)
		{
			echo "<tr>";
			foreach($result as $field)
				echo "<td>".$field."</td>";
			echo "</tr>";	
		}
		echo "</table>";
	}

	/*
	function getData
	@author:Karan Shah
	@Date: 7/02/2013
	
	loads model and returns json data
	
	Parameter: String (type of data to be fetched e.g. Student marks,Android App analytics)
	
	Returns : Json String
	*/
	
	function getData($data)
	{
		$this->load->model("chart_model");	
		if ($data==1)
			return $this->chart_model->GetData("column_data");	
		else if ($data==2)
			return $this->chart_model->GetData("line_data");			
	}
	/*
	function getData
	@author:Karan Shah
	@Date: 7/02/2013
	
	loads model and returns json data for gantt chart
	
	
	Returns : Json String
	*/	
	function getGanttData()
	{
		$this->load->model("chart_model");	
		$this->chart_model->getGanttData2();		
	}
	
	
	
/*	function getSort($data,$sortby)
	{
		$this->load->model("chart_model");			
		if ($data==1)
			return $this->chart_model->GetDataSorted("column_data",$sortby);	
		else if ($data==2)
			return $this->chart_model->GetDataSorted("line_data",$sortby);			
		
		
	}
*/
	function exportData()
	{
		/*-------------------------------------------------------------------------*
			@category function : to export the data as a file
			@author : Jay Vora
        	@date : 13/02/2013
			@return : file in the formate which is specified.
			
			Modified by Karan Shah 
		----------------------------------------------------------------------------*/
		
		date_default_timezone_set('Asia/Calcutta');
	
		$html = $this->input->post("data");
		echo $html;

		header("Content-Type:  application/msexcel");
		header("Content-Disposition: attachment; filename=Report.xls");
		header("Pragma: no-cache");
		header("Expires: 0");



	}


}


/* End of file student.php */
/* Location: ./application/controllers/student.php */