<?php
/* CALL FUNCTION IN MENU HELPER */
$active_menu = getActiveMenuName();
?>
<ul class="sidebar-menu">

	<li class="treeview <?php if($active_menu=='dashboard') echo 'active'; ?>">
	<a href="javascript:void(0);">
	<i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo base_url(); ?>home">My Dashboard</a></li>
		</ul>
	</li>



	<li class="treeview <?php if($active_menu=='orders') echo 'active'; ?>">
		<a href="javascript:void(0);">
		<i class="fa fa-shopping-cart"></i> <span>Orders</span> <i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
				<li><a href="<?php echo base_url(); ?>orders">My Orders</a></li>
				<li><a href="<?php echo base_url(); ?>historic-orders">My Historic Orders</a></li>
		</ul> 
	</li>


	<li class="treeview <?php if($active_menu=='customer') echo 'active'; ?>">
		<a href="javascript:void(0);">
		<i class="fa fa-user-plus"></i> <span>Customer</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">
				<li><a href="<?php echo base_url(); ?>customers">My Customers</span></a></li>
		</ul>
	</li>


	<li class="treeview <?php if($active_menu=='user') echo 'active'; ?>">
		<a href="javascript:void(0);">
		<i class="fa fa-user"></i> <span>User</span> <i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
				<li><a href="<?php echo base_url(); ?>user-add">Create User</a></li>
				<li><a href="<?php echo base_url(); ?>user">View Users</a></li>
		</ul> 
	</li>
	<li class="treeview <?php if($active_menu=='cms') echo 'active'; ?>">
		<a href="javascript:void(0);">
		<i class="fa fa-file-o"></i> <span>CMS</span> <i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
				<li><a href="<?php echo base_url(); ?>cms-add">Add Page</a></li>
				<li><a href="<?php echo base_url(); ?>cms">View Pages</a></li>
		</ul> 
	</li>
	<li class="treeview <?php if($active_menu=='category') echo 'active'; ?>">
		<a href="javascript:void(0);">
		<i class="fa fa-shopping-cart"></i> <span>Category</span> <i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
				<li><a href="<?php echo base_url(); ?>category-add">Add Category</a></li>
				<li><a href="<?php echo base_url(); ?>category">View Categories</a></li>
		</ul> 
	</li>
	<li class="treeview <?php if($active_menu=='brand') echo 'active'; ?>">
		<a href="javascript:void(0);">
		<i class="fa fa-book"></i> <span>Brand</span> <i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
				<li><a href="<?php echo base_url(); ?>brand-add">Add Brand</a></li>
				<li><a href="<?php echo base_url(); ?>brand">View Brands</a></li>
		</ul> 
	</li>
	<li class="treeview <?php if($active_menu=='product') echo 'active'; ?>">
		<a href="javascript:void(0);">
		<i class="fa fa-shopping-cart"></i> <span>Product</span> <i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
				<li><a href="<?php echo base_url(); ?>import-product">Import Product</a></li>
				<li><a href="<?php echo base_url(); ?>product-add">Add Product</a></li>
				<li><a href="<?php echo base_url(); ?>product">View Products</a></li>
				<li><a href="<?php echo base_url(); ?>product_variant">View Product Variants</a></li>
				<li><a href="<?php echo base_url(); ?>tag-add">Add Product Tag</a></li>
				<li><a href="<?php echo base_url(); ?>tag	">View Product Tags</a></li>
		</ul> 
	</li>

	<li class="treeview <?php if($active_menu=='setup') echo 'active'; ?>">
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
		


	</ul>
	</li>
	
	<li class="treeview <?php if($active_menu=='promotion') echo 'active'; ?>">
	<a href="javascript:void(0);"> <i class="fa fa-bullhorn"></i><span>Promotion</span> <i class="fa fa-angle-left pull-right"></i> </a>
	<ul class="treeview-menu">
	<li class="treeview">
		<a href="javascript:void(0);"> <span>Promotional Code</span> <i class="fa fa-angle-left pull-right"></i> </a>
		<ul class="treeview-menu">
			<li><a href="<?php echo base_url(); ?>promotional-code-add">Create Code</a> </li>
			<li><a href="<?php echo base_url(); ?>promotional-code">View Code</a> </li>
		</ul>
	</li>
	<li class="treeview">
		<a href="javascript:void(0);"> <span>Customer Group</span> <i class="fa fa-angle-left pull-right"></i> </a>
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

	<!--<li class="treeview <?php if($active_menu=='blog') echo 'active'; ?>">
		<a href="javascript:void(0);">
		<i class="fa fa-user-plus"></i> <span>Blog</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">
				<li><a href="<?php echo base_url(); ?>blog-add">Add Blog</a></li>
				<li><a href="<?php echo base_url(); ?>blog">View Blogs</a></li>
		</ul>
	</li>-->



	<li class="treeview <?php if($active_menu=='inquires') echo 'active'; ?>">
	<a href="javascript:void(0);"><i class="fa fa-phone"></i> <span>Inquiries</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">

		<li><a href="<?php echo base_url(); ?>contact-inquiry">Contact Us</a></li>
		<li><a href="<?php echo base_url(); ?>report-about-order">Report About Order</a></li>
		<li><a href="<?php echo base_url(); ?>product-facility-request">Product Facility Requests</a></li>
	
	</ul>
	</li>

<li><a href="<?php echo SITE_URL; ?>sign-out"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
</ul>