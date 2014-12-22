<div class="container project-wrapper" id="<?php echo $project->id; ?>">
    <script src="<?php echo base_url('assets/chart.js'); ?>"></script>
    <div class="row">
        <div class="col-md-3">
            <p class="lead">Tasks Status</p>
            <canvas id="taskStatusChart" width="200" height="200" data-ready="<?php echo $project->readyCount; ?>" data-doing="<?php echo $project->doingCount; ?>" data-review="<?php echo $project->reviewCount; ?>" data-complete="<?php echo $project->completeCount; ?>"></canvas>
            <p class="lead">On Mark</p>
            <canvas id="onTimeStatusChart" width="200" height="200" data-ontime="<?php echo $project->ontimeCount; ?>" data-late="<?php echo $project->lateCount; ?>"></canvas>
        </div>
        <div class="col-md-3">
            <p class="lead">Meetings</p>
            <div class="meetings-container">
                <?php foreach($project->meetings as $meeting) { ?>
                    <article class="meeting client-facing" id="meeting-<?php echo $meeting->meeting_id; ?>">
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
        <div class="col-md-6">
            <p class="lead">Support Tickets <button class="add-btn" id="add-ticket-btn">+</button></p>
            <?php
            if(sizeof($project->tickets) > 0 ) {
                foreach($project->tickets as $ticket) {
                    ?>
                    <article class="ticket" id="ticket-<?php echo $ticket->ticket_id; ?>">
                        <header>
                            <p class="lead"><?php echo $ticket->subject; ?></p>
                            <span class="ticket-urgency" data-urgency="<?php echo $ticket->urgency; ?>"><span class="glyphicon glyphicon-fire"></span></span>
                        </header>
                        <p><?php echo $ticket->description; ?></p>
                        <footer>
                            <p class="messages"><span class="glyphicon glyphicon-comment"></span> <?php echo sizeof($ticket->messages); ?></p>
                        </footer>
                    </article>
                    <?php
                }
            } else {
                echo "<p>No active tickets to show! You're all set!</p>";
            }?>
        </div>
    </div>
</div>

<!-- MODALS -->
<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="ticketModalLabel">New Ticket</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="taskModalhiddenIdField" value=""/>
	<div class="form-group">
		<label for="ticketBodyField">Description</label>
		<textarea id="ticketBodyField" class="form-control"></textarea>	
	</div>
	<div class="form-group">
		<label for="ticketUrgencyField">How urgent is this?</label>
		<select class="form-control" id="ticketUrgencyField">
            <option value="0">Just checking in</option>
            <option value="1">I need some help</option>
            <option value="2">Something is broken</option>
            <option value="3">I can't get work done</option>
            <option value="4">The world is burning!</option>
            <option value="5">OH GOD WE'RE ALL GOING TO DIE!!</option>
        </select>
	</div>
	<p class="lead">Note:<br />
    <small>We make our best effort to respond to all inquiries in a timely manner. However, please understand that we handle many requests, and higher urgency tickets will get priority.</small></p>
	</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary save-btn" id="saveNewTicket">Send</button>
      </div>
    </div>
  </div>
</div>