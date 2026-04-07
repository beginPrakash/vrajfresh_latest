<?php  require_once('common/header.php');  ?>

<section class="categories-banner cms_header">
  <h1>Your Shopping Cart</h1>
</section>
<section class="billing-page cart-min">
    <?php if ($this->session->flashdata('success')){ ?>
        <div class="container">
           <div class=" alert alert-success">
            <p><?php echo $this->session->flashdata('success'); ?></p>
           </div>
        </div>
   <?php } ?>
   <?php if ($this->session->flashdata('error')){ ?>
       <div class="container">
           <div class="alert alert-danger">
              <p><?php echo $this->session->flashdata('error'); ?></p>
           </div>
        </div>
   <?php } ?>
  <div class="container container-flex" id="cart-data">
    <div class="cart-page borderRemove">
      <h3>REVIEW YOUR ORDERS</h3>
      <?php //print_r($this->cart->contents());exit; ?>
        <div>
          <table class="review-table" width="100%" cellpadding="0" cellspacing="5" align="center">
            <?php if (count($this->cart->contents()) > 0) { ?>
        
              <tr>
                <th scope="col">PRODUCT</th>
                <th scope="col">AMOUNT</th>
                <th scope="col">QTY</th>
                <th scope="col">TOTAL</th>
                <th scope="col">REMOVE</th>
              </tr>

              <?php $ArrProductIDs = array();
              if ($_COOKIE['can_deliver_perishable_products'] == 'Yes') {
               
                foreach ($this->cart->contents() as $items) {
                  $ArrProductIDs[$items['id']] = $items["price"] * $items["qty"];
                  ?>
                  <tr class="cart-products">
                    <td>
                      <img src="<?php echo $items["image"]; ?>" alt="bakery">
                      <div class="cart_product"><a href="<?php echo 'product/' . $items["product_slug"]; ?>"
                          target="_blank"><?php echo $items["name"] . " (" . $items["options"]['weight'] . "lb)"; ?></a></div>
                    </td>
                    <td>$
                      <?php echo $items["price"]; ?>
                    </td>
                    <td>
                      <div class="quantity">
                        <button type="button" id="sub" class="sub_quantity">-</button>
                        <input type="text" name="qty" value="<?php echo $items["qty"]; ?>" min="1" max="3" disabled
                          class="qty" />
                        <button type="button" id="add" class="add_quantity">+</button>
                      </div>
                    </td>
                    <td>$
                      <?php echo $items["price"] * $items["qty"]; ?>
                    </td>

                    <td>
                      <button type="button" name="remove" class="remove_inventory error" data-newid="<?php echo $items["id"]; ?>" id="<?php echo $items["rowid"]; ?>">X
                      </button>
                    </td>
                    <td><input type="hidden" value="<?php echo $items["rowid"]; ?>" class="row_id"></td>
                    <td><input type="hidden" value="<?php echo $items["id"]; ?>" class="new_row_id"></td>
                  </tr>
                  <?php
                } ?>
              <?php
              } else {
                foreach ($this->cart->contents() as $items) {
                  $ArrProductIDs[$items['id']] = $items["price"] * $items["qty"];
                  if ($items['is_perisible'] == '1')
                    $not_deliver_products[] = $items['name'];
                  ?>
                  <tr class="cart-products">
                    <td>
                      <img src="<?php echo $items["image"]; ?>" alt="bakery">
                      <div class="cart_product"><a href="<?php echo 'product/' . $items["product_slug"]; ?>" target="_blank">
                          <?php echo $items["name"] . " (" . $items["options"]['weight'] . "lb)"; ?></a>
                        <div class="mobile-view-show">
                          <div class="mobile-price">$ <?php echo $items["price"] * $items["qty"]; ?></div>
                          <button type="button" name="remove" class="remove_inventory error" id="<?php echo $items["rowid"]; ?>">X</button>
                        </div>
                       </div>
                    </td>
                    <td class="mobieNone">$
                      <?php echo $items["price"]; ?>
                    </td>
                    <td class="quaRig">
                      <div class="quantity">
                        <button type="button" id="sub" class="sub_quantity">-</button>
                        <input type="text" name="qty" value="<?php echo $items["qty"]; ?>" min="1" max="3" disabled
                          class="qty" />
                        <button type="button" id="add" class="add_quantity">+</button>
                      </div>
                    </td>
                    <td class="mobieNone">$
                      <?php echo $items["price"] * $items["qty"]; ?>
                    </td>

                    <td class="mobieNone">
                      <button type="button" name="remove" class="remove_inventory error" data-newid="<?php echo $items["id"]; ?>" id="<?php echo $items["rowid"]; ?>">X
                      </button>
                    </td >
                    <td class="mobieNone"><input type="hidden" value="<?php echo $items["rowid"]; ?>" class="row_id"></td>
                    <td class="mobieNone"><input type="hidden" value="<?php echo $items["id"]; ?>" class="new_row_id"></td>
                  </tr>
                  <?php
                }
              }
            } else { ?>
                  <tr>
                    <td colspan="4">Cart is Empty</td>
                  </tr>
                  <?php
            } ?>
          </table>
        </div>
        <?php if (count($this->cart->contents()) > 0) { ?>
          <button class="clr-cart" id="clear_cart_data">Clear Cart</button>
        <?php } ?>
    </div>
    <?php if (count($this->cart->contents()) > 0) { ?>
    
      <div class="cart_totals borderRemove">
        <h3>Cart Total</h3>
        <ul>
          <li class="cart-subtotal">
            <p>Subtotal</p>
            <span>
              <?php echo '$' . $this->cart->total(); ?>
            </span>
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
              <b>
                <?php echo '$' . $total; ?>
              </b>
            </span>
          </li>
        </ul>
        <div class="zip-code_cart"> <img src=<?php echo ASSET_URL . "images/map-pin.png"; ?> class="left-arrow_cart">
          <input name="zipcode" placeholder="We deliver in your area" type="text" id="zipcode_cart" required maxlength="6"
            class="numberonly_cart">
          <button onclick="setZipCodeCookie('cart');" class="header_button_cart"><img src=<?php echo ASSET_URL . "images/go.png"; ?>></button>
          <div id="zipcode_message1_cart" class="error_cart">
            <?php echo $_COOKIE['zipcode_error_message']; ?>
          </div>
          <div class="success_cart" id="success_center_cart">
            <?php echo $_COOKIE['zipcode_success_message']; ?>
          </div>
        </div>
        <?php if (count($this->cart->contents()) > 0) { ?>
          <table width="100%" cellpadding="0" cellspacing="5" align="center" class="couponcode">
            <tr>
              <td scope="col">
                <form>
                    <input name="coupon_code" placeholder="Coupon Code" type="text" id="coupon_code" value="">
                    <button onclick="return apply_coupon()">Apply Coupon </button>
                    <div id="error_msg"></div>
                    <div id="success_msg"></div>
                  </form>
              </td>
              <td scope="col">
                <!-- <a href="#" class="vraj-btn update_inventory">Update Cart</a> -->
                <div id="success_update_msg"></div>
              </td>
            </tr>
          </table>
          <?php
        } ?>
        <?php 
        unset($_SESSION['redirect_after_login']);
        if (isset($_COOKIE['zipcode'])) {
          
          if ($_COOKIE['valid_zipcode'] == 'TRUE') {
            
            if ($_COOKIE['minimum_order_value'] < $this->cart->total()) {
              if (!IsUserLogin()) { 
                // Save requested page
                $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
                ?><div class="stcky">
                <p>Total - <span><?php echo '$' . $this->cart->total(); ?></span></p>
                <?php if(isset($_COOKIE['user_id'])){ ?>
                  <?php
                  echo '<a href="' . BASE_URL . 'checkout" class="vraj-btn" id="vraj-btn">Proceed to checkout </a>';
                  ?>
                <?php } else { ?>
                  <?php
                  echo '<a href="' . BASE_URL . 'login" class="vraj-btn" id="vraj-btn">Proceed to checkout </a>';
                  ?>
              <?Php } ?></div><?php
              } else {
                  ?><div class="stcky">
                <p>Total - <span><?php echo '$' . $this->cart->total(); ?></span></p>
                <?php
                echo '<a href="' . BASE_URL . 'checkout" class="vraj-btn" id="vraj-btn">Proceed to checkout </a>';
                ?></div><?php
              }
            } else {
              echo '<div class="cart_error"> A minimum $' . $_COOKIE['minimum_order_value'] . ' Order Required for this zipcode.</div>';
            }
          } else {
            //echo '<div class="cart_error"></div>';
            echo '<div class="cart_error">' . $_COOKIE['zipcode_error_message'] . '</div>';
            /* if (!IsUserLogin()) {
              echo '<a href="' . BASE_URL . 'login" class="vraj-btn" id="vraj-btn">Proceed to checkout </a>';
            } else {
              echo '<a href="' . BASE_URL . 'checkout" class="vraj-btn" id="vraj-btn">Proceed to checkout </a>';
            } */
          }
        } else {
          echo '<div class="cart_error">Please Enter zipcode first</div>';
        }
        /* if (isset($this->session->userdata['logged_in']['user_id']) && $this->session->userdata['logged_in']['user_id'] > 0) {
          echo '<a href="' . BASE_URL . 'checkout" class="vraj-btn" id="vraj-btn">Proceed to checkout</a>';
        } else {
          echo '<a href="' . BASE_URL . 'login" class="vraj-btn" id="vraj-btn">Proceed to checkout </a>';
        } */
        ?>
      </div>
      <?php
    } ?>
  </div>
</section>
<?php require_once('common/common_js.php'); ?>
<?php require_once('scripts/cart_js.php'); ?>
<?php require_once('common/footer.php'); ?>