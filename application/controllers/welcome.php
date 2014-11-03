<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index()
	{
		$data['title'] = 'Login';
		$this->_loadView('login',$data);
	}

	public function login() 
	{
		$this->load->model('user');
		$success = $this->user->login_user();
		if($success) {
			$type = $this->user->type;
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */