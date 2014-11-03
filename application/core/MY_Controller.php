<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	//TASK STATE CONSTANTS
	var $TASK_STATE_READY = 0;
	var $TASK_STATE_DOING = 1;
	var $TASK_STATE_REVIEW = 2;
	var $TASK_STATE_COMPLETE = 3;

	//USER TYPE CONSTANTS
	var $USER_AGENT = 2;
	var $USER_CLIENT = 1;

	public function __construct() {
		parent::__construct();

	}

	public function _loadView($page,$data = array())
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