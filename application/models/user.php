<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {
	
	var $user_id 		= '';
	var $uname 		= '';
	var $email 		= '';
	var $type		= '';
	var $thumb		= '';
	var $initials		= '';
	var $first 		= '';
	var $last		= '';
	var $company		= '';
	var $address1		= '';
	var $address2 		= '';
	var $city		= '';
	var $state		= '';
	var $postal		= '';
	var $phone		= '';
	
	function __construct() 
	{
		parent::__construct();
	}
	
	public function load($id = null) {
		if(($user = $this->session->userdata('user')) && $id == null) {
			$this->user_id = $user['id'];
		} else {
			$this->user_id = $id;
		}
		$record = $this->db->get_where('sec_user',array('user_id' => $this->user_id))->row();
		$this->uname = $record->uname;
		$this->email = $record->email;
		$this->type = $record->type;
		$this->thumb = base_url('assets/uploads/'.$record->thumb);
		$this->initials = $record->initials;
		$this->first = $record->first;
		$this->last = $record->last;
		$this->address1 = $record->address1;
		$this->address2 = $record->address2;
		$this->city = $record->city;
		$this->state = $record->state;
		$this->postal = $record->postal;
		$this->phone = $record->phone;
		
		//$this->company == $this->db->get_where('company',array('company_id' => $record->company_id));
	}
	
	public function getUser() {
		return $this;
	}
	
	public function saveUser() {
		$this->db->where('user_id',$this->user_id);
		$this->db->update('sec_user',$this);
	}
	
	public function deleteUser() {
		$this->db->where('user_id',$this->user_id);
		$this->db->update('sec_user', array('type','500'));
	}
	
	

	
}