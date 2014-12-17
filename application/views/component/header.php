<!DOCTYPE html>
<html lang="en" class="<?php echo $page; ?>">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $title; ?> | SUMMIT</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url('/assets/style.css'); ?>">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	</head>
  <body>
	<nav class="navbar navbar-fixed-top navbar-inverse">
		
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		    <span class="sr-only">Toggle navigation</span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="#"><img alt="SUMMIT" src="<?php echo base_url('assets/summit-icon.png'); ?>" /> <?php if(isset($project)) {  echo $project->name; } ?></a>
		</div>
	    
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<div class="contrainer-fluid">
			<?php if(isset($project)) { ?>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Team <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<div id="agent-heads-container">
							<ul class="members">
								<?php foreach($project->agents as $member) { ?>
									<li class="member-head" id="<?php echo $member->user_id; ?>">
										<img alt="<?php echo $member->initials; ?>" src="<?php echo base_url('assets/uploads/'.$member->thumb); ?>">
									</li>
								<?php } ?>
							</ul>
							<span cla
						</div>
					</ul>
				</li>
				<li class="dropdown">
					<a class="dropdwon-toggle" data-toggle="dropdown" href="#">Client <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<div id="client-container">
							<p class="lead"><?php echo $project->client->first.' '.$project->client->last; ?><br />
							<small><?php if(isset($project->client->company->name)) echo $project->client->company->name; ?></small></p>
							<p><small>ph</small> <?php echo $project->client->phone; ?><br />
							<small>em</small> <?php echo $project->client->email; ?></p>
						</div>
					</ul>
				</li>
				<li class="active"><a>Starts on: <?php echo date('M-d-y',strtotime($project->start)); ?></a></li>
				<li class="active"><a>Launch on: <?php echo date('M-d-y',strtotime($project->end)); ?>&nbsp;&nbsp;&nbsp;</a></li>
			</ul>
			<?php } else { ?>
			<ul class="nav navbar-nav navbar-right">
				<li><a>Welcome <?php echo $user['uname']; ?></a></li>
			</ul>
			<?php } ?>
		</div>
		
	</nav>