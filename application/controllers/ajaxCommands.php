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
	
	public function getTask() {
		$id = $this->input->get('task');
		$task = $this->db->get_where( 'task', array( 'task_id' => $id ) )->row();
		//set the id for the task
		$tId = $task->task_id;
	
		//get the task members
		$members = $this->db->get_where( 'task_has_agent', array( 'task_id' => $tId ) );
		$task->members = array();
		foreach ( $members->result() as $member ) {
			$agent = $this->db->get_where( 'sec_user', array( 'user_id' => $member->user_id ) )->row();
			$task->members[] = $agent;
		}
	
		//check if it's past due
		$dueOn = strtotime($task->due_on);
		$dueOn += (60 * 60 * 24);
		$now = time();
	
		if( $now < $dueOn && $now > ($dueOn - (60*60*24)) ) {
			$task->dueState = 'now';
		}
		else if( $now < $dueOn ) {
			$task->dueState = 'ontime';
		}
		else if( $now > $dueOn ) {
			$task->dueState = 'late';
		}
		
		$taskArray = (array) $task;
		header('Content-Type: application/json');
		echo json_encode( $taskArray );
	}
	
	public function updateTask() {
		$id = $this->input->get('task');
		$description = $this->input->get('description');
		$dueon = $this->input->get('due');
		
		$this->db->where('task_id',$id);
		$this->db->update('task',array('description' => $description, 'due_on' => $dueon ));
	}
	
	public function checkForUpdate() {
		$changed = array();
		$tasks = $this->input->post('tasks');
		$project = $this->input->post('project');
		$this->load->model('Project','project');
		$this->project->init($project);
		
		$newTasks = $this->project->tasks;
		foreach ($tasks as $task) {
			$curTasks[$task['id']] = $task;
		}
		foreach($newTasks as $task) {
			$isDifferent = false;
			$checkAgainst = $curTasks[$task->task_id];
			if($task->name != $checkAgainst['title'])
				$isDifferent = true;
			if($task->due_on != $checkAgainst['dueDate'])
				$isDifferent = true;
			if($task->description != $checkAgainst['description'])
				$isDifferent = true;
			if($isDifferent)
				$changed[] = $task->task_id;
		}
		echo json_encode($changed);
	}
	
	public function saveStartTimer() {
		$task = $this->input->get('task');
		$usr = $this->session->userdata('user');
		$usrId = $usr['id'];
		$now = date("Y-m-d H:i:s");
		$data = array(
			'task_id' => $task,
			'user_id' => $usrId,
			'type'    => 'start',
			'time'    => $now
		);
		$this->db->insert('task_log',$data);
	}
	
	public function saveEndTimer() {
		$task = $this->input->get('task');
		$usr = $this->session->userdata('user');
		$usrId = $usr['id'];
		$now= date("Y-m-d H:i:s");
		$data = array(
			'task_id' => $task,
			'user_id' => $usrId,
			'type' => 'end',
			'time' => $now
		);
		$this->db->insert('task_log',$data);
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