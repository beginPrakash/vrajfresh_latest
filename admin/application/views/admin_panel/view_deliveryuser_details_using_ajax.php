<div class="row">
   <div class="col-sm-4">
      <label>Name : </label>
      <label style="font-weight:400; line-height: 20px;">
          <?php echo $ArrFieldData['first_name']; ?> <?php echo $ArrFieldData['last_name']; ?>
      </label>
   </div>

   <div class="col-sm-4">
      <label>Phone : </label>
      <label style="font-weight:400; line-height: 20px;">
          <?php echo $ArrFieldData['phone']; ?>
      </label>
   </div>

   <div class="col-sm-4">
      <label>Email : </label>
      <label style="font-weight:400; line-height: 20px;">
          <?php echo $ArrFieldData['email']; ?>
      </label>
   </div>

   <div class="col-sm-4">
      <label>City : </label>
      <label style="font-weight:400; line-height: 20px;">
          <?php echo $ArrFieldData['city']; ?>
      </label>
   </div>

   <div class="col-sm-4">
      <label>Zipcode : </label>
      <label style="font-weight:400; line-height: 20px;">
          <?php echo $ArrFieldData['zipcode']; ?>
      </label>
   </div>



</div>
<?php if(is_array($ArrOrderData) && count($ArrOrderData)>0) { ?>
<label>Assigned Orders : </label>
   <table border="1" width="100%" id="transaction">
      <thead>
         <th>Order ID</th>
         <th>Order Date</th>
         <th>City</th>
         <th>Zipcode</th>
         <th>Status</th>
      </thead>
      <tbody>
         <?php foreach($ArrOrderData as $data) { ?>
         <tr>
            <td><a href="<?php echo base_url(); ?>update-order/<?php echo $data['order_id']; ?>" target="_blank"><?php echo $data['order_id']; ?></a></td>
            <td><?php echo $data['created_datetime']; ?></td>
            <td><?php echo $data['shipping_city']; ?></td>
            <td><?php echo $data['shipping_zipcode']; ?></td>
            <td><?php echo $data['order_status']; ?></td>
         </tr>
         <?php } ?>
      </tbody>
   </table>
<?php } ?>

<script>
   $('#transaction').DataTable({
      "scrollX": true,
				//"bScrollCollapse": true,

				"paging": true,
				"aaSorting": [],
				"pageLength": 10,
				"lengthChange": true,
				"searching": false,
				"ordering": true,
				"info": false,
				"autoWidth": false,
				"bPaginate": true,

         
   });
         </script>