<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header filter_div">
            <div class="row">
               <form id="frmListDataFilter" action="<?php echo SITE_URL ?>clientgroup" method="post">
                  <div class="col-sm-2">
                     <input type="text" name="ser_from_date" id="ser_from_date" value=""
                        class="form-control date_picker_bottom_left" placeholder="From Date" autocomplete="off" />
                  </div>
                  <div class="col-sm-2">
                     <input type="text" name="ser_to_date" id="ser_to_date" value=""
                        class="form-control date_picker_bottom_left" placeholder="To Date" autocomplete="off" />
                  </div>
                  <div class="col-sm-2">
                     <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit" value="Search">
                     <input type="button" class="btn btn-info" name="searchReset" id="searchReset" value="Reset">
                  </div>
               </form>
               <!--
               <div class="col-sm-2">
                  <input type="submit" class="btn btn-warning" name="searchExport" id="searchExport" value="Export to CSV">
                  <input type="hidden" value="<?php echo SITE_URL ?>adminpanel/controller_clientgroup/clientgroup_list_export" id="searchExportaction">
               </div>-->
            </div>
         </div>

         <div class="box-body">
            <table id="tblListData" class="display nowrap" cellspacing="0" width="100%">
               <thead>
                  <tr>
                     <th><input type="checkbox" class="select_all_delete">&nbsp;Action</th>
                     <th>ID</th>
                     <th>Created Date</th>
                     <th>Customer Group Title</th>
                     <th>Is Acitve</th>
                  </tr>
               </thead>
               <tfoot>
                  <tr>
                     <td colspan="2"><input type="submit" name="submitForm" value="Delete"
                           class="btn btn-danger pull-left" onClick="return delete_multiple_record();" />
                     </td>
                  </tr>
               </tfoot>
            </table>
         </div>
         <input type="hidden"
            value="<?php echo base_url() ?>adminpanel/controller_clientgroup/delete_multiple_clientgroup_record"
            id="multiple_delete_action">
         <input type="hidden" value="<?php echo base_url() ?>adminpanel/controller_clientgroup/view_clientgroup_ajax"
            id="view_action">
      </div>
   </div>
</div>
<script type="text/javascript">
   /* DATA TABLE JS */
   var orderCol = [[1, "desc"]];
   var cols = [{ "bSortable": false }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }];
   var colDef = [0, 5];
   var aoColumnDefs = [];
   var aoColumnDefsCenter = [];
   var ajaxURL1 = {
      url: $('#frmListDataFilter').attr('action'),
      type: "POST",
      data: function (d) {
         //d.txtSearchKeyWord = $("#txtSearchKeyWord").val();
         d.ser_from_date = $("#ser_from_date").val();
         d.ser_to_date = $("#ser_to_date").val();
      }
   };
   var footerCallBack = function (row, data, start, end, display) { };


   //- export csv file of client group - START
   $("#searchExport").click(function () {

      $(document.body).css({ 'cursor': 'wait' });
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
               var blob = new Blob([data], { type: type, endings: 'native' });
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
//- export csv file of client group - END

</script>