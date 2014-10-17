<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent extends CI_Controller {

	//****************** PAGE FUNCTIONS ******************//
	public function projects() {
		$data['user'] = $this->session->userdata('user');
		$data['title'] = 'Projects';
		$data['projects'] = $this->_loadProjects();
		$this->_loadView('agentProjects',$data);
	}

	public function projectDetail($id) {
		ini_set('display_errors','1');
		error_reporting(E_ALL);
		$data['user'] = $this->session->userdata('user');
		$project = $this->_loadProject($id);
		$data['title'] = $project->name.' Details';
		$data['project'] = $project;
		$this->_loadView('projectDashboard',$data);
	}


	//****************** HELPER FUNCTIONS ******************//
	public function _loadProjects() 
	{
		$user = $this->session->userdata('user');
		$uid = $user['id'];
		$query = $this->db->get_where('project_has_agent',array('pUserId' => $uid) );
		$projects = array();
		foreach($query->result() as $row) {
			$project = $this->db->get_where('project',array('project_id' => $row->pId));
			$projects[] = $project->row();
		}
		return $projects;
	}

	public function _loadProject($id) 
	{
		$query = $this->db->get_where('project',array('project_id' => $id));
		$project = $query->row();
		return $project;
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