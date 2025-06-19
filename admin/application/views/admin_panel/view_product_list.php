<div class="row">

   <div class="col-xs-12">

      <div class="box">

         <div class="box-header filter_div">

            <!-- Fillter area -->

            <div class="row">

               <form id="frmListDataFilter" action="<?php echo SITE_URL ?>product" method="post">

                  <div class="col-sm-2">

                     <input size="15" type="text" class="extra_field form-control" name="txtSearchKeyWord"

                        id="txtSearchKeyWord" placeholder="Search Keyword" />

                  </div>

                  <div class="col-sm-4">

                     <select name="src_category[]" id="src_category" class="multiple_category_select" multiple="">

                        <option value="">Select Category</option>

                        <?php foreach ($category as $key => $value): ?>

                           <option value="<?php echo $value['category_id']; ?>"><?php echo $value['category_name']; ?>

                           </option>

                        <?php endforeach; ?>

                     </select>

                  </div>
                  <div class="col-sm-2">
                     <select class="form-control select2" style="width: 100%;" name="prodStatus" id="prodStatus">
                        <option value="" selected="selected">--Product Status--</option>
                        <option value="active">Active</option>
                        <option value="inactive">In Active</option>
                        <option value="out_of_stock">Out Of Stock</option>
                     </select>
                  </div>

                  <div class="col-sm-2">

                     <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit" value="Search">

                     <input type="button" class="btn btn-info" name="searchReset" id="searchReset" value="Reset">

                  </div>

               </form>

               <div class="col-sm-2">

                  <!-- <input type="submit" class="btn btn-warning" name="searchExport" id="searchExport" value="Export to CSV">-->

                  <input type="hidden" value="<?php echo SITE_URL ?>adminpanel/controller_product/product_list_export"

                     id="searchExportaction">

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

                     <th>SKU</th>

                     <th>Product Name</th>

                     <th>MRP</th>

                     <th>Sale Price</th>

                     <th>Unit Cost</th>

                     <th>Product Image</th>

                     <th>Is Active</th>

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

            value="<?php echo base_url() ?>adminpanel/controller_product/delete_multiple_product_record"

            id="multiple_delete_action">

         <input type="hidden" value="<?php echo base_url() ?>adminpanel/controller_product/view_product_ajax"

            id="view_action">

         <!-- /.box-body -->

      </div>

      <!-- /.box -->

   </div>

   <!-- /.col -->

</div>

<!-- /.row -->

<script type="text/javascript">

   /* DATA TABLE JS */

   var orderCol = [[1, "desc"]];

   var cols = [{ "bSortable": false }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }, { "bSortable": false }, { "bSortable": true }];

   var colDef = [0, 8];

   var aoColumnDefs = [];

   var aoColumnDefsCenter = [];

   var ajaxURL1 = {

      url: $('#frmListDataFilter').attr('action'),

      type: "POST",

      data: function (d) {

         d.txtSearchKeyWord = $("#txtSearchKeyWord").val();

         d.src_category = $("#src_category").val();

         d.prod_status = $("#prodStatus").val();

      }

   };

   var footerCallBack = function (row, data, start, end, display) { };





   //- export csv file of product - START

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

 //- export csv file of product - END





</script>