<?php

$active_menu = getActiveMenuName();

$site_title = "VrajFresh Admin";

$temp_site_name = "VF";

$temp_site_id = 0;

?>



<!DOCTYPE html>

<html>



<head>

   <meta charset="utf-8">

   <meta http-equiv="X-UA-Compatible" content="IE=edge">

   <title>

      <?php echo $site_title; ?>

   </title>

   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

   <link rel="shortcut icon" type="image/x-icon" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>/dist/img/favicon.png" />

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>bootstrap/css/bootstrap.min.css">

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/font-awesome.min.css">

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/ionicons.min.css">

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/AdminLTE.min.css">

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/skins/skin-blue.min.css">

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/inbox_page.css">

   <!-- SCRIPT -->

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>



   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>plugins/datatables/jquery.dataTables1.js"></script>

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>plugins/datatables/jquery.dataTables.css" />

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>plugins/datatables/percircle.css">



   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/styles.css" />

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/select2.min.css">

   <!-- SCRIPT END-->

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>sweetalert/sweetalert.css">

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>sweetalert/sweetalert.js"></script>

   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>sweetalert/sweetalert.css">

   <!-- DATE PICKER -->

   <link href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/datepicker.css" rel="stylesheet" type="text/css" />

   <link href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/datepicker.min.css" rel="stylesheet" type="text/css" />

   <script> var site_url = '<?php echo base_url(); ?>'; var base_url = '<?php echo base_url(); ?>'; </script>



   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

   <!-- DATE PICKER END -->



   <link rel="stylesheet" href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/tags/bootstrap-tagsinput.css">

</head>



<?php

$side_bar_menu_class = 'skin-blue sidebar-collapse sidebar-mini';



if (isset($_SESSION['side_bar_menu_status'])) {

   if ($_SESSION['side_bar_menu_status'] == 'open')

      $side_bar_menu_class = 'skin-blue sidebar-mini';

}

?>



