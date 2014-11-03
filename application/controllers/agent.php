<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('display_errors','1');
		error_reporting(E_ALL);
class Agent extends MY_Controller {

	//****************** PAGE FUNCTIONS ******************//
	public function projects() {
		$data['user'] = $this->session->userdata('user');
		$data['title'] = 'Projects';
		$data['projects'] = $this->_loadProjects();
		$this->_loadView('agentProjects',$data);
	}

	public function projectDetail($id) {
		$data['user'] = $this->session->userdata('user');
		$this->load->model('Project','project');
		$this->project->init($id);
		$data['title'] = $this->project->name.' Details';
		$data['project'] = $this->project;
		$this->_loadView('projectDashboard',$data);
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