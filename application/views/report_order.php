<?php require_once('common/header.php'); ?>

<section class="categories-banner">
  <h2>Report Order</h2>
</section>
<section class="special-item contact vraj-title">
  <div class="container container-flex">

    <div class="contact-right">
      <div id="report_order_message"></div>
      <form id="report_form" method="POST" enctype="multipart/form-data">
        <label>Order Id:</label>
        <div id="order_no"></div>
        <!-- <input type="text" name="order_no" placeholder="Found in your order confirmation email">  -->
        <textarea name="message" cols="" rows="" placeholder="Message"></textarea>
        <label>Upload images<span>*</span></label>
        <input name="report_images" id="report_images" type="file" accept="image/png, image/jpeg,image/jpg"
          aria-label="Choose File">
        <input name="report_images1" id="report_images1" type="file" accept="image/png, image/jpeg,image/jpg"
          aria-label="Choose File">
        <input type="hidden" id="user_id" name="user_id"
          value="<?php echo $this->session->userdata['logged_in']['user_id']; ?>">
        <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
        <button onclick="submit_form()">Submit</button>
      </form>
    </div>
  </div>
</section>
<?php require_once('common/common_js.php'); ?>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
  $(document).ready(function () {
    showProgress('div#spinner');
    get_content(api_url_prefix);
    hideProgress('div#spinner');
  });
</script>
<script>

  function submit_form() {
    $("#report_form").validate({
      rules: {
        order_no: 'required',
        message: 'required',


      },
      messages: {

        order_no: 'This field is required',
        message: 'This field is required'
      },
      submitHandler: function (form) {
        // form.submit();
        var form = $("#report_form")[0];
        var formData = new FormData(form);
        $.ajax({
          "type": "POST",
          "url": api_url_prefix + 'report-order',
          "data": formData,
          "processData": false,
          "contentType": false,
          "success": function (response) {
            if (response.data != null) {
              $("#report_order_message").html(response.success_message);
              $("#report_order_message").css("color", "green");

            }
            else {
              $("#report_order_message").html('<span class="error">' + response.errors + '</span>');
              $("#report_order_message").css("color", "red");
            }


          },
          "error": function (response) {
            $("#message").html('<span class="error">' + response.errors + '</span>');

          }
        });
      },

    });

  }
  function get_content(api_url_prefix) {
    var json_request = {
      "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
      "user_id": $("#user_id").val()
    };

    $.ajax({
      "type": "POST",
      "url": api_url_prefix + 'get-order-by-user-id',
      "data": JSON.stringify(json_request),
      "dataType": "JSON",
      "success": function (response) {

        if (response.data != null) {
          var select = '<select name="order_no">';
          for (let a = 0; a < response.data.length; a++) {
            select += '<option value="' + response.data[a].order_id + '">' + response.data[a].order_id + '-' + response.data[a].order_status + '</option>';
          }
          select += '</select>';
        }
        else {
          select = '<select name="order_no"><option>No Order Found</option></select>';
        }
        $("#order_no").html(select);
      },
      "error": function (response) {
        $("#order_table").html("<tr>" + response.errors + "</tr>");
      }
    });
  }
</script>
<?php require_once('common/footer.php'); ?>