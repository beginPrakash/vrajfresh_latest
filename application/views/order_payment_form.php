<?php require_once('common/header.php'); ?>
<section class="order-receiver">
  <div class="container">
    <h3>Please enter payment details</h3>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <!-- jQuery is used only for this example; it isn't required to use Stripe -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" />
    <!-- Stripe JavaScript library -->
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

   
<?php require_once('scripts/payment_js.php'); ?>

    <form method="post" id="paymentFrm" enctype="multipart/form-data" action="<?php echo BASE_URL; ?>payment-process">
      <div style="color:red" id="payment-errors"></div>
      <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
      <div class="form-group">
        <input type="hidden" name="name" value="<?php echo set_value('name'); ?>" required>
        <input type="hidden" name="zip_code" value="<?php echo set_value('zip_code'); ?>" required>
        <input type="hidden" name="sms_shipping_phone" value="<?php echo $sms_shipping_phone; ?>" required>
      </div>
      <div class="form-group">
        <input type="hidden" name="email" class="form-control" placeholder="email@you.com"
          value="<?php echo set_value('email'); ?>" required>
      </div>
      <div class="form-group">
        <label>Order Amount:
          <?php echo '$' . $order_amount; ?>
        </label>
        <input type="hidden" name="order_amount" value="<?php echo $order_amount * 100; ?>">
      </div>
      <div class="form-group">
        <input type="text" name="card_num" id="card_num" class="form-control" placeholder="Card Number"
          autocomplete="off" value="" maxlength="19" required on>
      </div>
      <div class="row">
        <div class="col-sm-8">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <input type="text" name="exp_month" maxlength="2" class="form-control" id="card-expiry-month"
                  placeholder="MM" value="" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <input type="text" name="exp_year" class="form-control" maxlength="4" id="card-expiry-year"
                  placeholder="YYYY" required="" value="">
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <input type="text" name="cvc" id="card-cvc" maxlength="4" class="form-control" autocomplete="off"
              placeholder="CVC" value="" required>
          </div>
        </div>
      </div>
      <div class="form-group text-right">
        <button class="btn btn-secondary" type="reset">Reset</button>
        <button type="submit" id="payBtn" class="btn btn-success">Submit Payment</button>
      </div>
    </form>

</section>
 <input type="hidden" name="user_id" id="user_id" value="<?php echo $this->session->userdata['logged_in']['user_id']; ?>">

<?php require_once('common/footer.php'); ?>