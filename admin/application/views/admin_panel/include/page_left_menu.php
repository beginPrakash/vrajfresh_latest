<?php

// print_r($_SESSION);

/* CALL FUNCTION IN MENU HELPER */

$active_menu = getActiveMenuName();
$unread_report_count = getUnreadReportCount('about_order');
$unread_product_faci_count = getUnreadReportCount('product_repo');
$processing_order_count = getProcessingOrderCount();
$user_role_id = $this->session->userdata('login_view');

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





	<?php

	if ($user_role_id != 'Tirth' && $user_role_id != 'tirth') { ?>

		<li class="treeview <?php if ($active_menu == 'orders')

			echo 'active'; ?>">

			<a href="javascript:void(0);">

				<i class="fa fa-shopping-cart"></i> <span>Orders</span> <i class="fa fa-angle-left pull-right"></i>

			</a>

			<ul class="treeview-menu">

			<li><a href="<?php echo base_url(); ?>orders">My Orders <?php if($processing_order_count > 0){ ?> <span class="noti_count_or"> <?php echo $processing_order_count; ?></span> <?php } ?></a></li>

<li><a href="<?php echo base_url(); ?>historic-orders">My Historic Orders</a></li>

			</ul>

		</li>

	<?php } ?>



	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'Tirth' && $user_role_id != 'kaivan' && $user_role_id != 'tirth') { ?>

		<li class="treeview <?php if ($active_menu == 'customer')

			echo 'active'; ?>">

			<a href="javascript:void(0);">

				<i class="fa fa-user-plus"></i> <span>Customer</span> <i class="fa fa-angle-left pull-right"></i></a>

			<ul class="treeview-menu">

				<li><a href="<?php echo base_url(); ?>customers">My Customers</span></a></li>

			</ul>

		</li>

	<?php } ?>



	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'Tirth' && $user_role_id != 'kaivan' && $user_role_id != 'tirth') { ?>

		<li class="treeview <?php if ($active_menu == 'user')

			echo 'active'; ?>">

			<a href="javascript:void(0);">

				<i class="fa fa-user"></i> <span>User</span> <i class="fa fa-angle-left pull-right"></i>

			</a>

			<ul class="treeview-menu">

				<li><a href="<?php echo base_url(); ?>user-add">Create User</a></li>

				<li><a href="<?php echo base_url(); ?>user">View Users</a></li>

			</ul>

		</li>

	<?php } ?>



	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'Tirth' && $user_role_id != 'kaivan' && $user_role_id != 'tirth') { ?>

		<li class="treeview <?php if ($active_menu == 'cms')

			echo 'active'; ?>">

			<a href="javascript:void(0);">

				<i class="fa fa-file-o"></i> <span>CMS</span> <i class="fa fa-angle-left pull-right"></i>

			</a>

			<ul class="treeview-menu">

				<li><a href="<?php echo base_url(); ?>cms-add">Add Page</a></li>

				<li><a href="<?php echo base_url(); ?>cms">View Pages</a></li>

			</ul>

		</li>

	<?php } ?>



	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'Tirth' && $user_role_id != 'kaivan' && $user_role_id != 'tirth') { ?>

		<li class="treeview <?php if ($active_menu == 'category')

			echo 'active'; ?>">

			<a href="javascript:void(0);">

				<i class="fa fa-shopping-cart"></i> <span>Category</span> <i class="fa fa-angle-left pull-right"></i>

			</a>

			<ul class="treeview-menu">

				<li><a href="<?php echo base_url(); ?>category-add">Add Category</a></li>

				<li><a href="<?php echo base_url(); ?>category">View Categories</a></li>

			</ul>

		</li>

	<?php } ?>



	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'Tirth' && $user_role_id != 'kaivan' && $user_role_id != 'tirth') { ?>

		<li class="treeview <?php if ($active_menu == 'brand')

			echo 'active'; ?>">

			<a href="javascript:void(0);">

				<i class="fa fa-book"></i> <span>Brand</span> <i class="fa fa-angle-left pull-right"></i>

			</a>

			<ul class="treeview-menu">

				<li><a href="<?php echo base_url(); ?>brand-add">Add Brand</a></li>

				<li><a href="<?php echo base_url(); ?>brand">View Brands</a></li>

			</ul>

		</li>

	<?php } ?>



	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'kaivan') { ?>

		<li class="treeview <?php if ($active_menu == 'product')

			echo 'active'; ?>">

			<a href="javascript:void(0);">

				<i class="fa fa-shopping-cart"></i> <span>Product</span> <i class="fa fa-angle-left pull-right"></i>

			</a>

			<ul class="treeview-menu">

				<?php

				if ($user_role_id != 'Kaivan' && $user_role_id != 'kaivan') { ?>

					<li><a href="<?php echo base_url(); ?>import-product">Import Product</a></li>

				<?php } ?>

				<?php

				if ($user_role_id != 'Kaivan' && $user_role_id != 'kaivan') { ?>

					<li><a href="<?php echo base_url(); ?>product-add">Add Product</a></li>

				<?php } ?>

				<li><a href="<?php echo base_url(); ?>product">View Products</a></li>

				<li><a href="<?php echo base_url(); ?>product_variant">View Product Variants</a></li>

				<?php

				if ($user_role_id != 'Kaivan' && $user_role_id != 'kaivan') { ?>

					<li><a href="<?php echo base_url(); ?>tag-add">Add Product Tag</a></li>

				<?php } ?>

				<li><a href="<?php echo base_url(); ?>tag	">View Product Tags</a></li>

			</ul>

		</li>

	<?php } ?>



	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'Tirth' && $user_role_id != 'kaivan' && $user_role_id != 'tirth') { ?>

		<li class="treeview <?php if ($active_menu == 'setup')

			echo 'active'; ?>">

			<a href="#"> <i class="fa fa-cogs"></i> <span>Set Up</span><i class="fa fa-angle-left pull-right"></i></a>

			<ul class="treeview-menu">



				<li>

					<a href="javascript:void(0)"> <span>Menus</span> <i class="fa fa-angle-left pull-right"></i></a>

					<ul class="treeview-menu">

						<!--<li><a href="<?php echo base_url(); ?>menu-add">Add Menu</a></li>-->

						<li><a href="<?php echo base_url(); ?>menu">View Menus</a></li>

					</ul>

				</li>

				<li><a href="<?php echo base_url(); ?>zipcode-configuration">ZIP Code Configuration</a></li>

				<li><a href="<?php echo base_url(); ?>website-configuration">Website Configuration</a></li>

				<li><a href="<?php echo base_url(); ?>state-wise-tax">State Wise Tax</a></li>

				<li><a href="<?php echo base_url(); ?>cash-credits">Cash Credits</a></li>





			</ul>

		</li>

	<?php } ?>



	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'Tirth' && $user_role_id != 'kaivan' && $user_role_id != 'tirth') { ?>

		<li class="treeview <?php if ($active_menu == 'promotion')

			echo 'active'; ?>">

			<a href="javascript:void(0);"> <i class="fa fa-bullhorn"></i><span>Promotion</span> <i

					class="fa fa-angle-left pull-right"></i> </a>

			<ul class="treeview-menu">

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Promotional Code</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>promotional-code-add">Create Code</a> </li>

						<li><a href="<?php echo base_url(); ?>promotional-code">View Code</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Customer Group</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>clientgroup-step1">Create Group</a> </li>

						<li><a href="<?php echo base_url(); ?>clientgroup">View Groups</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Testimonial</span> <i class="fa fa-angle-left pull-right"></i> </a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>testimonial-add">Add Testimonial</a> </li>

						<li><a href="<?php echo base_url(); ?>testimonial">View Testimonials</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Banner</span> <i class="fa fa-angle-left pull-right"></i> </a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>banner-add">Add Banner</a> </li>

						<li><a href="<?php echo base_url(); ?>banner">View Banners</a> </li>

					</ul>

				</li>

			</ul>

		</li>

	<?php } ?>

	<!--<li class="treeview <?php if ($active_menu == 'blog')

		echo 'active'; ?>">

		<a href="javascript:void(0);">

		<i class="fa fa-user-plus"></i> <span>Blog</span> <i class="fa fa-angle-left pull-right"></i></a>

		<ul class="treeview-menu">

				<li><a href="<?php echo base_url(); ?>blog-add">Add Blog</a></li>

				<li><a href="<?php echo base_url(); ?>blog">View Blogs</a></li>

		</ul>

	</li>-->





	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'kaivan') { ?>

		<li class="treeview <?php if ($active_menu == 'inquires')

			echo 'active'; ?>">

			<a href="javascript:void(0);"><i class="fa fa-phone"></i> <span>Inquiries</span> <i

					class="fa fa-angle-left pull-right"></i></a>

			<ul class="treeview-menu">



				<li><a href="<?php echo base_url(); ?>contact-inquiry">Contact Us</a></li>

				<li><a href="<?php echo base_url(); ?>report-about-order">Report About Order <?php if($unread_report_count > 0){ ?> <span class="noti_count"> <?php echo $unread_report_count; ?></span> <?php } ?></a></li>

				<li><a href="<?php echo base_url(); ?>product-facility-request">Product Facility Requests<?php if($unread_product_faci_count > 0){ ?> <span class="noti_count"> <?php echo $unread_product_faci_count; ?></span> <?php } ?></a></li>



			</ul>

		</li>

	<?php } ?>

	<?php

	if ($user_role_id != 'Kaivan' && $user_role_id != 'kaivan') { ?>

		<li class="treeview <?php if ($active_menu == 'promotion')

			echo 'active'; ?>">

			<a href="javascript:void(0);"> <i class="fa fa-file-o"></i><span>Home Page</span> <i

					class="fa fa-angle-left pull-right"></i> </a>

			<ul class="treeview-menu">

				<li class="treeview">

					<a href="<?php echo base_url(); ?>banner-top"> <span>Banner Top Data</span>

					</a>
				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Home Banners</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>homebanners-add">Create Home Banner</a> </li>

						<li><a href="<?php echo base_url(); ?>homebanners">View Home Banner</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Feature Category</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>fcategories-add">Create Feature Category</a> </li>

						<li><a href="<?php echo base_url(); ?>fcategories">View Categories</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Stockup Your Frozen</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>stockup-add">Create Stockup Frozen</a> </li>

						<li><a href="<?php echo base_url(); ?>stockup">View Stockup Frozen</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Refill Your Pantry</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>pantry-add">Create Refill Pantry</a> </li>

						<li><a href="<?php echo base_url(); ?>pantry">View Refill Pantry</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Advertises</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>advertises-add">Create Advertise</a> </li>

						<li><a href="<?php echo base_url(); ?>advertises">View Advertise</a> </li>

					</ul>

				</li>
				<li class="treeview">

					<a href="javascript:void(0);"> <span>Home Product Slider</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>homep_slider-add">Create Home Product Slider</a> </li>

						<li><a href="<?php echo base_url(); ?>homep_slider">View Home Product Slider</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Home Product Slider Item</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>homep_slider_item-add">Create Home Product Slider Item</a> </li>

						<li><a href="<?php echo base_url(); ?>homep_slider_item">View Home Product Slider Item</a> </li>

					</ul>

				</li>

				<!-- <li class="treeview">

					<a href="javascript:void(0);"> <span>New Savings</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>new_savings-add">Create New Savings</a> </li>

						<li><a href="<?php echo base_url(); ?>new_savings">View New Savings</a> </li>

					</ul>

				</li>

				
				<li class="treeview">

					<a href="javascript:void(0);"> <span>Fresh Vegetables & Fruits</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>fresh_veg-add">Create Fresh Vegetables & Fruits</a> </li>

						<li><a href="<?php echo base_url(); ?>fresh_veg">View  Fresh Vegetables & Fruits</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Vraj Bakery</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>vraj_bakery-add">Create Vraj Bakery</a> </li>

						<li><a href="<?php echo base_url(); ?>vraj_bakery">View  Vraj Bakery</a> </li>

					</ul>

				</li>

				<li class="treeview">

					<a href="javascript:void(0);"> <span>Shop Ayurvedic</span> <i class="fa fa-angle-left pull-right"></i>

					</a>

					<ul class="treeview-menu">

						<li><a href="<?php echo base_url(); ?>shop_ayurvedic-add">Create Shop Ayurvedic</a> </li>

						<li><a href="<?php echo base_url(); ?>shop_ayurvedic">View  Shop Ayurvedic</a> </li>

					</ul>

				</li> -->

			</ul>

		</li>


		<li class="treeview <?php if ($active_menu == 'order_reports')

			echo 'active'; ?>">

			<a href="javascript:void(0);">

			<i class="fa fa-file-text-o"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>

			<ul class="treeview-menu">

				<li><a href="<?php echo base_url(); ?>order_reports">Order Reports</span></a></li>
				<li><a href="<?php echo base_url(); ?>cat_reports">Category Reports</span></a></li>
				<li><a href="<?php echo base_url(); ?>prod_reports">Product Reports</span></a></li>
				<li><a href="<?php echo base_url(); ?>brand_reports">Brand Reports</span></a></li>
				<li><a href="<?php echo base_url(); ?>custom_reports">Customer Reports</span></a></li>
				<li><a href="<?php echo base_url(); ?>tax_reports">Tax Amount Reports</span></a></li>

			</ul>

		</li>

	<?php } ?>



	<li><a href="<?php echo SITE_URL; ?>sign-out"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>

</ul>