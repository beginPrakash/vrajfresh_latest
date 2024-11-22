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
               <form id="frmListDataFilter" action="<?php echo SITE_URL ?>tax_reports" method="post">
                  <div class="col-sm-3">
                     <select name="src_state" id="src_state" class="select_with_noclear">
                        <option value="">Select State</option>
                        <?php foreach ($states as $key => $value): ?>
                           <option value="<?php echo $value['state_id']; ?>"><?php echo $value['state']; ?>
                           </option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="col-sm-2">
                     <select name="src_month" id="src_month" class="select_with_noclear">
                        <option value="">Select Month</option>
                        <?php for ($m=1; $m<=12; $m++) {
                            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                            $month_number = date('m', mktime(0,0,0,$m, 1, date('Y'))); ?>
                        <option value="<?php echo $month_number; ?>"><?php echo $month; ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <?php
                  $current_year= date('Y');  $years = range(2010, $current_year); ?>
                  <div class="col-sm-2">
                     <select name="src_year" id="src_year" class="select_with_noclear">
                        <option value="">Select Year</option>
                        <?php foreach($years as $val) { ?>
                        <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="col-sm-3">
                     <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit" value="Search">
                     <input type="button" class="btn btn-info" name="searchReset" id="searchReset" value="Reset">
                     <input type="submit" class="btn btn-warning" name="searchExport" id="searchExport" value="Export to CSV">
                  <input type="hidden" value="<?php echo SITE_URL ?>adminpanel/Controller_taxreport/order_list_export"
                     id="searchExportaction">
                  </div>
               </form>
               <div class="col-sm-2">
                  

                  <!-- <input type="button" name="packlist_order" value="Pack List" class="btn btn-primary pull-left" onClick="return packlist_order();" /> -->
            </div>
            <!-- Fillter area end-->
         </div>
         <!-- /.box-header -->
         <div class="box-body">
            <table id="tblListData" class="display nowrap" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     
                     <th>State Name</th>
                     <th>Tax percantage</th>
                     <th>Date</th>
                     <th>Total Tax</th>
                     <th>Total Amount</th>
                     
                  </tr>
               </thead>
            </table>
         </div>
        
         <input type="hidden" value="<?php echo base_url() ?>adminpanel/Controller_taxreport/view_customer_ajax"
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
      "bSortable": true
   }, {
      "bSortable": true
   }, {
      "bSortable": true
   }, {
      "bSortable": true
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
         d.src_month = $("#src_month").val();
         d.src_year = $("#src_year").val();
         d.src_state = $("#src_state").val();
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