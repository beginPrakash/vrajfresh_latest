<?php

// print_r($_SESSION);

/* CALL FUNCTION IN MENU HELPER */

$active_menu = getActiveMenuName();

?>
<style>
span.noti_count {
    border: 2px solid red;
    border-radius: 10px;
    font-size: 8px;
    margin-top: -4px !important;
    position: absolute;
    padding-left: 2px;
    padding-right: 2px;
    font-weight: bold;
    margin-left: -3px;
}
span.noti_count_or {
    border: 2px solid red;
    border-radius: 10px;
    font-size: 13px;
    margin-top: -4px !important;
    position: absolute;
    padding-left: 5px;
    padding-right: 5px;
    font-weight: bold;
    margin-left: 4px;
}
</style>
<ul class="sidebar-menu">



	<li class="treeview <?php if ($active_menu == 'dashboard')

		echo 'active'; ?>">

		<a href="javascript:void(0);">

			<i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>

		</a>

		<ul class="treeview-menu">

			<li><a href="<?php echo base_url(); ?>home">My Dashboard</a></li>

		</ul>

	</li>

	<li><a href="<?php echo SITE_URL; ?>sign-out"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>

</ul>