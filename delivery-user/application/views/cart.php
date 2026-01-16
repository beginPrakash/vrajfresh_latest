<?php require_once('common/header.php'); ?>
<section class="categories-banner">
  <h2>Cart</h2>
</section>
<section class="billing-page cart-min">
  <div class="container container-flex">
    <div class="cart-page">
      <?php if (count($this->cart->contents()) > 0) { ?>
        <h3>REVIEW YOUR ORDERS</h3>
        <div>
          <table width="100%" cellpadding="0" cellspacing="5" align="center">
            <tr>
              <th scope="col">PRODUCT</th>
              <th scope="col">AMOUNT</th>
              <th scope="col">QTY</th>
              <th scope="col">TOTAL</th>
              <th scope="col">REMOVE</th>
            </tr>

            <?php $ArrProductIDs = array();
            if ($_COOKIE['can_deliver_perishable_products']  == 'Yes') {
              foreach ($this->cart->contents() as $items) {
                $ArrProductIDs[$items['id']] = $items["price"] * $items["qty"];
            ?> <tr class="cart-products">
                  <td>
                    <img src="<?php echo $items["image"]; ?>" alt="bakery">
                    <div class="cart_product"><a href="<?php echo 'product/' . $items["product_slug"]; ?>" target="_blank"><?php echo $items["name"] . " (" . $items["options"]['weight'] . "lb)"; ?></a></div>
                  </td>
                  <td>$<?php echo $items["price"]; ?> </td>
                  <td>
                    <div class="quantity">
                      <button type="button" id="sub" class="sub">-</button>
                      <input type="text" name="qty" value="<?php echo $items["qty"]; ?>" min="1" max="3" disabled class="qty" />
                      <button type="button" id="add" class="add">+</button>
                    </div>
                  </td>
                  <td>$<?php echo $items["price"] * $items["qty"]; ?> </td>

                  <td>
                    <button type="button" name="remove" class="remove_inventory error" id="<?php echo $items["rowid"]; ?>">X </button>
                  </td>
                  <td><input type="hidden" value="<?php echo $items["rowid"]; ?>" class="row_id"></td>
                </tr> <?php
                    } ?> <?php
                        } else {
                          foreach ($this->cart->contents() as $items) {
                            $ArrProductIDs[$items['id']] = $items["price"] * $items["qty"];
                            if ($items['is_perisible'] == '1')
                              $not_deliver_products[] = $items['name'];
                          ?> <tr class="cart-products">
                  <td>
                    <img src="<?php echo $items["image"]; ?>" alt="bakery">
                    <div class="cart_product"><a href="<?php echo 'product/' . $items["product_slug"]; ?>" target="_blank">
                        <?php echo $items["name"] . " (" . $items["options"]['weight'] . "lb)"; ?></a> </div>
                  </td>
                  <td>$<?php echo $items["price"]; ?> </td>
                  <td>
                    <div class="quantity">
                      <button type="button" id="sub" class="sub">-</button>
                      <input type="text" name="qty" value="<?php echo $items["qty"]; ?>" min="1" max="3" disabled class="qty" />
                      <button type="button" id="add" class="add">+</button>
                    </div>
                  </td>
                  <td>$<?php echo $items["price"] * $items["qty"]; ?> </td>

                  <td>
                    <button type="button" name="remove" class="remove_inventory error" id="<?php echo $items["rowid"]; ?>">X </button>
                  </td>
                  <td><input type="hidden" value="<?php echo $items["rowid"]; ?>" class="row_id"></td>
                </tr> <?php
                          }
                        }
                      } else { ?> <tr>
              <td colspan="4">Cart is Empty</td>
            </tr> <?php
                      } ?>
          </table>
        </div><?php if (count($this->cart->contents()) > 0) { ?> <table width="100%" cellpadding="0" cellspacing="5" align="center" class="couponcode">
            <tr>
              <td scope="col">
                <form>
                  <input name="" placeholder="Coupon Code" type="text" id="coupon_code">
                  <button onclick="return apply_coupon()">Apply Coupon</button>
                  <div id="error_msg"></div>
                  <div id="success_msg"></div>
                </form>
              </td>
              <td scope="col">
                <a href="#" class="vraj-btn update_inventory">Update Cart</a>
                <div id="success_update_msg"></div>
              </td>
            </tr>
          </table> <?php
                  } ?>
    </div> <?php if (count($this->cart->contents()) > 0) { ?> <div class="cart_totals">
        <h3>Cart Total</h3>
        <ul>
          <li class="cart-subtotal">
            <p>Subtotal</p>
            <span> <?php echo '$' . $this->cart->total(); ?> </span>
          </li>
          <li class="cart-subtotal" id="discount">
            <p>Discount</p>
            <span id="discount_amount">$0.00</span>
          </li>
          <!--<li class="cart-subtotal">
          <p>Delivery</p>
          <span>
            <b>Delivery Free</b>
          </span>
        </li>-->
          <?php $total = $this->cart->total(); ?>
          <li class="cart-subtotal">
            <p>Total</p>
            <span id="total">
              <b><?php echo '$' . $total; ?></b>
            </span>
          </li>
        </ul>
        <div class="zip-code_cart"> <img src=<?php echo ASSET_URL . "images/map-pin.png"; ?> class="left-arrow_cart">
          <input name="zipcode" placeholder="We deliver in your area" type="text" id="zipcode_cart" required maxlength="6" class="numberonly_cart">
          <button onclick="setCookie_cart();" class="header_button_cart"><img src=<?php echo ASSET_URL . "images/go.png"; ?>></button>
          <div id="zipcode_message1_cart" class="error_cart"><?php echo $_COOKIE['zipcode_error_message']; ?></div>
          <div class="success_cart" id="success_center_cart"><?php echo $_COOKIE['zipcode_success_message']; ?></div>
        </div>
        <?php if (isset($_COOKIE['zipcode'])) {
                if ($_COOKIE['valid_zipcode'] == 'TRUE') {

                  if ($_COOKIE['minimum_order_value'] < $this->cart->total()) {
                    if (is_array($not_deliver_products) && count($not_deliver_products) > 0) {
                      echo '<div class="cart_error"> Products ' . implode(",", $not_deliver_products) . ' are not delivered in your Area</div>';
                    } else {
                      // if (isset($_COOKIE['user_id']) && $_COOKIE['user_id'] != ''){
                      echo '<a href="' . BASE_URL . 'checkout" class="vraj-btn" id="vraj-btn">Proceed to checkout </a>';
                      
                      // } else {
                      // echo '<a hreft = "" class="vraj-btn" id="vraj-btn-login">Processed to checkout </a>';
                      // }
                    }
                  } else {
                    echo '<div class="cart_error"> A minimum $' . $_COOKIE['minimum_order_value'] . ' Order Required.</div>';
                  }
                } else {
                  echo '<div class="cart_error"></div>';
                }
              } else {
                echo '<div class="cart_error">Please Enter zipcode first</div>';
              }
        ?>

      </div> <?php
            } ?>
  </div>
</section>
<?php require_once('common/common_js.php'); ?>
<script>


  $(document).on('click', '#clear_cart', function() {
    
    if (confirm("Are you sure you want to clear cart?")) {
      $.ajax({
        url: " <?php echo BASE_URL; ?> cart-clear ",
        success: function(data) {
          alert("Your cart has been clear...");
          $('#cart_details').html(data);
          setTimeout(function() {
            location.reload();
          }, 1000);
        }
      });
    } else {
      return false;
    }
  });
  $(document).on('click', '.remove_inventory', function() {
    var row_id = $(this).attr("id");
    if (confirm("Are you sure you want to remove this?")) {
      $.ajax({
        url: "<?php echo BASE_URL; ?>item-remove ",
        method: "POST",
        data: {
          row_id: row_id
        },
        success: function(data) {

          $('#cart_details').html(data);
          alert("Product has been removed from Cart");
          setTimeout(function() {
            location.reload();
          }, 1000);
        }
      });
    } else {
      return false;
    }
  });
  $(document).on('click', '.update_inventory', function() {
    var row_id = [];
    var quantity = [];
    i = 0;

    $('.cart-products').each(function(index, tr) {
      $('td:nth-child(6)').each(function() {
        row_id.push($(this).find("input[type=hidden]").val());
      })
      $('td:nth-child(3)').each(function(index, tr) {
        quantity.push($(this).find(".qty").val());
      });
      return false;

    });
    // var row_id = $(this).attr("id");
    //var qty = $('#qty').val();
    $.ajax({
      url: " <?php echo BASE_URL; ?>item-update ",
      method: "POST",
      data: {
        row_id: row_id,
        qty: quantity
      },
      success: function(data) {
        $('.qty').html(data);
        $('#success_update_msg').html("<span class='success'>Cart has been updated successfully</span>");
        setTimeout(function() {
          location.reload();
        }, 1000);
      }
    });
  });

  function setCookie_cart() {
    var zipcode = $("#zipcode_cart").val();
    // alert(zipcode);
    if (zipcode != '') {
      Cookies.set('zipcode', zipcode);
      var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "zipcode": zipcode
      };

      $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-zipcode-detail',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
          if (response.data != null) {
            Cookies.set('minimum_order_value', '');
            Cookies.set('is_deliver_perishable_products', '');
            Cookies.set('delivery_type', '');
            Cookies.set('delivery_days', '');
            Cookies.set('zipcode_success_message', '');
            Cookies.set('zipcode_error_message', '');
            Cookies.set('valid_zipcode', '');

            Cookies.set('minimum_order_value', response.data[0].minimum_order_value);
            Cookies.set('is_deliver_perishable_products', response.data[0].can_deliver_perishable_products);
            Cookies.set('delivery_type', response.data[0].delivery_types);
            Cookies.set('delivery_days', response.data[0].delivery_days);
            Cookies.set('zipcode_success_message', 'Yes, We deliver in your area!');
            Cookies.set('zipcode_error_message', '');
            Cookies.set('valid_zipcode', 'TRUE');
            window.location = front_url + 'cart-detail';
            // $("#zipcode_message").html('Zip code saved successfully.');


          } else {
            Cookies.set('minimum_order_value', '');
            Cookies.set('is_deliver_perishable_products', '');
            Cookies.set('delivery_type', '');
            Cookies.set('delivery_days', '');
            Cookies.set('zipcode_success_message_cart', '');
            Cookies.set('zipcode_error_message', '');
            Cookies.set('valid_zipcode', '');

            $("#zipcode_message").html('Sorry We do not deliver in your area.');
            Cookies.set('zipcode_error_message', 'Sorry We do not deliver in your area.');
            Cookies.set('valid_zipcode', 'FALSE');
            window.location = front_url + 'cart-detail';
          }

        },
        "error": function(response) {
          $("#zipcode_message").html('Sorry We do not deliver in your area.');
          Cookies.set('valid_zipcode', 'FALSE');
          window.location = front_url + 'cart-detail';
        }
      });
    } else {
      $("#zipcode_message1_cart").html('Please enter zipcode');
      $("#success_center_cart").hide();
    }
  }
  // $("#success_center_cart").hide();
  $("#zipcode_cart").val(Cookies.get("zipcode"));
  $('.numberonly_cart').keypress(function(e) {
    var charCode = (e.which) ? e.which : event.keyCode;
    if (String.fromCharCode(charCode).match(/[^0-9]/g)) return false;
  });

  function apply_coupon() {
    // alert('test');
    $("#discount").show();
    var json_request = {
      "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
      "customer_id": "<?php echo $this->session->userdata['logged_in']['user_id']; ?>",
      "order_amount": "<?php echo $this->cart->total(); ?>",
      "product_ids": <?php echo json_encode($ArrProductIDs); ?>,
      "coupon_code": $("#coupon_code").val()
    };

    $.ajax({
      "type": "POST",
      "url": api_url_prefix + 'get-coupon-amount',
      "data": JSON.stringify(json_request),
      "dataType": "JSON",
      "success": function(response) {
        if (response.data != null) {
          $("#discount_amount").html('-$' + response.data.discount_amount);
          var order_amount_show = response.data.order_amount.replace(/,/g,'');
          if (order_amount_show > 0) {
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
          $("#error_msg").html("<span class='error'>" + response.errors + "</span>");
        }

      },
      "error": function(response) {
        console.log(response.errors);
      }
    });
    return false;
  }
</script> <?php require_once('common/footer.php'); ?>