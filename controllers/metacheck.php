<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class metacheck extends CI_Controller {

	function get_metadata($query)
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
		$this->load->database();
		$mysqli = new mysqli($this->db->hostname,$this->db->username,$this->db->password,$this->db->database);
		if ($mysqli->connect_errno) {
		    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		$op=$mysqli->query($query);	
		$fields=$op->fetch_fields();
		$metadata_array=array();
		$id=1;
		foreach($fields as $fld)
		{
			$fld_info=array();
			$fld_info["id"]=$id;
			$fld_info["field_name"]=$fld->name;
			$fld_info["field_type"]=$mysql_data_type_hash[$fld->type];
			array_push($metadata_array,$fld_info);
			
		}
		print_r($metadata_array);

	}
}
