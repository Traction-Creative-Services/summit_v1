<link rel="stylesheet" href="<?php echo base_url('assets/cover.css'); ?>">
<div class="site-wrapper">

  <div class="site-wrapper-inner">

    <div class="cover-container">

      <div class="masthead clearfix">
        <div class="inner">
          <h3 class="masthead-brand">Summit</h3>
        </div>
      </div>

      <div class="inner cover">
        <h1 class="cover-heading">Login</h1>
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