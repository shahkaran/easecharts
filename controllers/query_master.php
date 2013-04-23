<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class query_master extends CI_Controller {
	/*
	*@Author : Karan Shah
	*@Date : 6/02/2013
	Constructor of controller

	*/
	function query_master()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("query_model");
	}
	
	function delete($id)
	{

		if ($this->query_model->delete($id)==true)
			echo "SuccessFully Deleted";
		else
			echo "Some Error Occured";
	}
		
	function SaveQuery()
	{
		if ($this->query_model->SaveQuery());
			echo "Saved Successfully";
	}
	
	function getDataSources()
	{
		echo $this->query_model->getDataSources();	
	}
	
	function getFieldsInfo($id)	
	{
		echo $this->query_model->getFieldsInfo($id);
	}

	function executeStoredQuery($id)
	{
		echo $this->query_model->executeStoredQuery($id);
	}
	
	function executeQueryJson($id)
	{
		echo $this->query_model->executeQueryJson($id);	
		
	}
	
}