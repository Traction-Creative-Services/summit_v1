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

	function login_user() {
		//check if we got a uname
		$uname = $this->input->post('uname');
		if(!$uname) {
			//exit if we dont
			return false;
		}
		//get the password
		$pword = $this->input->post('pword');
		

		//find the user
		$query = $this->db->get_where('sec_user',array( 'uname' => $uname));
		//return the user
		foreach($query->result() as $row) {
			if(password_verify($pword,$row->pass_hash)) {
				$this->id 	 = $row->user_id;
				$this->uname = $row->uname;
				$this->email = $row->email;
				$this->type  = $row->type;	
			}
			
		}

		//set the user to the session
		$this->session->set_userdata('user',array(
			'id' => $this->id,
			'uname' => $this->uname,
			'email' => $this->email,
			'type'  => $this->type
		));

		return true;
	}

}