<body class="<?php echo $side_bar_menu_class; ?>">

   <div class="wrapper">

      <!-- Main Header -->

      <header class="main-header">

         <a href="#" class="logo"><span class="logo-lg"><b>

                  <?php echo $temp_site_name; ?>

               </b></span></a>

         <!-- Header Navbar -->

         <nav class="navbar navbar-static-top" role="navigation">

            <a href="#" class="sidebar-toggle left_togle_btn_arrow" data-toggle="offcanvas" role="button"

               onClick="SaveSideBarClickEvent();">

               <span class="sr-only">Toggle navigation</span>

            </a>

            <?php if (isset($cms_title)) { ?>

               <i class="fa fa-angle-double-right bredcome_icon" aria-hidden="true"></i>

               <div class="cms_title responsive_ttl_s"

                  style="color: #fff;font-size: 16px;display: inline-block;margin: 13px 0 0 0;">

                  <?php echo $cms_title; ?>

               </div>

            <?php } ?>

            <!-- Navbar Right Menu -->

            <div class="navbar-custom-menu">

               <ul class="nav navbar-nav">



                  <li>

                     <!--<form class="navbar-form navbar-left" role="search" id="frmSearch" action="<?php echo base_url(); ?>adminpanel/controller_search/view_order_details" method="post" target="_blank">

                        <div class="form-group">

                           <input type="text" name="order_id" id="order_id" class="form-control" placeholder="Search order, customer, lead etc" title="Search order, customer, lead etc" value="<?php echo (isset($_GET['keyword']) && $_GET['keyword'] != "") ? base64_decode($_GET['keyword']) : ''; ?>">

                        </div>

                     </form>-->

                  </li>



                  <!-- Sidebar toggle button-->





                  <!-- User Account Menu -->

                  <li class="dropdown user user-menu">

                     <!-- Menu Toggle Button -->

                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <!-- The user image in the navbar-->

                        <img src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/img/avatar5.png" class="user-image"

                           alt="User Image">

                        <!-- hidden-xs hides the username on small devices so only the image appears. -->

                        <span class="hidden-xs">

                           <?php echo $this->session->userdata('display_name') . ' ' . $this->session->userdata('last_name'); ?>

                        </span>

                     </a>

                     <ul class="dropdown-menu">

                        <!-- The user image in the menu -->

                        <li class="user-header">

                           <img src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/img/avatar5.png" class="img-circle"

                              alt="User Image">

                           <p>

                              <?php echo $this->session->userdata('display_name') . ' ' . $this->session->userdata('last_name'); ?>

                           </p>

                        </li>

                        <!-- Menu Body -->

                        <li class="user-footer">

                           <div class="pull-left">

                              <a href="<?php echo SITE_URL; ?>my-profile" class="btn btn-default btn-flat">Profile</a>

                           </div>

                           <div class="pull-right">

                              <a href="<?php echo SITE_URL; ?>update-password" class="btn btn-default btn-flat">Change

                                 Password</a>

                           </div>

                        </li>

                     </ul>

                  </li>

               </ul>

            </div>

         </nav>

      </header>

      <!-- Left side column. contains the logo and sidebar -->

      <aside class="main-sidebar left_side_block_mobile">

         <!-- sidebar: style can be found in sidebar.less -->

         <section class="sidebar">

            <!-- Sidebar Menu -->

            <?php require_once('include/page_left_menu.php'); ?>

            <!-- /.sidebar-menu -->

         </section>

         <!-- /.sidebar -->

      </aside>

      <!-- Content Wrapper. Contains page content -->

      <div class="content-wrapper">

         <!-- Content Header (Page header) -->





         <section class="content-header top_padding_none_style">

            <div class="navbar-header">

               <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">

                  <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span

                     class="icon-bar"></span> <span class="icon-bar"></span> </button>

            </div>

            <div class="collapse navbar-collapse js-navbar-collapse" id="navbar-collapse">



               <?php // require_once('include/page_top_menu.php'); ?>

            </div>



            <div style="clear:both;"></div>



            <?php if (isset($button_url) && isset($button_label) && $button_url != '' && $button_label != '') { ?>

               <div class="breadcrumb">

                  <a href="<?php echo $button_url; ?>" class="btn btn-block btn-primary"><?php echo $button_label; ?></a>

               </div>

            <?php } ?>

         </section>

         <!-- Main content -->

         <div id="load">

            <div class="loading">

               <span class="circle1"></span>

               <span class="circle2"></span>

               <span class="circle3"></span>

               <span class="circle4"></span>

               <span class="circle5"></span>

               <b>PAGE IS LOADING</b>

            </div>

         </div>

         <section class="content">

            <!-- TO HIDE CONTENTS USE ID id="contents" CHECK onreadystatechange FUNCTION ON COMMON SCRIPT-->

            <?php include($view_name); ?><!--VIEW FILES -->

         </section>

      </div>

      <!-- Main Footer -->

      <footer class="main-footer">

         <div class="pull-right">

            <span class="mobile_all_fill"><b></span>

            <span class="mobile_all_fill"><b>Version</b> 1.0</span>

         </div>

         <span class="mobile_all_fill"><strong>Copyright &copy; <a target="_blank" href="#">VrajFresh</a>.</strong> All

            rights reserved.</span>



      </footer>

      <div class="control-sidebar-bg"></div>

   </div>





   <!-- details pop up start -->





   <div class="modal fade" id="recordDetailsPopUp" role="dialog" data-keyboard="false" data-backdrop="static">

      <div class="modal-dialog modal-lg">

         <!-- Modal content-->

         <div class="modal-content">

            <div class="modal-header padding_bts">

               <button type="button" class="close" data-dismiss="modal">&times;</button>

               <h4 class="modal-title" id="detailsPopUpTitle"></h4>

            </div>

            <div style="padding-top:0px;" class="modal-body text_line_height innner_tbls" id="detailsPopUpData">

               <div class="loading">

                  <span class="circle1"></span>

                  <span class="circle2"></span>

                  <span class="circle3"></span>

                  <span class="circle4"></span>

                  <span class="circle5"></span>

                  <b>LODING</b>

                  <p>Please give a MOMENT to COLLECT data</p>

               </div>

            </div>

         </div>

      </div>

   </div>



   <!-- details pop up end -->

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>bootstrap/js/bootstrap.min.js"></script>

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/app.min.js"></script>

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/jquery.validate.min.js"></script>

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/datepicker.min.js"></script>

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/highcharts.js"></script>

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/datepicker-configuration.js"></script>

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/plugins/select2.full.min.js"></script>

   <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/call_all_page.js"></script>

   <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>













   <?php

   /* MINIFY CSS & JS USING CI HELPER */

   echo $this->carabiner->display('css');

   echo $this->carabiner->display('js');

   /* COMMON JS SCRIPTS & HTML POP UP CODE */

   require_once('include/common_scripts.php');

   ?>

   <script>



      function SaveSideBarClickEvent() {

         $.ajax({

            type: "post",

            url: "<?php echo base_url(); ?>SaveSideBarClickEvent",

            method: "POST",

            cache: false

         });

      }

   </script>

</body>



</html>