<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>

   <title>Vraj</title>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <style type="text/css">
       
      @media dompdf {

         html,
         body {
            font-size: 80%;
         }
      }

      table {
         border-collapse: collapse;
         mso-table-lspace: 0px;
         mso-table-rspace: 0px;
      }

      /*assets css start end*/
      body {
         margin: 0 !important;
         padding: 0 !important;
         -webkit-text-size-adjust: 100% !important;
         -ms-text-size-adjust: 100% !important;
         -webkit-font-smoothing: antialiased !important;
         font-size: 10pt;
      }

      .product_tbl th {
         border: 1px solid black;
         text-align: center;
      }

      .product_tbl td {
         border: 1px solid black;
         text-align: center;
      }

      .product_tbl .category_row {
         border: 1px solid black;
         text-align: left;
         background-color: #F5F5F5;
      }
      table {
    page-break-inside: auto;
  }

  tr {
    page-break-inside: avoid;
    page-break-after: auto;
  }
   </style>
</head>

<body class="body">
<div style="page-break-after: always;">

   <table style="width: 100%;">
      <tr>
         <td style="text-align: left;padding-bottom: 30px;width:70%;vertical-align: top;">
            <img src="https://www.vrajfresh.com/assets/images/logo.png" alt="Logo" width="155">
            <div style="margin-top: 20px; margin-bottom: 20px;">
            <?php
            if (!empty($product_ids)) {
            ?>
               <p><?php echo count($product_ids) ?> orders</p>
               <p>Orders:<?php echo implode(',', $product_ids) ?></p>
            <?php
            } else {
            ?>
               <p>0 orders</p>
               <p>Orders: no order</p>
            <?php
            }
            ?>
            <p>Printed on:<?php echo date("Y/m/d h:i:s A"); ?></p>
            </div>
         </td>
         <td style="text-align: left;padding-bottom: 30px;width:30%">
            <h4>From Address:</h4>
            <p>
               Vraj Fresh<br>
               449 MARKET STREET<br>
               SADDLE BROOK NJ<br>
               07663</br>
               United States (US)</br>
            </p>
         </td>
      </tr>
   </table>

   <table style="width: 100%;" class="product_tbl" cellpadding="5" cellspacing="0">
      <thead>
         <tr style="background-color: #333;color:#fff;">
            <!-- <th>Image</th> -->
            <th>SKU</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Weight</th>
         </tr>
      </thead>
      <tbody>
         <?php
        //echo'<pre>';print_r($order_products);exit;
         if (!empty($order_products)) {
            $category_name_prev = '';
            $product_sku_prev = '';
            $product_skur_prev = '';
            $product_qty_prev = '';
            $qty = '';
            foreach ($order_products as $key => $products_row) {
               $product_sku = $products_row['product_name'].' '.$products_row['variant_sku'];
               $pqty = $products_row['qty'];
               
               if ($product_sku != $product_sku_prev) {
                  $product_sku_prev = $product_sku;
                  $product_qty_prev = $products_row['qty'];
                  $qty = $products_row['qty'];
               }else{
                  $qty = $products_row['qty'] .'+'.$qty;
                  
               }
               $order_products[$key]['qtys'] = $qty;
               $order_products[$key]['check_product_sku'] = $product_sku;
               
            }
         }
//echo'<pre>';print_r($order_products);exit;
         function keepLastDuplicateByKey(&$array, $key) {
            $seen = [];
            
            // Loop through the array in reverse to keep the last occurrence
            foreach (array_reverse($array, true) as $index => $item) {
                if (isset($item[$key])) {
                    $value = $item[$key];
        
                    if (isset($seen[$value])) {
                        // Remove earlier occurrences
                        unset($array[$index]);
                    } else {
                        // Store last occurrence index
                        $seen[$value] = $index;
                    }
                }
            }
        
            // Reindex array
            $array = array_values($array);
        }
        
        // Keep only the last occurrence of duplicate 'name'
        keepLastDuplicateByKey($order_products, 'check_product_sku');

         if (!empty($order_products)) {
           
            $category_name_prev = '';
            $product_sku_prev = '';
            $product_skur_prev = '';
            $product_qty_prev = '';
            foreach ($order_products as $key => $products_row) {
               $category_name = $products_row['category_name'];
               $product_sku = $products_row['variant_sku'];
               $pqty = $products_row['qty'];
               if ($category_name != $category_name_prev) {
                  $category_name_prev = $category_name;
                  echo '<tr>';
                  echo '<td colspan="4" class="category_row">' . $category_name . '</td>';
                  echo '</tr>';
               }
               $imag_path = SITE_URL . 'uploads/products/' . $products_row['product_image'];
               if (!@file_get_contents($imag_path)) {
                  $imag_path = SITE_URL . 'uploads/no-image.png';
               }
               $total_weight=$products_row['product_weight_gms'] * $products_row['qty'] . ' lb';
               $product_variant_size = '';
               if (!empty($products_row['product_variant_size'])) {
                  $product_variant_size = '<br>Size: ' . $products_row['product_variant_size'] . '';
                  $total_weight=$product_variant_size;
               }

              $dis_sku = empty($products_row['variant_sku']) ? $products_row['product_sku'] : $products_row['variant_sku'];
                  echo '<tr>';
                  echo '<td>' . $dis_sku . '</td>';
                  echo '<td>' . $products_row['product_name'] .'</td>';
                  echo '<td>' . $products_row['qtys'] .'</td>';
                  echo '<td>' . $total_weight .'</td>';
                  echo '</tr>';
               
               
            }
         }
         ?>
      </tbody>
   </table>
      
</body>
</div>
</html>
<?php //exit; ?>