<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Person extends CI_Model {
	var $user_id 	= '';
	var $uname 		= '';
	var $email 		= '';
	var $type		= '';
	var $thumb		= '';
	var $initials	= '';
	var $first		= '';
	var $last		= '';
	var $company_id	= '';
	var $address1	= '';
	var $address2	= '';
	var $city		= '';
	var $state		= '';
	var $postal		= '';
	var $phone		= '';
	var $deleted	= '';
	var $company	= '';

	function __construct() {
		parent::__construct();
	}

	public function init($id) {
		if(($user = $this->session->userdata('user')) && $id == null) {
			$this->user_id = $user['id'];
		} else {
		    $this->user_id = $id;
		}
		$this->_loadAttributes();
	}
	
	public function unload() {
		$this->_unsetAttributes();
	}
	
	public function getPerson() {
		$_tempObj = new stdClass();
		$_tempObj->uname = $this->uname;
		$_tempObj->email = $this->email;
		$_tempObj->type = $this->type;
		$_tempObj->thumb = $this->thumb;
		$_tempObj->initials = $this->initials;
		$_tempObj->first = $this->first;
		$_tempObj->last = $this->last;
		$_tempObj->company_id = $this->company_id;
		$_tempObj->address1 = $this->address1;
		$_tempObj->address2 = $this->address2;
		$_tempObj->city = $this->city; 		
		$_tempObj->state = $this->state; 		
		$_tempObj->postal = $this->postal; 	
		$_tempObj->phone = $this->phone;		
		$_tempObj->deleted = $this->deleted;
		$_tempObj->id = $this->user_id;
		$_tempObj->company = $this->company;

		return $_tempObj;
	}

	private function _loadAttributes() {
		$user_data = $this->db->get_where( 'sec_user', array( 'user_id' => $this->user_id ) )->row();
		$this->uname = $user_data->uname;
		$this->email = $user_data->email;
		$this->type = $user_data->type;
		$this->thumb = $user_data->thumb;
		$this->initials = $user_data->initials;
		$this->first = $user_data->first;
		$this->last = $user_data->last;
		$this->company_id = $user_data->company_id;
		$this->address1 = $user_data->address1;
		$this->address2 = $user_data->address2;
		$this->city = $user_data->city;
		$this->state = $user_data->state;
		$this->postal = $user_data->postal;
		$this->phone = $user_data->phone;
		$this->deleted = $user_data->deleted;
		if($this->company_id != null && $this->company_id != '') 
			$this->company = $this->db->get_where( 'company', array( 'company_id' => $this->company_id ) )->row();
	}
	
	private function _unsetAttributes() {
		$this->uname = 		null;
		$this->email = 		null;
		$this->type =  		null;
		$this->thumb = 		null;
		$this->initials = 	null;
		$this->first = 		null;
		$this->last = 		null;
		$this->company_id = null;
		$this->address1 = 	null;
		$this->address2 = 	null;
		$this->city = 		null;
		$this->state = 		null;
		$this->postal = 	null;
		$this->phone = 		null;
		$this->deleted = 	null;
		$this->user_id = 	null;
		$this->company = 	null;
	}
	
	public function saveAttributes() {
		$this->db->update( 'sec_user', $this , array( 'user_id' => $this->user_id ) );
	}
	
	public function deletePerson() {
		date_default_timezone_set('America/Chicago');
		$date_time = date('Y-m-d h:i:s', time());
		$date_time_array = array(
			'deleted' => $date_time
		);
		$this->db->update( 'sec_user', $date_time_array, array( 'user_id' => $this->user_id ) );
	}
}