<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ajaxCommands extends MY_Controller {

	//****************** AJAX FUNCTIONS ******************//
	public function index() {
		redirect('welcome');
	}
	
	public function updateTaskStatus() {
		$task = $this->input->get('task');
		$arr = explode('-',$task);
		$task = $arr[1];
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
			$this->load->model('person');
			$this->person->init($member->user_id);
			$task->members[] = (array) $this->person->getPerson();
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
	
	public function addTask() {
		$name = $this->input->get('name');
		$description = $this->input->get('description');
		$dueon = $this->input->get('due');
		$project = $this->input->get('project');
		$createdon = date('Y-m-d H:i:s',time());
		$createdby = $this->session->userdata('user')['id'];
		
		$data = array(
				'name' => $name,
				'project_id' => $project,
				'description' => $description,
				'created_on' => $createdon,
				'created_by' => $createdby,
				'due_on' => $dueon,
				'status' => 0
		);
		
		$this->db->insert('task',$data);
		$tID = $this->db->insert_id();
		echo $tID;
	}
	
	public function checkForUpdate() {
		$changed = array();
		$tasks = $this->input->get('tasks');
		$project = $this->input->get('project');
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
	
	public function getAvailableMembers() {
		$id = $this->input->get('id');
		$type = $this->input->get('type');
		
		if($type == 'task') {
			$table = 'task_has_agent';
		} else {
			$table = 'meeting_has_members';
		}
		$ref = $type.'_id';
		
		//get the users, all of them
		$this->db->select('user_id');
		$agents = $this->db->get_where('sec_user',array('type' => 2));
		$users = array();
		foreach($agents->result() as $row) {
			$userID = $row->user_id;
			$users[] = $userID;
		}
		
		//get the full user objects of the remaining
		$userObjs = array();
		$this->load->model('person');
		foreach($users as $usr) {
			$this->person->init($usr);
			$userObjs[] = $this->person->getPerson();
			$this->person->unload();
		}
		echo json_encode($userObjs);
	}
	
	public function addMember() {
		$id = $this->input->get('id');
		$type = $this->input->get('type');
		$member = $this->input->get('member');
		
		if($type == 'task') {
			$table = 'task_has_agent';
			$data = array(
				'task_id' => $id,
				'user_id' => $member,
			);
		} else {
			$table = 'meeting_has_members';
			$data = array(
				'meeting_id' => $id,
				'user_id' => $member,
			);
		}
		$this->db->insert($table,$data);
		
		$this->load->model('person');
		$this->person->init($member);
		echo json_encode($this->person->getPerson());
	}
	
	public function upload() {
		set_include_path(get_include_path() . PATH_SEPARATOR . 'third_party/google-api-php-client/src');
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