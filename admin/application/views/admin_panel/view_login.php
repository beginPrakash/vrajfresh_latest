<!DOCTYPE HTML>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin</title>
  <link rel="shortcut icon" type="<?php echo ADMIN_PANEL_THEME_PATH; ?>login/image/png" href="img/favicon.png">
  <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>login/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>login/css/style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>login/css/font-awesome.css" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
</head>

<body class="login_bg">

  <div class="login_page">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="login_page_detail">
            <div class="logo_img"><img src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/img/logo-2.png" /></div>
            <h3>Login as a Admin User</h3>
            <?php if ($this->session->flashdata('authorized_error') != "") { ?>
              <div class="alert alert-danger" role="alert" style="">
                <?php echo $this->session->flashdata('authorized_error'); ?>
              </div>
            <?php } ?>
            <?php if (isset($error)) { ?>
              <div class="alert alert-danger" role="alert" style="">
                <?php echo $error; ?>
              </div>
            <?php } ?>
            <form action="<?php echo base_url() ?>login-process" method="post">
              <div class="form-group user_icon">
                <input type="text" class="form-control" name="login_view" id="login_view"
                  value="<?php if (isset($_COOKIE["username"])) {
                    echo $_COOKIE["username"];
                  } ?>"
                  placeholder="Username">
              </div>
              <div class="form-group pwd_icon">
                <input type="password" class="form-control" name="password" id="password"
                  value="<?php if (isset($_COOKIE["password"])) {
                    echo base64_decode($_COOKIE["password"]);
                  } ?>"
                  placeholder="Password">
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <button type="submit" class="form-control btn btn-primary" name="submit">Sign In</button>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>login/js/jquery.min.js"></script>
  <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>login/js/bootstrap.min.js"></script>
</body>

</html>