<div class="container">
	<div class="navbar navbar-fixed-top navbar-inverse">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#"><?php echo $project->name; ?></a>
	    </div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Team <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<input type="search" id="agent-search" placeholder="Add Team Member..." class="form-control">
						<div id="agent-heads-container">
							<?php //echo $project->agentHeads; ?>
						</div>
					</ul>
				</li>
				<li class="dropdown">
					<a class="dropdwon-toggle" data-toggle="dropdown" href="#">Client <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<input type="search" id="client-search" placeholder="Add Client..." class="form-control">
						<div id="client-container">
							<?php //echo $project->clientCard; ?>
						</div>
					</ul>
				</li>
				<li class="active"><a>Starts on: <?php echo date('M-d-y',strtotime($project->start)); ?></a></li>
				<li class="active"><a>Launch on: <?php echo date('M-d-y',strtotime($project->end)); ?>&nbsp;&nbsp;&nbsp;</a></li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3" id="ready-column" ondragover="allowDrop(event)" ondrop="drop(event)">
			<p class="lead">Ready</p>
			<?php foreach($project->tasks as $task) {
				if ( $task->status == 0 ) { ?>
					<article class="task" draggable="true" id="<?php echo $task->task_id; ?>">
						<header>
							<p class="lead"><?php echo $task->name; ?></p>
							<span class="due-on <?php echo $task->dueState; ?>"><?php echo $task->due_on; ?></span>
						</header>
						<p class="task-description"><?php echo $task->description; ?></p>
						<footer>
							<ul class="members">
								<?php foreach($task->members as $member) { ?>
									<li class="member-head" id="<?php echo $member->user_id; ?>">
										<img alt="<?php echo $member->initials; ?>" src="<?php echo $member->thumb; ?>">
									</li>
								<?php } ?>
							</ul>
							<span class="more-btn" id="more-btn-<?php echo $task->task_id; ?>">...</span>
						</footer>
					</article>
				 <?php }
			}?>
		</div>
		<div class="col-md-3" id="doing-column" ondragover="allowDrop(event)" ondrop="drop(event)">
			<p class="lead">Doing</p>
			<?php foreach($project->tasks as $task) {
				if ( $task->status == 1 ) { ?>
					<article class="task" draggable="true" id="<?php echo $task->task_id; ?>">
						<header>
							<p class="lead"><?php echo $task->name; ?></p>
							<span class="due-on <?php echo $task->dueState; ?>"><?php echo $task->due_on; ?></span>
						</header>
						<p class="task-description"><?php echo $task->description; ?></p>
						<footer>
							<ul class="members">
								<?php foreach($task->members as $member) { ?>
									<li class="member-head" id="<?php echo $member->user_id; ?>">
										<img alt="<?php echo $member->initials; ?>" src="<?php echo $member->thumb; ?>">
									</li>
								<?php } ?>
							</ul>
							<span class="more-btn" id="more-btn-<?php echo $task->task_id; ?>">...</span>
						</footer>
					</article>
				 <?php }
			} ?>
		</div>
		<div class="col-md-3" id="review-column" ondragover="allowDrop(event)" ondrop="drop(event)">
			<p class="lead">Review</p>
			<?php foreach($project->tasks as $task) {
				if ( $task->status == 2 ) { ?>
					<article class="task" draggable="true" id="<?php echo $task->task_id; ?>">
						<header>
							<p class="lead"><?php echo $task->name; ?></p>
							<span class="due-on <?php echo $task->dueState; ?>"><?php echo $task->due_on; ?></span>
						</header>
						<p class="task-description"><?php echo $task->description; ?></p>
						<footer>
							<ul class="members">
								<?php foreach($task->members as $member) { ?>
									<li class="member-head" id="<?php echo $member->user_id; ?>">
										<img alt="<?php echo $member->initials; ?>" src="<?php echo $member->thumb; ?>">
									</li>
								<?php } ?>
							</ul>
							<span class="more-btn" id="more-btn-<?php echo $task->task_id; ?>">...</span>
						</footer>
					</article>
				<?php }
			}?>
		</div>
		<div class="col-md-3" id="complete-column" ondragover="allowDrop(event)" ondrop="drop(event)">
			<p class="lead">Complete</p>
			<?php foreach($project->tasks as $task) {
				if ( $task->status == 3 ) { ?>
					<article class="task" draggable="true" id="<?php echo $task->task_id; ?>">
						<header>
							<p class="lead"><?php echo $task->name; ?></p>
							<span class="due-on <?php echo $task->dueState; ?>"><?php echo $task->due_on; ?></span>
						</header>
						<p class="task-description"><?php echo $task->description; ?></p>
						<footer>
							<ul class="members">
								<?php foreach($task->members as $member) { ?>
									<li class="member-head" id="<?php echo $member->user_id; ?>">
										<img alt="<?php echo $member->initials; ?>" src="<?php echo $member->thumb; ?>">
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
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>