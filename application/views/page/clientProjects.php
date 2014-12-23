<div class="container">
	<div class="cust-row">
	<?php foreach($projects as $project) { ?>
		<a href="<?php echo site_url('/client/projectDetail/'.$project->project_id); ?>">
		<div class="project hex" id="project-<?php echo $project->project_id; ?>">
			<p class="lead"><?php echo $project->name; ?></p>
		</div>
	</a>
	<?php } ?>
</div>
