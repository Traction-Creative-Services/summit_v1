<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('display_errors','1');
		error_reporting(E_ALL);
class Client extends MY_Controller {

	//****************** PAGE FUNCTIONS ******************//
	public function projects() {
		$data['user'] = $this->session->userdata('user');
		$data['title'] = 'Projects';
		$data['projects'] = $this->_loadProjects();
		$this->_loadView('clientProjects',$data);
	}

	public function projectDetail($id) {
		$data['user'] = $this->session->userdata('user');
		$this->load->model('Project','project');
		$this->project->init($id);
		$data['title'] = $this->project->name.' Details';
		
        $project = $this->project;
        $project->readyCount = 0;
        $project->doingCount = 0;
        $project->reviewCount = 0;
        $project->completeCount = 0;
		$project->ontimeCount = 0;
		$project->lateCount = 0;
        foreach($project->tasks as $task) {
            switch($task->status) {
                case $this->TASK_STATE_READY:
                    $project->readyCount++;
					if($task->dueState == 'late') {
						$project->lateCount++;
					} else {
						$project->ontimeCount++;
					}
                    break;
                case $this->TASK_STATE_DOING:
                    $project->doingCount++;
					if($task->dueState == 'late') {
						$project->lateCount++;
					} else {
						$project->ontimeCount++;
					}
                    break;
                case $this->TASK_STATE_REVIEW:
                    $project->reviewCount++;
					if($task->dueState == 'late') {
						$project->lateCount++;
					} else {
						$project->ontimeCount++;
					}
                    break;
                case $this->TASK_STATE_COMPLETE:
                    $project->completeCount++;
                    break;
            }
        }
        
        $data['project'] = $project;
        
		$this->_loadView('projectReportCard',$data);
	}
	

	//****************** HELPER FUNCTIONS ******************//
	public function _loadProjects() 
	{
		$user = $this->session->userdata('user');
		$uid = $user['id'];
		$query = $this->db->get_where('project',array('client_id' => $uid) );
		$projects = array();
		foreach($query->result() as $row) {
			$projects[] = $row;
		}
		return $projects;
	}
}