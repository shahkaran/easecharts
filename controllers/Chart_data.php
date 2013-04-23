<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chart_data extends CI_Controller {
	/*
	*@Author : Karan Shah
	*@Date : 6/02/2013
	Constructor of controller

	*/
	function Chart_data()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("Chartdata_model");

	}
	
	/*
	function AddNew
	@Author: Karan Shah
	@Date:2/4/2013
	*/
	
	function AddNew()
	{
		$this->Chartdata_model->addNew();
		echo "Added";
	}
	
	
	/*
	function getChartDataAtPosition	
	@Author: Karan Shah
	@Date:2/4/2013
	*/
	
	function getChartData($obj_id)
	{
		$this->Chartdata_model->getBasicInfo($obj_id);
	}
	
	/*
	function getCategoryName
	@Author:Karan Shah
	@Date:2/4/2013
	*/
	
	function getCategoryName($chartno)
	{
		echo $this->Chartdata_model->getCategory($chartno);
		
	}

	/*
	function getSeriesName
	@Author:Karan Shah
	@Date:2/4/2013
	*/
	
	function getSeriesName($chartno)
	{
		echo $this->Chartdata_model->getSeries($chartno);
		
	}

	function delete($chart_obj)
	{
		if ($this->Chartdata_model->delete($chart_obj))
			echo "SuccessFully Deleted";
		else
			echo "Error In deleting";
	}

}