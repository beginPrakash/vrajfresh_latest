<?php
// header("Content-type: application/pdf");
?>
<style>
   .box-body .dataTables_wrapper .dataTables_info {
      width: 12%;
      display: inline-block;
   }
   .box-body .dataTables_wrapper .dataTables_packlist {
      width: 11%;
      display: inline-block;
   }
</style>
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header filter_div">
            <!-- Fillter area -->
            <div class="row">
               <form id="frmListDataFilter" action="<?php echo SITE_URL ?>orders" method="post">
                  <div class="col-sm-2">
                     <input size="15" type="text" class="extra_field form-control" name="txtSearchKeyWord"
                        id="txtSearchKeyWord" placeholder="Search Keyword" />
                  </div>
                  <div class="col-sm-2">
                     <select class="form-control select2" style="width: 100%;" name="ddIsActive" id="ddIsActive">
                        <option value="" selected="selected">--Order Status--</option>
                        <option value="Out For Delivery" selected>Out For Delivery</option>
                        <option value="Completed">Completed</option>
                     </select>
                  </div>
                  <div class="col-sm-3">
                     <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit" value="Search">
                     <input type="button" class="btn btn-info" name="searchReset" id="searchReset" value="Reset">
                  </div>
               </form>
               
            </div>
            <!-- Fillter area end-->
         </div>
         <!-- /.box-header -->
         <div class="box-body">
            <table id="tblListData" class="display nowrap" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th>Action</th>
                     <th>Order ID</th>
                     <th>User Name</th>
                     <th>Mobile</th>
                     <th>City</th>
                     <th>Zipcode</th>
                     <th>Order Status</th>
                  </tr>
               </thead>
               
            </table>
         </div>
        
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
   <!-- /.col -->
</div>
<!-- /.row -->
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
   /* DATA TABLE JS */
   var orderCol = [
      // [1, "desc"]
   ];
   var cols = [{
      "bSortable": false
   }, {
      "bSortable": false
   }, {
      "bSortable": true
   }, {
      "bSortable": true
   }, {
      "bSortable": true
   }, {
      "bSortable": true
   }, {
      "bSortable": false
   }];
   var colDef = [0, 7];
   var aoColumnDefs = [];
   var aoColumnDefsCenter = [];
   var ajaxURL1 = {
      url: $('#frmListDataFilter').attr('action'),
      type: "POST",
      data: function (d) {
         d.txtSearchKeyWord = $("#txtSearchKeyWord").val();
         d.txtSearchFrom = $("#txtSearchFrom").val();
         d.txtSearchTo = $("#txtSearchTo").val();
         d.ddIsActive = $("#ddIsActive").val();
      }
   };
   var footerCallBack = function (row, data, start, end, display) { };


</script>