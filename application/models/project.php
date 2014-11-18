<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Model {
	
	var $id 		= '';
	var $name 		= '';
	var $start 		= '';
	var $end		= '';
	var $agents 		= array();
	var $client 		= array();
	var $tasks      	= array();
	var $meetings 		= array();
	var $notes		= array();

	
	function __construct() 
	{
		parent::__construct();
	}

	public function init($id) {
		$this->id = $id;
		$this->_loadAttributes();
		$this->_loadTasks();
		$this->_loadMeetings();
		$this->_loadNotes();
		$this->_loadAgents();
		//$this->_loadClient();
	}
	
	//****************** HELPER FUNCTIONS ******************//
	
	private function _LoadAttributes() {
		$query = $this->db->get_where( 'project', array( 'project_id' => $this->id ) );
		$project = $query->row();
		$this->name = $project->name;
		$this->start = $project->start;
		$this->end = $project->end;
	}

	private function _loadTasks() {
		$query = $this->db->get_where( 'task', array( 'project_id' => $this->id ) );
		foreach( $query->result() as $task ) {
			//set the id for the task
			$tId = $task->task_id;

			//get the task members
			$members = $this->db->get_where( 'task_has_agent', array( 'task_id' => $tId ) );
			$task->members = array();
			foreach ( $members->result() as $member ) {
				$agent = $this->db->get_where( 'sec_user', array( 'user_id' => $member->user_id ) )->row();
				$task->members[] = $agent;
			}
			
			//get the task lists
			$lists = $this->db->get_where( 'task_has_list', array( 'task_id' => $tId ) );
			$task->lists = array();
			foreach ( $lists->result() as $list ) {
				$list_object = $this->db->get_where( 'list', array( 'list_id' => $list->list_id ) )->row();
				$list_object->list_items = array();
				foreach ( $list_object->result() as $item ) {
					$list_item = $this->db->get_where( 'list_item', array( 'item_id' => $item->item_id ) )->row();
					$list_object->list_items[] = $list_item;
				}
				$task->lists[] = $list_object;
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
			

			$this->tasks[] = $task;
		}
	}

	private function _loadMeetings() {
		$query = $this->db->get_where( 'meeting', array('project_id' => $this->id ) );
		foreach( $query->result() as $meeting ) {
			//set the id for the meeting
			$mId = $meeting->meeting_id;
			
			//get the meeting members
			$members = $this->db->get_where( 'meeting_has_members', array( 'meeting_id' => $mId ) );
			$meeting->members = array();
			foreach ( $members->result() as $member ) {
				$agent = $this->db->get_where( 'sec_user', array( 'user_id' => $member->user_id ) )->row();
				$meeting->members[] = $agent;
			}
			
			//get the meeting lists
			$lists = $this->db->get_where( 'meeting_has_list', array( 'meeting_id' => $mId ) );
			$meeting->lists = array();
			foreach ( $lists->result() as $list ) {
				$list_object = $this->db->get_where( 'list', array( 'list_id' => $list->list_id ) )->row();
				$list_object->list_items = array();
				foreach ( $list_object->result() as $item ) {
					$list_item = $this->db->get_where( 'list_item', array( 'item_id' => $item->item_id ) )->row();
					$list_object->list_items[] = $list_item;
				}
				$meeting->lists[] = $list_object;
			}
			
			
			
			$this->meetings[] = $meeting;
		}
	}

	private function _loadNotes() {
		$query = $this->db->get_where( 'note', array( 'project_id' => $this->id ) );
		foreach( $query->result() as $note ) {
			$this->notes[] = $note;
		}
	}

	private function _loadAgents() {
		$query = $this->db->get_where( 'project_has_agent', array( 'project_id' => $this->id ) );
		foreach( $query->result() as $pair ) {
			$id = $pair->user_id;
			$query = $this->db->get_where( 'sec_user', array( 'user_id' => $id ) );
			$agent = $query->row();
			$this->agents[] = $agent;
		}
	}

	private function _loadClient() {
		$query = $this->db->get_where( 'client', array( 'client_id' => $this->client_id ) );
		$this->client = $query->row();
	}

}