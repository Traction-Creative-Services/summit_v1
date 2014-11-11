<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ajaxCommands extends MY_Controller {

	//****************** AJAX FUNCTIONS ******************//
	public function index() {
		redirect('welcome');
	}
	
	public function updateTaskStatus() {
		$task = $this->input->get('task');
		$state = $this->input->get('newState');
		
		switch($state) {
			case 'ready':
				$state = 0;
				break;
			case 'doing':
				$state = 1;
				break;
			case 'review':
				$state = 2;
				break;
			case 'complete':
				$state = 3;
				break;
		}
		$this->db->where('task_id',$task);
		$this->db->update('task',array('status' => $state));
	}

	
	

	//****************** HELPER FUNCTIONS ******************//
	public function _loadProjects() 
	{
		$user = $this->session->userdata('user');
		$uid = $user['id'];
		$query = $this->db->get_where('project_has_agent',array('user_id' => $uid) );
		$projects = array();
		foreach($query->result() as $row) {
			$project = $this->db->get_where('project',array('project_id' => $row->project_id));
			$projects[] = $project->row();
		}
		return $projects;
	}
}