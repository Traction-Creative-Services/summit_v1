<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index()
	{
		$data['title'] = 'Login';
		$this->_loadView('login',$data);
	}

	public function login() 
	{
		$user = $this->login_user();
		if($user) {
			$type = $user['type'];
			switch($type) {
				case $this->USER_CLIENT:
					redirect('/client/projects');
				case $this->USER_AGENT:
					redirect('/agent/projects');
			}
		} else {
			redirect('/welcome/','refresh');	
		}
		die("error ".$type);
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
			//if(password_verify($pword,$row->pass_hash)) {
				$id    = $row->user_id;
				$uname = $row->uname;
				$email = $row->email;
				$type  = $row->type;	
			//}
			
		}
		
		$userArray = array(
			'id' 	=> $id,
			'uname' => $uname,
			'email' => $email,
			'type'  => $type
			);

		//set the user to the session
		$this->session->set_userdata('user',$userArray);

		return $userArray;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */