<script>
  sessionStorage.setItem("discount_amount", 0);
  sessionStorage.setItem("coupon_code", "");
  sessionStorage.setItem("coupon_id",0);

  $("#zipcode_cart").val(Cookies.get("zipcode"));
  $("#coupon_code").val(sessionStorage.getItem("coupon_code"));
  
  $('.numberonly_cart').keypress(function (e) {
    var charCode = (e.which) ? e.which : event.keyCode;
    if (String.fromCharCode(charCode).match(/[^0-9]/g)) return false;
  });
  
  $(document).on('click', '#clear_cart', function () {
    if (confirm("Are you sure you want to clear cart?")) {
      $.ajax({
        url: " <?php echo BASE_URL; ?> cart-clear ",
        success: function (data) {
          alert("Your cart has been clear...");
          $('#cart_details').html(data);
          setTimeout(function () {
            location.reload();
          }, 1000);
        }
      });
    } else {
      return false;
    }
  });

$(document).on('click', '#clear_cart_data', function () {
  if (confirm("Are you sure you want to clear cart?")) {
    $.ajax({
      url: "<?php echo BASE_URL; ?>cart-clear-data",
      success: function (data) {
        alert("Your cart has been clear...");
        $('#cart-data').empty();
        $('#cart-data').html(data.html);
        var qtySum = getQtyValues();
        $("#cartCount").text(qtySum);
        $("#success_msg").html("<span class='success'>Product has been removed from Cart</span>");
        /* setTimeout(function () {
          location.reload();
        }, 1000); */
      }
    });
  } else {
    return false;
  }
});

  $(document).on('click', '.remove_inventory_old', function () {
    var row_id = $(this).attr("id");
    if (confirm("Are you sure you want to remove this?")) {
      $.ajax({
        url: "<?php echo BASE_URL; ?>item-remove ",
        method: "POST",
        data: {
          row_id: row_id
        },
        success: function (data) {
          //$('#cart_details').html(data);
          $('#cart-data').empty();
          $('#cart-data').html(data.html);
          var qtySum = getQtyValues();
          $("#cartCount").text(qtySum);
          $("#success_msg").html("<span class='success'>Product has been removed from Cart</span>");
        }
      });
    } else {
        return false;
    }
  });

  $(document).on('click', '.remove_inventory', function () {
    var row_id = $(this).attr("id");
    var new_row_id = $(this).attr("data-newid");
    
    if (confirm("Are you sure you want to remove this?")) {
      $.ajax({
        url: "<?php echo BASE_URL; ?>item-new-remove",
        method: "POST",
        data: {
          row_id: row_id,
          new_row_id: new_row_id
        },
        success: function (data) {
          //$('#cart_details').html(data);
          $('#cart-data').empty();
          $('#cart-data').html(data.html);
          var qtySum = getQtyValues();
          $("#cartCount").text(qtySum);
			    $("#success_msg").html("<span class='success'>Product has been removed from Cart</span>");
        }
      });
    } else {
      return false;
    }
  });

  function getQtyValues() {
    var qtySum = 0;
    // Select all inputs with name="qty" and iterate over them
    $('input[name="qty"]').each(function() {
        // Push the value of each input to the qtyValues array
        qtySum += parseFloat($(this).val()) || 0;
    });    
    return qtySum;
  }

  $(document).on('click', '.add_quantity', function() {
      if ($(this).prev().val() < 50) {
          $(this).prev().val(+$(this).prev().val() + 1);
          update_quantity();
      }
  });
  $(document).on('click', '.sub_quantity', function() {
      if ($(this).next().val() > 1) {
          if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
          update_quantity();
      }
      
  });
  function update_quantity_old()
  {

    var row_id = [];
    var quantity = [];
    i = 0;

    $('.cart-products').each(function (index, tr) {
      $('td:nth-child(6)').each(function () {
        row_id.push($(this).find("input[type=hidden]").val());
      })
      $('td:nth-child(3)').each(function (index, tr) {
        quantity.push($(this).find(".qty").val());
      });
      return false;

    });
	
    $.ajax({
      url: " <?php echo BASE_URL; ?>item-update ",
      method: "POST",
      data: {
        row_id: row_id,
        qty: quantity
      },
      success: function (data) {
        //console.log(data, "Response");
        $('.qty').html(data);
        $('#cart-data').empty();
        $('#cart-data').html(data.html);
        var qtySum = getQtyValues();
        $("#cartCount").text(qtySum);
        $('#success_update_msg').html("<span class='success'>Cart has been updated successfully</span>");
      }
    });

  }

  function update_quantity()
  {

    var row_id = [];
    var quantity = [];
    var new_row_id = [];
    i = 0;

    $('.cart-products').each(function (index, tr) {
      $('td:nth-child(6)').each(function () {
        row_id.push($(this).find("input[type=hidden]").val());
      })
      $('td:nth-child(3)').each(function (index, tr) {
        quantity.push($(this).find(".qty").val());
      });
      $('td:nth-child(7)').each(function () {
        new_row_id.push($(this).find("input[type=hidden]").val());
      })
      return false;

    });
	
    $.ajax({
      url: " <?php echo BASE_URL; ?>item-new-update ",
      method: "POST",
      data: {
        row_id: row_id,
        qty: quantity,
        new_row_id: new_row_id
      },
      success: function (data) {
        //console.log(data, "Response");
        $('.qty').html(data);
        $('#cart-data').empty();
        $('#cart-data').html(data.html);
        var qtySum = getQtyValues();
        $("#cartCount").text(qtySum);
        $('#success_update_msg').html("<span class='success'>Cart has been updated successfully</span>");
      }
    });

  }

  $(document).on('click', '.update_inventory', function () {
    var row_id = [];
    var quantity = [];
    i = 0;

    $('.cart-products').each(function (index, tr) {
      $('td:nth-child(6)').each(function () {
        row_id.push($(this).find("input[type=hidden]").val());
      })
      $('td:nth-child(3)').each(function (index, tr) {
        quantity.push($(this).find(".qty").val());
      });
      return false;

    });
	
    $.ajax({
      url: " <?php echo BASE_URL; ?>item-update",
      method: "POST",
      data: {
        row_id: row_id,
        qty: quantity
      },
      success: function (data) {
        console.log(data, "Response");
        $('.qty').html(data);

        var qtySum = getQtyValues();
        $("#cartCount").text(qtySum);
        $('#cart-data').empty();
        $('#cart-data').html(data.html);
        $('#success_update_msg').html("<span class='success'>Cart has been updated successfully</span>");
        

        /* $('#success_update_msg').html("<span class='success'>Cart has been updated successfully</span>");
        setTimeout(function () {
          location.reload();
        }, 1000); */
        /* $.ajax({
          url: "<?php echo BASE_URL; ?>render-cart-detail",
          method: "GET",
          success: function (data) {
            var qtySum = getQtyValues();
            $("#cartCount").text(qtySum);
            $('#cart-data').empty();
            $('#cart-data').html(data.html);
            $('#success_update_msg').html("<span class='success'>Cart has been updated successfully</span>");
          }
        }); */
      }
    });
  });

  

  function apply_coupon() {
    $("#discount").show();
    var json_request = {
      "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
      "customer_id": "<?php echo $this->session->userdata["logged_in"][
          "user_id"
      ]; ?>",
      "order_amount": "<?php echo $this->cart->total(); ?>",
      "product_ids": <?php echo json_encode($ArrProductIDs); ?>,
	  "coupon_code": $("#coupon_code").val()
  };

  $.ajax({
    "type": "POST",
    "url": api_url_prefix + 'get-coupon-amount',
    "data": JSON.stringify(json_request),
    "dataType": "JSON",
    "success": function (response) {
      if (response.data != null) {
        $("#discount_amount").html('-$' + response.data.discount_amount);
        if (response.data.order_amount > 0) {
          $("#total b").html('$' + response.data.order_amount);
        } else {
          $("#total b").html('$0.00');
        }
        sessionStorage.setItem("discount_amount", response.data.discount_amount);
        sessionStorage.setItem("order_amount", response.data.order_amount);
        sessionStorage.setItem("coupon_code", $("#coupon_code").val());
        sessionStorage.setItem("coupon_id", response.data.coupon_id);
        $("#error_msg").html("");
        $("#success_msg").html("<span class='success'>" + response.success_message + "</span>");

      } else {
        $("#success_msg").html("");
        sessionStorage.setItem("discount_amount", 0);
		$("#discount_amount").html('$0.00');
        $("#error_msg").html("<span class='error'>" + response.errors + "</span>");
      }

    },
    "error": function (response) {
      console.log(response.errors);
    }
  });
  return false;
  }
</script>