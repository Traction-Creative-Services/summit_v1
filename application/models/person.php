<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {
	var $user_id 		= '';
	var $uname 		= '';
	var $email 		= '';
	var $type		= '';
	var $thumb		= '';
	var $initials		= '';
	var $first		= '';
	var $last		= '';
	var $company_id		= '';
	var $address1		= '';
	var $address2		= '';
	var $city		= '';
	var $state		= '';
	var $postal		= '';
	var $phone		= '';
	var $deleted		= '';

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