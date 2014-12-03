<div class="container project-wrapper" id="<?php echo $project->id; ?>">

	<div class="row">
		<div class="col-md-3 task-column" id="ready-column" ondragover="allowDrop(event)" ondrop="drop(event)">
			<p class="lead">Ready</p>
			<?php foreach($project->tasks as $task) {
				if ( $task->status == 0 ) { ?>
					<article class="task" draggable="true" id="task-<?php echo $task->task_id; ?>">
						<header>
							<p class="lead"><?php echo $task->name; ?></p>
							<span class="due-on <?php echo $task->dueState; ?>"><?php echo $task->due_on; ?></span>
						</header>
						<span class="glyphicon glyphicon-time timer-start" id="timer-<?php echo $task->task_id; ?>"></span>
						<p class="task-description"><?php echo $task->description; ?></p>
						<footer>
							<ul class="members">
								<?php foreach($task->members as $member) { ?>
									<li class="member-head" id="<?php echo $member->user_id; ?>">
										<img alt="<?php echo $member->initials; ?>" src="<?php echo base_url('assets/uploads/'.$member->thumb); ?>">
									</li>
								<?php } ?>
							</ul>
							
							<span class="more-btn" id="more-btn-<?php echo $task->task_id; ?>">...</span>
						</footer>
					</article>
				 <?php }
			}?>
		</div>
		<div class="col-md-3 task-column" id="doing-column" ondragover="allowDrop(event)" ondrop="drop(event)">
			<p class="lead">Doing</p>
			<?php foreach($project->tasks as $task) {
				if ( $task->status == 1 ) { ?>
					<article class="task" draggable="true" id="task-<?php echo $task->task_id; ?>">
						<header>
							<p class="lead"><?php echo $task->name; ?></p>
							<span class="due-on <?php echo $task->dueState; ?>"><?php echo $task->due_on; ?></span>
						</header>
						<span class="glyphicon glyphicon-time timer-start" id="timer-<?php echo $task->task_id; ?>"></span>
						<p class="task-description"><?php echo $task->description; ?></p>
						<footer>
							<ul class="members">
								<?php foreach($task->members as $member) { ?>
									<li class="member-head" id="<?php echo $member->user_id; ?>">
										<img alt="<?php echo $member->initials; ?>" src="<?php echo base_url('assets/uploads/'.$member->thumb); ?>">
									</li>
								<?php } ?>
							</ul>
							<span class="more-btn" id="more-btn-<?php echo $task->task_id; ?>">...</span>
						</footer>
					</article>
				 <?php }
			} ?>
		</div>
		<div class="col-md-3 task-column" id="review-column" ondragover="allowDrop(event)" ondrop="drop(event)">
			<p class="lead">Review</p>
			<?php foreach($project->tasks as $task) {
				if ( $task->status == 2 ) { ?>
					<article class="task" draggable="true" id="task-<?php echo $task->task_id; ?>">
						<header>
							<p class="lead"><?php echo $task->name; ?></p>
							<span class="due-on <?php echo $task->dueState; ?>"><?php echo $task->due_on; ?></span>
						</header>
						<span class="glyphicon glyphicon-time timer-start" id="timer-<?php echo $task->task_id; ?>"></span>
						<p class="task-description"><?php echo $task->description; ?></p>
						<footer>
							<ul class="members">
								<?php foreach($task->members as $member) { ?>
									<li class="member-head" id="<?php echo $member->user_id; ?>">
										<img alt="<?php echo $member->initials; ?>" src="<?php echo base_url('assets/uploads/'.$member->thumb); ?>">
									</li>
								<?php } ?>
							</ul>
							<span class="more-btn" id="more-btn-<?php echo $task->task_id; ?>">...</span>
						</footer>
					</article>
				<?php }
			}?>
		</div>
		<div class="col-md-3 task-column" id="complete-column" ondragover="allowDrop(event)" ondrop="drop(event)">
			<p class="lead">Complete</p>
			<?php foreach($project->tasks as $task) {
				if ( $task->status == 3 ) { ?>
					<article class="task" draggable="true" id="task-<?php echo $task->task_id; ?>">
						<header>
							<p class="lead"><?php echo $task->name; ?></p>
							<span class="due-on <?php echo $task->dueState; ?>"><?php echo $task->due_on; ?></span>
						</header>
						<span class="glyphicon glyphicon-time timer-start" id="timer-<?php echo $task->task_id; ?>"></span>
						<p class="task-description"><?php echo $task->description; ?></p>
						<footer>
							<ul class="members">
								<?php foreach($task->members as $member) { ?>
									<li class="member-head" id="<?php echo $member->user_id; ?>">
										<img alt="<?php echo $member->initials; ?>" src="<?php echo base_url('assets/uploads/'.$member->thumb); ?>">
									</li>
								<?php } ?>
							</ul>
							<span class="more-btn" id="more-btn-<?php echo $task->task_id; ?>">...</span>
						</footer>
					</article>
				 <?php }
			}?>
		</div>
	</div>
</div>


<!-- MODALS -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="taskModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="taskModalhiddenIdField" value=""/>
	<div class="form-group">
		<label for="taskModaldescriptionField">Description</label>
		<textarea id="taskModaldescriptionField" class="form-control"></textarea>	
	</div>
	<div class="form-group">
		<label for="taskModaldueDateField">Due On</label>
		<input type="date" value="" id="taskModaldueDateField" class="form-control" />	
	</div>
	<ul class="members" id="taskModalMemberList">
	</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveModalTask">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="drawer" tabindex="-1" role="dialog"  id="meetingDrawer">
	<div id="meetingDrawerTab" class="pull-tab glyphicon glyphicon-calendar" data-target="meetingDrawer" data-state="closed">
		<!--span class="glyphicon glyphicon-calendar tab-trigger" aria-hidden="true"></span-->
	</div>
	<div class="drawer-inner">
		<p class="lead">Meetings</p>
		<hr />
		<div class="meetings-container">
			<?php foreach($project->meetings as $meeting) { ?>
				<article class="meeting" id="meeting-<?php echo $meeting->meeting_id; ?>">
					<header class="meeting-header">
						<p class="lead"><?php echo $meeting->name; ?></p>
					</header>
					<p><?php echo $meeting->description; ?></p>
					<div class="attendees well">
						<ul class="members">
							<?php foreach($meeting->members as $member) { ?>
								<li class="member-head <?php if($member->user_id == $meeting->host_id) echo 'host'; ?>" id="<?php echo $member->user_id; ?>">
									<img alt="<?php echo $member->initials; ?>" src="<?php echo base_url('assets/uploads/'.$member->thumb); ?>">
								</li>
							<?php } ?>
						</ul>
					</div>
					<footer>
						<p>On <?php echo date('M, j',strtotime($meeting->when)); ?> At <?php echo date('h:i A',strtotime($meeting->when)); ?> <a target="_blank" href="https://www.google.com/maps/search/<?php echo urlencode(str_replace(" ", "+", $meeting->where)); ?>"><span class="glyphicon glyphicon-map-marker"></span></a> </p>
					</footer>
				</article>
			 <?php } ?>
		</div>
	</div>
</div>