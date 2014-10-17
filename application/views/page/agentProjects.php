<div class="container">
	<h1>Welcome Agent <?php echo $user['uname']; ?></h1>
	<div class="cust-row">
	<?php foreach($projects as $project) { ?>
		<a href="<?php echo site_url('/agent/projectDetail/'.$project->project_id); ?>">
		<div class="project" id="project-<?php echo $project->project_id; ?>">
			<p class="lead"><?php echo $project->name; ?></p>
		</div>
	</a>
	<?php } ?>
</div>
