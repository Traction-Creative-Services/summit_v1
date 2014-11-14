<link rel="stylesheet" href="<?php echo base_url('assets/cover.css'); ?>">
<script>
  $(document).ready(function() {
    $("#arrow-one").css('top','-20px');
    $("#arrow-two").css('top','-40px');
  });
</script>
<div class="site-wrapper">

  <div class="site-wrapper-inner">

    <div class="cover-container">

      <div class="inner cover">
        <div class="cover-heading" id="logo-container">
          <img alt="logo" src="<?php echo base_url('assets/summit.png'); ?>" id="arrow-one">
          <img alt="logo" src="<?php echo base_url('assets/summit.png'); ?>" id="arrow-two">
          <img alt="logo" src="<?php echo base_url('assets/Traction_logo.png'); ?>" id="base">
        </div>
        <form method="post" action="<?php echo base_url('index.php/welcome/login'); ?>">
          <div class="input-group input-group-lg">
            <span class="input-group-addon">Username</span>
            <input type="text" class="form-control" name="uname">
          </div>
          <div class="input-group input-group-lg">
            <span class="input-group-addon">Password</span>
            <input type="password" class="form-control" name="pword">
          </div>
        <p class="lead">
          <input type="submit" class="btn btn-lg btn-default" value="Login"></input>
        </p>
      </div>

      <div class="mastfoot">
        <div class="inner">
          <p>A service of <a href="http://www.traction.media">Traction Creative Service</a></p>
        </div>
      </div>

    </div>

  </div>

</div>