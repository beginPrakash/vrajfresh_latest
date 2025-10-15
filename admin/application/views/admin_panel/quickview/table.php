<?php error_reporting(0); ?>

<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>



</head>

<input type="hidden" name="order_id" value="" />

<input type="hidden" name="order_total_tax" value="">

<input type="hidden" name="product_counter" id="product_counter" value="0" />



<body style="margin:5%; width:800px; margin: auto;">

    <div class="centered-content" style="width:100%">

        <div style="float:right;text-align:right;font-size:12px;width:50%;padding-right:5%">

            <div>

                <h3 style='margin-bottom:0; '><b>From Address:</b></h3>            

<b style="font-size:16px;">Vraj Fresh</b><br>

                449 Market Street <br>

                Saddle Brook, <br/>New Jersey, 07652 <br>

                United States (US) <br>

            </div>

            <div style="float:left;text-align:left;width:50%;font-size:14px;margin-top:20px;">

                <b>Delivery Addresss:</b><br>

                <?php echo $ArrFieldDatashow['shipping_first_name'] . ' &nbsp' . $ArrFieldDatashow['shipping_last_name'] ?><br>

                <?php echo $ArrFieldDatashow['shipping_street_name'] ?><br>

                <?php echo $ArrFieldDatashow['shipping_apartment_name'] ?><br>

                <?php echo $ArrFieldDatashow['shipping_city'] ?><br>

                <?php echo $ArrFieldDatashow['shipping_state_name'] ?>-

                <?php echo $ArrFieldDatashow['shipping_zipcode'] ?><br>

                <b>Delivery Method:</b> Free Delivery<br>

                Item Replacement:

                <?php if ($ArrFieldDatashow['is_replace_item'] == 4) {

                    echo "No, please refund";

                    } elseif ($ArrFieldDatashow['is_replace_item'] == 3) {

                    echo "Yes, Substitute on entire order";

                    }elseif ($ArrFieldDatashow['is_replace_item'] == 2) {

                    echo "Yes, Substitute on selected products only";

                    } ?><br>



                <br>



                <?php if ($ArrFieldDatashow['delivery_comments'] != '') { ?>
                    <b>Delivery Instructions:</b>

                    <?php echo $ArrFieldDatashow['delivery_comments']; ?><br>
                <?php } ?>
                <?php if ($ArrFieldDatashow['delivery_type'] != '') { ?>
                    <b>Delivery Type:</b>

                    <?php echo $ArrFieldDatashow['delivery_type']; ?><br>
                <?php } ?>
                <?php if ($ArrFieldDatashow['delivery_datetime'] != '') { ?>
                    <b>Delivery Date:</b>

                    <?php echo $ArrFieldDatashow['delivery_datetime']; ?><br>
                <?php } ?>

                <b>Payment Method:</b>

                    <?php echo $ArrFieldDatashow['payment_methodtype']; ?><br>


            </div>

        </div>

        <div style="margin-left:2%; width:50%">

            <div style="margin-left:3%;">

                <img src="<?php echo ADMIN_PANEL_THEME_PATH . 'dist/img/favicon.png' ?>"

                    style="width:10%; margin-top:3%; margin-left:7%;"><br>

                <img src="<?php echo ADMIN_PANEL_THEME_PATH . 'dist/img/packing-logo.png' ?>" style="width:30%;  ">



            </div>



            <div style="width:100%">

                <b>

                    <?php echo $ArrFieldDatashow['order_id']; ?>

                </b> <br>

                Date:

                <?php echo $ArrFieldDatashow['created_datetime']; ?>

                <br>



            </div>

            <div style="width:100%">

            <br>

            <b>Billing Address:</b> <br>



            <?php echo $ArrFieldDatashow['shipping_first_name'] . ' ' . $ArrFieldDatashow['shipping_last_name']  ?><br>



            <?php echo $ArrFieldDatashow['billing_street_name'] ?><br>



            <?php echo $ArrFieldDatashow['billing_apartment_name'] ?><br>



            <?php echo $ArrFieldDatashow['billing_city'] ?>,<br>



            <?php echo $ArrFieldDatashow['billing_state_name'] ?>-



            <?php echo $ArrFieldDatashow['billing_zipcode'] ?><br>



            <br>



            <b>Email:</b><?php echo $ArrFieldDatashow['shipping_email'] ?> <br>



            <b>Tel:</b> <?php echo $ArrFieldDatashow['shipping_phone']; ?> <br>
            <?php if ($ArrFieldDatashow['order_notes'] != '') { ?>
            <b>Order Note:</b> <?php echo $ArrFieldDatashow['order_notes']; ?><br>
<?php } ?>
                

			<?php //echo "<pre>";print_r($ArrOrderProductshow);exit; ?>



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

                <!-- <th style="height:40px;border:1px solid black;">

                    <font color="white">Total Weight</font>

                </th> -->

                <th style="height:40px;border:1px solid black;">

                    <font color="white">Total Price</font>

                </th>

                <br>



            </tr>

            <tr>

        <tbody>

            <?php

            $total_qty = $net_total = 0;

            $pre_category = '';

            foreach ($ArrOrderProductshow as $arr) {

                $total_qty += $arr['qty'];

                $net_total += $arr['total_amount'];

                ?>

                <?php if ($pre_category != $arr['category_name']) { ?>

                    <tr>

                        <td colspan="6" style="text-align: center">

                            <font color="#525252">

                                <?php echo $arr['category_name']; ?>

                            </font>

                        </td>

                    </tr>

                <?php } ?>





                <tr>

                    <!-- <td style="height:30px;border:1px solid rgb(218 218 218);">

                        <?php //echo $arr['product_image']; ?>

                    </td> -->



                    <td style="height:30px;border:1px solid rgb(218 218 218);">

                        <?php if ($arr['variant_sku'] != '') {

                            echo $arr['variant_sku'];

                        } else {

                            echo $arr['product_sku'];

                        } ?>

                    </td>



                    <td style="height:30px;border:1px solid rgb(218 218 218);">

						<?php if ($arr['product_variant_size'] == "") {

                                echo $arr['product_name'];

                            } else {

                                echo $arr['product_name'] . ' (' . $arr['product_variant_size'] . ' LB)';

                            } ?>

							

                    </td>

                    <td style="height:30px;border:1px solid rgb(218 218 218);">

                        <?php if ($arr['old_qty'] > 0) { ?><del>

                            <?php echo $arr['old_qty']; ?>

                        </del>&nbsp;

                    <?php } ?>

                        <?php echo $arr['qty']; ?>

                    </td>



                    <!--  <td style="height:30px;border:1px solid rgb(218 218 218);">

                        <?php //echo $arr['product_weight_gms']; ?> LB

                    </td> -->



                    <td style="height:30px;border:1px solid rgb(218 218 218);">

                        <?php echo $arr['total_amount']; ?>

                    </td>

                </tr>

                <?php

                $pre_category = $arr['category_name'];

            }

            ?>

        </tbody>

        </tr>

        </thead>

    </table>





</body>



</html>

