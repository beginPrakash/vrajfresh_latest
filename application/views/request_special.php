<?php require_once('common/header.php'); ?>
<script type="text/javascript">
  function MM_jumpMenu(targ, selObj, restore) { //v3.0
    eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
    if (restore) selObj.selectedIndex = 0;
  }
</script>
<section class="categories-banner">
  <h2>Special Request</h2>
</section>
<section class="special-item contact vraj-title">
  <div class="container container-flex">
    <div class="contact-right">
      <div id="request_special_message"></div>
      <form id="report_form" method="POST">
        <!-- <input type="text" name="user_name" placeholder="Your Name*" value="<?php //echo $this->session->userdata['logged_in']['first_name'];?>"> -->
        <input type="text" name="product_name" placeholder="Product Name*">
        <textarea name="message" cols="" rows="" placeholder="Message"></textarea>
        <input type="hidden" name="user_id" value="<?php echo $this->session->userdata['logged_in']['user_id']; ?>">
        <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
        <button onclick="submit_form()">Submit</button>
      </form>
    </div>
  </div>
</section>
<?php require_once('common/common_js.php'); ?>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>

  function submit_form() {
    $("#report_form").validate({
      rules: {
        product_name: 'required',
        message: 'required',


      },
      messages: {
        product_name: 'This field is required',
        message: 'This field is required'
      },
      submitHandler: function (form) {
        // form.submit();
        var form = $("#report_form");

        $.ajax({
          "type": "POST",
          "url": api_url_prefix + 'requested-product',
          "data": form.serialize(),
          "dataType": "JSON",
          "success": function (response) {
            $("#request_special_message").html(response.success_message); $("#request_special_message").css("color", "green");

          },
          "error": function (response) {
            $("#request_special_message").html(response.errors); $("#request_special_message").css("color", "red");

          }
        });
      },

    });

  }
</script>
<?php require_once('common/footer.php'); ?>