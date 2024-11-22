<div class="row">

   <div class="col-xs-12">

      <div class="box">

         <div class="box-header filter_div">

            <!-- Fillter area -->

            <div class="row">

               <form id="frmListDataFilter" action="<?php echo SITE_URL ?>pantry" method="post">

                  <div class="col-sm-2">

                     <input size="15" type="text" class="extra_field form-control" name="txtSearchKeyWord"

                        id="txtSearchKeyWord" placeholder="Search Keyword" />

                  </div>

                  <div class="col-sm-2">

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

                     <th><input type="checkbox" class="select_all_delete">&nbsp;Action</th>

                     <th>ID</th>

                     <th>Pantry Name</th>

                     <th>Pantry URL</th>

                     <th>Image</th>

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

         <input type="hidden" value="<?php echo base_url() ?>adminpanel/Controller_pantry/delete_multiple_pantry_record"

            id="multiple_delete_action">

         <input type="hidden" value="<?php echo base_url() ?>adminpanel/Controller_pantry/view_pantry_ajax"

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

   var cols = [{ "bSortable": false }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }, { "bSortable": false }, { "bSortable": true }];

   var colDef = [0, 6];

   var aoColumnDefs = [];

   var aoColumnDefsCenter = [];

   var ajaxURL1 = {

      url: $('#frmListDataFilter').attr('action'),

      type: "POST",

      data: function (d) {

         d.txtSearchKeyWord = $("#txtSearchKeyWord").val();

      }

   };

   var footerCallBack = function (row, data, start, end, display) { };






</script>