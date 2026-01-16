<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header filter_div">
            <!-- Fillter area -->
            <div class="row">
               <form id="frmListDataFilter" action="<?php echo SITE_URL ?>newsletter-subscriber" method="post">
                  <div class="col-sm-2">
                     <input size="15" type="text" class="extra_field form-control" name="txtSearchKeyWord" id="txtSearchKeyWord" placeholder="Search Keyword" />
                  </div>
                  <div class="col-sm-2">
                     <input type="text" class="form-control date_picker_bottom_center" name="txtSearchFrom" id="txtSearchFrom" placeholder="Created From" title="Created From" autocomplete="off">
                  </div>
                  <div class="col-sm-2">
                     <input type="text" class="form-control date_picker_bottom_center" name="txtSearchTo" id="txtSearchTo"  placeholder="Created To" title="Created To" autocomplete="off">
                  </div>
                  <div class="col-sm-2">
                     <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit" value="Search">
                     <input type="button" class="btn btn-info" name="searchReset" id="searchReset" value="Reset">
                  </div>
               </form>
               <div class="col-sm-2">
					<input type="submit" class="btn btn-warning" name="searchExport" id="searchExport" value="Export to CSV">
                  <input type="hidden" value="<?php echo SITE_URL ?>adminpanel/controller_newsletter_subscriber/newsletter_subscriber_list_export" id="searchExportaction">
               </div>
            </div>
            <!-- Fillter area end-->
         </div>
         <!-- /.box-header -->
         <div class="box-body">
            <table id="tblListData" class="display nowrap" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th>Action</th>
                     <th>ID</th>
                     <th>Date</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Status</th>
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
         <input type="hidden" value="<?php echo base_url() ?>adminpanel/controller_newsletter_subscriber/delete_multiple_newsletter_subscriber_record" id="multiple_delete_action">
         <input type="hidden" value="<?php echo base_url() ?>adminpanel/controller_newsletter_subscriber/view_newsletter_subscriber_ajax" id="view_action">
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
var cols = [{ "bSortable": false },{ "bSortable": true },{ "bSortable": true },{ "bSortable": true }, { "bSortable": true }, { "bSortable": true }];
var colDef = [0,6];
var aoColumnDefs = [];
var aoColumnDefsCenter = [];
var ajaxURL1 = {
   url: $('#frmListDataFilter').attr('action'),
   type: "POST",
   data: function (d) {
      d.txtSearchKeyWord = $("#txtSearchKeyWord").val();
      d.txtSearchFrom = $("#txtSearchFrom").val();
      d.txtSearchTo = $("#txtSearchTo").val();
   }
};
var footerCallBack = function ( row, data, start, end, display ) {};
</script>