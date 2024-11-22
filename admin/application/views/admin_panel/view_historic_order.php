<php echo "reached" ; error_reporting(E_ALL); ?>


    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header filter_div">
                    <!-- Fillter area -->
                    <div class="row">
                        <form id="frmListDataFilter" action="<?php echo SITE_URL ?>historic-orders" method="post">
                            <div class="col-sm-2">
                                <input size="15" type="text" class="extra_field form-control" name="txtSearchKeyWord"
                                    id="txtSearchKeyWord" placeholder="Search Keyword" />
                            </div>

                            <div class="col-sm-3">
                                <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit"
                                    value="Search">
                                <input type="button" class="btn btn-info" name="searchReset" id="searchReset"
                                    value="Reset">
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
                                <th>Order Number</th>
                                <th>Order Status</th>
                                <th>Order Date</th>
                                <th>Customer Note</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Company</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State Code</th>
                                <th>Postcode</th>
                                <th>Country Code</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Payment Method Title</th>
                                <th>Cart Discount Amount</th>
                                <th>Order Subtotal Amount</th>
                                <th>Delivery Method Title</th>
                                <th>Order Delivery Amount</th>
                                <th>Order Refund Amount</th>
                                <th>Order Total Amount</th>
                                <th>Order Total Tax Amount</th>
                                <th>SKU</th>
                                <th>Item #</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Item Cost</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <input type="hidden" value="<?php echo base_url() ?>adminpanel/controller_order/view_customer_ajax"
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
        var orderCol = [
            [1, "desc"]
        ];
        var cols = [{
            "bSortable": true
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }, {
            "bSortable": false
        }];
        var colDef = [1, 27];
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