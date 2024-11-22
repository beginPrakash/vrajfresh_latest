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
   .break-after-characters {
      word-break: break-word;  /* Breaks long words */
      white-space: normal!important;     /* Allows text to wrap */
      max-width: 200px;        /* Set maximum width to trigger wrapping */
   }
</style>
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header filter_div">
            <!-- Fillter area -->
            <div class="row">
               <form id="frmListDataFilter" action="<?php echo SITE_URL ?>custom_reports" method="post">
                  <div class="col-sm-2">
                     <input type="text" class="form-control date_picker_bottom_center" name="txtSearchFrom"
                        id="txtSearchFrom" placeholder="Order Placed From" title="Order Placed From"
                        autocomplete="off">
                  </div>
                  <div class="col-sm-2">
                     <input type="text" class="form-control date_picker_bottom_center" name="txtSearchTo"
                        id="txtSearchTo" placeholder="Order Placed To" title="Order Placed To" autocomplete="off">
                  </div>
                  <div class="col-sm-4">
                     <select name="src_type" id="src_type" class="form-control">
                        <option value="">Select Customer Type</option>
                        <option value="new_cutomer">New customer</option>
                        <option value="returning_customer">Returning customer</option>
                     </select>
                  </div>
                  <div class="col-sm-3">
                     <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit" value="Search">
                     <input type="button" class="btn btn-info" name="searchReset" id="searchReset" value="Reset">
                     <input type="submit" class="btn btn-warning" name="searchExport" id="searchExport" value="Export to CSV">
                  <input type="hidden" value="<?php echo SITE_URL ?>adminpanel/Controller_customreport/order_list_export"
                     id="searchExportaction">
                  </div>
               </form>
               <div class="col-sm-2">
</div>  

                  <!-- <input type="button" name="packlist_order" value="Pack List" class="btn btn-primary pull-left" onClick="return packlist_order();" /> -->
            </div>
            <!-- Fillter area end-->
         </div>
         <!-- /.box-header -->
         <div class="box-body">
            <table id="tblListData" class="display nowrap" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     
                     <th>Customer Type</th>
                     <th>Registration Date</th>
                     <th>Customer Email</th>
                     <th>Order Date</th>
                     <th>Order Number</th>
                     <th>Order Amount</th>
                  </tr>
               </thead>
               <tfoot>
                  <tr>
                     <td colspan="2">
                     </td>
                  </tr>
               </tfoot>
            </table>
         </div>
        
         <input type="hidden" value="<?php echo base_url() ?>adminpanel/Controller_customreport/view_customer_ajax"
            id="view_action">
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
      [1, "asc"]
   ];
   var cols = [{
      "bSortable": true,
   }, {
      "bSortable": true
   }, {
      "bSortable": true
   },{
      "bSortable": true,
      "className":'break-after-characters'
   }, {
      "bSortable": true,
      "className":'break-after-characters'
   }, {
      "bSortable": true
   }];
   var colDef = [0, 5];
   var aoColumnDefs = [];
   var aoColumnDefsCenter = [];
   var ajaxURL1 = {
      url: $('#frmListDataFilter').attr('action'),
      type: "POST",
      data: function (d) {
         d.txtSearchFrom = $("#txtSearchFrom").val();
         d.txtSearchTo = $("#txtSearchTo").val();
         d.src_type = $("#src_type").val();
      }
   };
   var footerCallBack = function (row, data, start, end, display) { };


   //- export csv file of customer - START
   $("#searchExport").click(function () {

      $(document.body).css({
         'cursor': 'wait'
      });
      $.ajax({
         type: "POST",
         url: $("#searchExportaction").val(),
         data: $("#frmListDataFilter").serialize(),
         success: function (data, textStatus, request) {

            if (jQuery.isPlainObject(data)) {
               data = data.data; //because my return data have a 'data' parameter with the content
            }

            var filename = "";
            var disposition = request.getResponseHeader('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
               var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
               var matches = filenameRegex.exec(disposition);
               if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }
            if (!jQuery.isPlainObject(data)) { //is CSV - we use blob
               var type = request.getResponseHeader('Content-Type');
               var blob = new Blob([data], {
                  type: type,
                  endings: 'native'
               });
               var URL = window.URL || window.webkitURL;
               var downloadUrl = URL.createObjectURL(blob);
            }
            var a = document.createElement("a");
            a.href = jQuery.isPlainObject(data) ? data.file : downloadUrl;
            a.download = jQuery.isPlainObject(data) ? data.filename : filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
         },
         error: function (ajaxContext) {
            toastr.error('Export error: ' + ajaxContext.responseText);
         }
      });
   });
   //- export csv file of customer - END
   

 
</script>