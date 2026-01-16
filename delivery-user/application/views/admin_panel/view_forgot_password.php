<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Rent-Guam | Forgot Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Rent</b>-Guam</a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Forgot Password</p>
      <form role="form" method="post" action="<?php echo SITE_URL ?>forgot-password" name="forgotPassword">
        <div class="form-group has-feedback">
          <input type="email" id="email" name="email" class="form-control" placeholder="Enter Your Email">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          <?php echo form_error('email', '<div class="error">', '</div>'); ?>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <input type="submit" class="btn btn-primary btn-block btn-flat" name="forgot_password" id="forgot_password"
              value="Submit">

          </div><!-- /.col -->
        </div>
      </form>




      <a href="admin-login">Login</a><br>
      <?php if ($this->session->flashdata('error_message')) { ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $this->session->flashdata('error_message'); ?>
        </div>
      <?php } ?>
      <?php if ($this->session->flashdata('success_message')) { ?>
        <div class="alert alert-success" role="alert">
          <?php echo $this->session->flashdata('success_message'); ?>
        </div>
      <?php } ?>

    </div><!-- /.login-box-body -->

  </div><!-- /.login-box -->

  <!-- jQuery 2.1.4 -->
  <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
  <!-- Bootstrap 3.3.5 -->
  <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>bootstrap/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>plugins/iCheck/icheck.min.js"></script>
  <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/jquery.validate.min.js"></script>
  <?php
  /* MINIFY CSS & JS USING CI HELPER */
  echo $this->carabiner->display('css');
  echo $this->carabiner->display('js');
  /* COMMON JS SCRIPTS & HTML POP UP CODE */
  require_once('common_scripts.php');
  ?>
</body>

</html>