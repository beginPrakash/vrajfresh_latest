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
   </style>
</head>

<body class="body">

<?php
for ($k = 1 ; $k <= $no_of_box; $k++){ ?>
   <table style="width: 100%;">
      <tr>
         <td style="text-align: left;padding-bottom: 10px;width:70%;vertical-align: top;">
            <div>
            
               <p style="font-size:15px">Order No</p>
               <p style="font-weight:bold;font-size:30px;margin-top:-10px;"><?php echo $order_data['order_id']; ?></p>
         
            </div>
         </td>
         <td style="text-align: right;padding-bottom: 30px;width:30%">
            <p style="font-weight:bold;font-size:30px;margin-top:10px"><?php echo $no_of_box; ?></p>
         </td>
      </tr>
     
   </table>
   <hr style="border:1px solid #000;margin-top:-40px;"></hr>
   <table style="width: 100%;">
      <tr>
         <td style="text-align: left;padding-bottom: 30px;width:70%;vertical-align: top;">
            <div>
            
               <p style="font-weight:bold;font-size:15px;margin-top:-5px"><?php echo ucfirst($order_data['shipping_first_name']); ?> <?php echo $order_data['shipping_last_name']; ?></p>
               <p style="font-size:15px;margin-top:-10px;"><?php echo $order_data['shipping_street_name'] . ' ' . $order_data['shipping_apartment_name'];?></p>
               <p style="font-size:15px;margin-top:-10px;"><?php echo $order_data['shipping_city'] . ', ' . $order_data['shipping_state_name'] . ', ' . $order_data['shipping_zipcode'];?></p>
            
            </div>
         </td>
        
      </tr>
   </table>
   <hr style="border:1px solid #000;margin-top:-40px;"></hr>
   <table style="width: 100%;">
      <tr>
         <td style="text-align: left;padding-bottom: 30px;width:70%;vertical-align: top;">
            
            <div>
            
               <p style="font-weight:bold;font-size:15px;margin-top:-5px;">Sender:</p>
               <img src="https://vrajfresh.com/admin/assets/images/VrajFresh_Logo02.png" alt="Logo" width="100" style="margin-top:-5px">
               <p style="font-size:9px;margin-top:5px;">
                  <img src="https://vrajfresh.com/admin/assets/images/VF_location.svg" alt="Logo" width="7" style="margin-top:5px;padding-right:5px;">
                  449, Market Street Saddle Brook, NJ 07663
               </p>
               <p style="font-size:9px;margin-top:-6px;">
                <img src="https://vrajfresh.com/admin/assets/images/VF_email.svg" alt="Logo" width="7" style="margin-top:-10px;padding-right:5px;">
                orders@vrajfresh.com 
                <span style="font-size:9px"><img src="https://vrajfresh.com/admin/assets/images/VF_Phone.svg" alt="Logo" width="7" style="padding-right:5px;padding-left:10px">201-688-7887</span></p>
               <p style="font-size:9px;margin-top:-5px;"><img src="https://vrajfresh.com/admin/assets/images/VF_Time.svg" alt="Logo" width="7" style="padding-right:7px;margin-top:-9px;">Work hour: 10:00 - 08:00, Monday - Sunday</p>
            
            </div>
         </td>
         <td style="text-align: right;padding-bottom: 95px;width:30%">
            <p style="font-weight:bold;font-size:15px;margin-top:0px;">Box of</p>
            <p style="font-weight:bold;font-size:20px;margin-top:-10px"> <?php echo $k; ?><span style="font-size:15px"> OF </span><span style="font-weight:bold;font-size:20px"><?php echo $no_of_box; ?></span></p>
         </td>
      </tr>
   </table>
   <hr style="border:1px solid #000;margin-top:-60px;"></hr>
   <table style="width: 100%;">
    <tr>
        <td style="text-align: center;padding-bottom: 30px;width:70%">
        <p style="font-weight:bold;font-size:20px;text-align:center;margin-top:0px;">www.vrajfresh.com</p>
    </td>
    </tr>
   </table>
   <hr style="border:1px solid #000;margin-top:-40px;"></hr>
   <table style="width: 100%;">
    <tr>
       <td style="text-align: center;width:50%;vertical-align: top;">
          
          <div>
          
             <p style="font-size:15px;margin-top:0px">Like us:</p>
             <img src="https://vrajfresh.com/admin/assets/images/follow_us.png" alt="Logo" width="80" style="margin-top:-15px;">
          
          </div>
       </td>
       <td style="text-align: center;width:50%">
        <p style="font-size:15px;margin-top:0px">Follow us:</p>
        <img src="https://vrajfresh.com/admin/assets/images/like_us.png" alt="Logo" width="80" style="margin-top:-15px;">
       </td>
    </tr>
 </table>
<?php } ?>
   

  

</body>

</html>
<?php //exit; ?>