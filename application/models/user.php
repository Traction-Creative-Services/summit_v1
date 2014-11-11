<?php

class User extends CI_Model {
	
	var	$id 		= '';
	var $uname 		= '';
	var $email 		= '';
	var $type		= '';
	
	function __construct() 
	{
		parent::__construct();
		if(($user = $this->session->userdata('user'))) {
			$this->id = $user['id'];
			$this->uname = $user['uname'];
			$this->email = $user['email'];
		}
	}

	
}