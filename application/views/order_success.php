<?php require_once('common/header.php'); ?>
<section class="order-receiver">
	<div class="container">
		<h3>Your order has been received</h3>
		<i class="fa fa-check" aria-hidden="true"></i>
		<strong>Thank you for your purchase!</strong>
		<span>Your Order ID is : <b>
				<?php echo $order_id; ?>
			</b>
		</span>
		<p>you will receive an order confirmation email with details of your order</p>
		<a href="
		<?php echo BASE_URL; ?>">Continue Shopping </a>
	</div>
</section>
<?php require_once('common/common_js.php'); ?>
<?php require_once('common/footer.php'); ?>
<script>
sessionStorage.setItem("discount_amount", 0);
sessionStorage.setItem("order_amount", 0);
sessionStorage.setItem("coupon_code", "");
sessionStorage.setItem("coupon_id",0);
</script>