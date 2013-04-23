<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class setup extends CI_Controller {

	function setup()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	
	function index()
	{
		$this->load->view("header");
		$this->load->view("footer");	
		
	}
	
}

?>
