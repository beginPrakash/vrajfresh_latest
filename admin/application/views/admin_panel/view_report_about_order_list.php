<div class="row">

   <div class="col-xs-12">

      <div class="box">

         <div class="box-header filter_div">

            <div class="row">

               <form id="frmListDataFilter" action="<?php echo SITE_URL ?>report-about-order" method="post">

                  <div class="col-sm-2">

                     <input size="15" type="text" class="extra_field form-control" name="txtSearchKeyWord"

                        id="txtSearchKeyWord" placeholder="Search Keyword" />

                  </div>

                  <div class="col-sm-2">

                     <input type="text" class="form-control date_picker_bottom_center" name="txtSearchFrom"

                        id="txtSearchFrom" placeholder="Created From" title="Created From" autocomplete="off">

                  </div>

                  <div class="col-sm-2">

                     <input type="text" class="form-control date_picker_bottom_center" name="txtSearchTo"

                        id="txtSearchTo" placeholder="Created To" title="Created To" autocomplete="off">

                  </div>

                  <div class="col-sm-2">

                     <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit" value="Search">

                     <input type="button" class="btn btn-info" name="searchReset" id="searchReset" value="Reset">

                  </div>

               </form>

               <div class="col-sm-2">

                  <!-- <input type="submit" class="btn btn-warning" name="searchExport" id="searchExport" value="Export to CSV">-->

                  <input type="hidden"

                     value="<?php echo SITE_URL ?>adminpanel/controller_report_about_order/report_about_order_list_export"

                     id="searchExportaction">

               </div>

            </div>

         </div>

         <div class="box-body">

            <table id="tblListData" class="display nowrap" cellspacing="0" width="100%">

               <thead>

                  <tr>

                     <th><input type="checkbox" class="select_all_delete">&nbsp;Action</th>

                     <th>Date</th>

                     <th>Order ID</th>

                     <th>Name</th>

                     <th>Email</th>

                     <th>Contact</th>

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

            value="<?php echo base_url() ?>adminpanel/controller_report_about_order/delete_multiple_report_about_order_record"

            id="multiple_delete_action">

         <input type="hidden"

            value="<?php echo base_url() ?>adminpanel/controller_report_about_order/view_report_about_order_ajax"

            id="view_action">

      </div>

   </div>

</div>

<script type="text/javascript">

   /* DATA TABLE JS */

   var orderCol = [[1, "desc"]];

   var cols = [{ "bSortable": false }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }];

   var colDef = [0, 5];

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

   var footerCallBack = function (row, data, start, end, display) { };





   //- export csv file of contact inquiry - START

   $("#searchExport").click(function () {



      console.log($("#frmListDataFilter").serialize());



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

//- export csv file of contact inquiry - END

</script>