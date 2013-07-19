<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class metacheck extends CI_Controller {

	function index()
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
		$mysqli = new mysqli("localhost", "root", "root", "test");
		if ($mysqli->connect_errno) {
		    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		$op=$mysqli->query("select District,Population from City Limit 0,1");	
		$fields=$op->fetch_fields();
		foreach($fields as $fld)
		{
			print_r($fld->name." = ".$mysql_data_type_hash[$fld->type]."<br/>");
		}
	}
}
