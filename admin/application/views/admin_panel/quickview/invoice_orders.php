<?php

error_reporting(0);

?>

<!DOCTYPE html>

<html lang="en">



<body style="margin:5%; width:800px; margin: auto;">

    <div class="centered-content" style="width:100%">



        <div>

            <div style="float:right;text-align:left;font-size:13px;width:50%; padding-left:10%;">

                <div style=" padding-left:40%;">

                     <b style="font-size:16px;">Vraj Fresh</b><br>

                449 Market Street <br>

                Saddle Brook,<br/> New Jersey, 07652 <br>

                United States (US) 

                    <br>

                    <!-- </div> -->

                    <!-- <div style="float:left;text-align:left;width:50%;font-size:14px;margin-top:20px;"> -->





                </div>

            </div>

            <div style="margin-left:1%; width:50%">

                <div style="margin-left:1%;">

                    <img src="<?php echo ADMIN_PANEL_THEME_PATH . 'dist/img/favicon.png' ?>"

                        style="width:10%; margin-top:3%; margin-left:7%;"><br>

                    <img src="<?php echo ADMIN_PANEL_THEME_PATH . 'dist/img/packing-logo.png' ?>" style="width:30%;  ">

                </div>

            </div>

        </div>

        <div style="width:100%;text-align:center;display: inline-flex;">



            <div style="font-size:15px; width:33%; text-align: left;">

                <b>INVOICE:

                    <?php echo $ArrFieldDatashow['order_id']; ?>

                </b><br>

                <b>Order No:

                    <?php echo $ArrFieldDatashow['order_id']; ?>

                </b><br>

                Invoice Date:

                <?php echo $ArrFieldDatashow['order_datetime']; ?><br>

                Date:

                <?php echo $ArrFieldDatashow['order_datetime']; ?><br>

                Email:

                <?php echo $ArrFieldDatashow['shipping_email'] ?><br>

                Tel:

                <?php echo $ArrFieldDatashow['shipping_phone']; ?><br>

                Delivery Method: Free Delivery<br>

                Item Replacement:

                <?php if ($ArrFieldDatashow['is_replace_item'] == 4) {

echo "No, please refund";

} elseif ($ArrFieldDatashow['is_replace_item'] == 3) {

echo "Yes, Substitute on entire order";

}elseif ($ArrFieldDatashow['is_replace_item'] == 2) {

echo "Yes, Substitute on selected products only";

} ?><br>

            </div>



            <div style="font-size:15px;text-align:left; width:33%; padding-left:10%;">

                <label><b>Billing Address:</b> </label><br>

                <?php echo $ArrFieldDatashow['shipping_first_name'] . '  ' . $ArrFieldDatashow['shipping_last_name'] ?><br>

                <?php echo $ArrFieldDatashow['billing_street_name'] ?><br>

                <?php echo $ArrFieldDatashow['billing_apartment_name'] ?> <br>

                <?php echo $ArrFieldDatashow['billing_city'] ?>,

                <?php echo $ArrFieldDatashow['billing_state_name'] ?><br>

                <?php echo $ArrFieldDatashow['billing_zipcode'] ?><br>

                <br>

            </div>

            <div style="font-size:15px; width:33%; text-align:left; padding-left:1%; ">

                <b>Delivery Address:</b><br>

                <?php echo $ArrFieldDatashow['shipping_first_name'] . ' &nbsp' . $ArrFieldDatashow['shipping_last_name'] ?><br>

                <?php echo $ArrFieldDatashow['shipping_street_name'] ?><br>

                <?php echo $ArrFieldDatashow['shipping_apartment_name'] ?><br>

                <?php echo $ArrFieldDatashow['shipping_city'] ?>,

                <?php echo $ArrFieldDatashow['shipping_state_name'] ?><br>

                <?php echo $ArrFieldDatashow['shipping_zipcode'] ?><br>



            </div>

        </div>

        <table class="no-spacing" cellspacing="0" style="width: 100%; border:1px solid rgb(218 218 218);">

            <thead>

                <tr style="background-color:rgb(33 37 41);border:1px solid black;">

                    <!-- <th style="height:40px;border:1px solid black;">

                        <font color="white">Image</font>

                    </th> -->

                    <th style="height:40px;border:1px solid black;">

                        <font color="white">SKU</font>

                    </th>

                    <th style="height:40px;border:1px solid black;">

                        <font color="white">Products</font>

                    </th>

                    <th style="height:40px;border:1px solid black;">

                        <font color="white">Quantity</font>

                    </th>

                    <th style="height:40px;border:1px solid black;">

                        <font color="white"> Price </font>

                    </th>

                    <th style="height:40px;border:1px solid black;">

                        <font color="white"> Tax </font>

                    </th>

                    <th style="height:40px;border:1px solid black;">

                        <font color="white">Total Price</font>

                    </th>

                    <br>



                </tr>

                <tr>

            <tbody>

                <?php

                $total_qty = $net_total = $total_state_tax = 0;

                //print_r($ArrOrderProduct);

                foreach ($ArrOrderProductshow as $arr) {

                    $total_qty += $arr['qty'];

                    $net_total += $arr['total_amount'];

                    ?>

                    <tr>

                        <!-- <td style="height:30px;border:1px solid rgb(218 218 218);">

                        <?php //echo $arr['product_image']; 

                            ?>

                    </td> -->



                        <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">

                            <?php if ($arr['variant_sku'] != '') {

                                echo $arr['variant_sku'];

                            } else {

                                echo $arr['product_sku'];

                            } ?>

                        </td>



                        <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">

                            <?php if ($arr['product_variant_size'] == "") {

                                echo $arr['product_name'];

                            } else {

                                echo $arr['product_name'] . ' (' . $arr['product_variant_size'] . ' LB)';

                            } ?>

                        </td>

                        <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">

                            <?php if ($arr['old_qty'] != $arr['qty']) { ?><del>

                                <?php echo $arr['old_qty']; ?>

                            </del>&nbsp;

                        <?php } ?>

                            <?php echo $arr['qty']; ?>

                        </td>



                        <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">

                            $

                            <?php echo $arr['unit_price'];

                            ?>

                        </td>

                        <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">

                            $

                            <?php

							echo $arr['product_tax_amount'];

							$total_state_tax = $total_state_tax + $arr['product_tax_amount'];

                            ?>

                        </td>



                        <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">

                            $

                            <?php echo $arr['total_amount'] + $arr['product_tax_amount']; ?>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

            </tr>

            <tr>

                <td colspan="5"

                    style="text-align:right; height:30px; border:1px solid rgb(218 218 218);padding-right: 1%;">Subtotal

                </td>

                <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">$

                    <?php echo $ArrFieldDatashow['order_amount']; ?>

                </td>

            </tr>

            <tr>

                <td colspan="5"

                    style="text-align: right ; height:30px; border:1px solid rgb(218 218 218);padding-right: 1%;">

                    Delivery</td>

                <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">Free Delivery</td>

            </tr>

            <tr>

                <td colspan="5"

                    style="text-align: right ; height:30px; border:1px solid rgb(218 218 218);padding-right: 1%;">

                    Payment Method</td>

                <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">--</td>

            </tr>

            <tr>

                <td colspan="5"

                    style="text-align: right ; height:30px; border:1px solid rgb(218 218 218);padding-right: 1%;">

                    State Tax </td>

                <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">$<?php echo $total_state_tax; ?></td>

            </tr>

            <tr>

                <td colspan="5"

                    style="text-align: right ; height:30px; border:1px solid rgb(218 218 218);padding-right: 1%;">Promo Discount <?php if(!empty($ArrFieldDatashow['promotional_code'])): ?>(<?php echo $ArrFieldDatashow['promotional_code']; ?>)<?php endif; ?>

                </td>

                <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">$

                    <?php if ($ArrFieldDatashow['discount_amount'] > 0) {

                        echo $ArrFieldDatashow['discount_amount'];

                    } else {

                        echo '0';

                    } ?>

                </td>

            </tr>

            <tr>

                <td colspan="5"

                    style="text-align: right ; height:30px; border:1px solid rgb(218 218 218);padding-right: 1%;">Tip

                </td>

                <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">$

                    <?php if ($ArrFieldDatashow['order_tip'] > 0) {

                        echo $ArrFieldDatashow['order_tip'];

                    } else {

                        echo '0';

                    } ?>

                </td>

            </tr>

            <tr>

                <td colspan="5"

                    style="text-align: right ; height:30px; border:1px solid rgb(218 218 218);padding-right: 1%;">

                    Preparation Cost</td>

                <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">$

                    <?php echo $ArrFieldDatashow['preparation_cost']; ?>

                </td>

            </tr>

            <tr>

                <td colspan="5"

                    style="text-align: right ; height:30px; border:1px solid rgb(218 218 218);padding-right: 1%;">

                    Packaging Cost</td>

                <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">$

                    <?php echo $ArrFieldDatashow['packaging_cost']; ?>

                </td>

            </tr>

            <tr>

                <td colspan="5"

                    style="text-align: right ; height:30px; border:1px solid rgb(218 218 218);padding-right: 1%;">Total

                </td>

                <td style="height:30px;border:1px solid rgb(218 218 218);text-align: center;">$

                    <?php echo $ArrFieldDatashow['order_total_amount']; ?>

                </td>

            </tr>

            </thead>

        </table>





</body>



</html>

