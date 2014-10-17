<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
				case 1:
					redirect('/client/projects');
				case 2:
					redirect('/agent/projects');
			}
		} else {
			redirect('/welcome/','refresh');	
		}
		die("error ".$type);
	}

	public function _loadView($page,$data)
	{
		if(!isset($data['title'])) {
			$data['title'] = 'Project Management';
		}
		$data['page'] = $page;

		$this->load->view('component/header',$data);
		$this->load->view('page/'.$page,$data);
		$this->load->view('component/footer',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */