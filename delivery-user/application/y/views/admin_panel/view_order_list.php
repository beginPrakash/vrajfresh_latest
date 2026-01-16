<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header filter_div">
            <!-- Fillter area -->
            <div class="row">
               <form id="frmListDataFilter" action="<?php echo SITE_URL ?>orders" method="post">
                  <div class="col-sm-2">
                     <input size="15" type="text" class="extra_field form-control" name="txtSearchKeyWord" id="txtSearchKeyWord" placeholder="Search Keyword" />
                  </div>
				  <div class="col-sm-2">
					 <input type="text" class="form-control date_picker_bottom_center" name="txtSearchFrom" id="txtSearchFrom" placeholder="Order Placed From" title="Order Placed From" autocomplete="false">
				  </div>
				  <div class="col-sm-2">
					 <input type="text" class="form-control date_picker_bottom_center" name="txtSearchTo" id="txtSearchTo"  placeholder="Order Placed To" title="Order Placed To" autocomplete="false">
				  </div>
					  <div class="col-sm-2">
						 <select class="form-control select2" style="width: 100%;" name="ddIsActive" id="ddIsActive">
							<option value="" selected="selected">--Order Status--</option>
							<option value="Pending Payment">Pending Payment</option>
							<option value="Processing">Processing</option>
							<option value="On Hold">On Hold</option>
							<option value="Completed">Completed</option>
							<option value="Cancelled">Cancelled</option>
							<option value="Refunded">Refunded</option>
							<option value="Failed">Failed</option>
						 </select>
					  </div>
                  <div class="col-sm-3">
                     <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit" value="Search">
                     <input type="button" class="btn btn-info" name="searchReset" id="searchReset" value="Reset">
                  </div>
               </form>
               <div class="col-sm-2">
                  <!-- <input type="submit" class="btn btn-warning" name="searchExport" id="searchExport" value="Export to CSV">-->
                  <input type="hidden" value="<?php echo SITE_URL ?>adminpanel/controller_order/order_list_export" id="searchExportaction">
               </div>
            </div>
            <!-- Fillter area end-->
         </div>
         <!-- /.box-header -->
         <div class="box-body">
            <table id="tblListData" class="display nowrap" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th><input type="checkbox" class="select_all_delete">&nbsp;Action</th>
                     <th>Order ID</th>
                     <th>Order Date</th>
                     <th>Customer Name</th>
                     <th>Mobile</th>
                     <th>Amount</th>
                     <th>Order Status</th>
                  </tr>
			      </thead>
			   	<tfoot>
					<tr>
                  <td colspan="2"><input type="submit" name = "submitForm" value = "Delete" class = "btn btn-danger pull-left" onClick="return delete_multiple_record();" /> 
               </td>
					</tr>
				</tfoot>
            </table>
         </div>
         <input type="hidden" value="<?php echo base_url() ?>adminpanel/controller_order/delete_multiple_order_record" id="multiple_delete_action">
         <input type="hidden" value="<?php echo base_url() ?>adminpanel/controller_order/view_customer_ajax" id="view_action">
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
   <!-- /.col -->
</div>
<!-- /.row -->
<script type="text/javascript">
/* DATA TABLE JS */
var orderCol = [[ 1, "desc" ]];  
var cols = [{ "bSortable": false },{ "bSortable": false }, { "bSortable": true },{ "bSortable": true },{ "bSortable": true }, { "bSortable": true }, { "bSortable": false }];
var colDef = [0,7];
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
var footerCallBack = function ( row, data, start, end, display ) {};


//- export csv file of customer - START
$("#searchExport").click(function(){
    
    $(document.body).css({'cursor' : 'wait'});
    $.ajax({
       type        : "POST",
       url         : $("#searchExportaction").val(),
       data    : $("#frmListDataFilter").serialize(),      
       success: function (data, textStatus, request) {
 
             if(jQuery.isPlainObject(data)) {
             data = data.data; //because my return data have a 'data' parameter with the content
             }
             
             var filename = "";
             var disposition = request.getResponseHeader('Content-Disposition');
             if (disposition && disposition.indexOf('attachment') !== -1) {
             var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
             var matches = filenameRegex.exec(disposition);
             if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
             }
             if(!jQuery.isPlainObject(data)) { //is CSV - we use blob
                var type = request.getResponseHeader('Content-Type');
                var blob = new Blob([data], { type: type ,endings:'native'});
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
       toastr.error('Export error: '+ajaxContext.responseText);
       }
    });
 });
 //- export csv file of customer - END

//Order Edit popup
$(document).on('click', '.edit_popup', function(){
	var action_url = $(this).attr('rel');
	var title = $(this).attr('title');
	var id = $(this).attr('id');
	$.ajax({
		type        : "POST",
		url         : action_url,
		data		: { id : id,title:title },
		success: function(results) {
			$(document.body).css({'cursor' : 'default'});
			$('#recordDetailsPopUp').modal('show');
			$('#detailsPopUpData').html(results);
			$('#detailsPopUpTitle').html(title);
			//$('.select_product_ajax').select2();
		},
		error: function() {
			$(document.body).css({'cursor' : 'default'});
			toastr.error('Oops..!! something went wrong please try again.');
			}
	});
});
</script>