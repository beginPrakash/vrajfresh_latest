<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<style>
    .table-wrapper {
        width: 100%;
        background: #fff;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .table-title {
        padding-bottom: 10px;
        margin: 0 0 10px;
    }

    .table-title h2 {
        margin: 6px 0 0;
        font-size: 22px;
    }

    .table-title .add-new {
        float: right;
        height: 30px;
        font-weight: bold;
        font-size: 12px;
        text-shadow: none;
        min-width: 100px;
        border-radius: 50px;
        line-height: 13px;
    }

    .table-title .add-new i {
        margin-right: 4px;
    }

    table.table {
        table-layout: fixed;
    }

    table.table tr th,
    table.table tr td {
        border-color: #e9e9e9;
    }

    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }

    table.table th:last-child {
        width: 100px;
    }

    table.table td a {
        cursor: pointer;
        display: inline-block;
        margin: 0 5px;
        min-width: 24px;
    }

    table.table td a.add {
        color: #27C46B;
    }

    table.table td a.edit {
        color: #FFC107;
    }

    table.table td a.delete {
        color: #E34724;
    }

    table.table td i {
        font-size: 19px;
    }

    table.table td a.add i {
        font-size: 24px;
        margin-right: -1px;
        position: relative;
        top: 3px;
    }

    table.table .form-control {
        height: 32px;
        line-height: 32px;
        box-shadow: none;
        border-radius: 2px;
    }

    table.table .form-control.error {
        border-color: #f50000;
    }

    table.table td .add {
        display: none;
    }
</style>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        var actions = $("table td:last-child").html();

        // Add row on add button click

        $(".add-new").click(function () {

            $("#current_state").val(0);
            $(this).attr("disabled", "disabled");
            var index = $("table tbody tr:last-child").index();

            var selOption = '';
            <?php foreach ($ArrStateOption as $key => $state) { ?>
                var is_selected = '';
                    // selOption = selOption + '<option value="<?php //echo $state; 
                        ?> "'+is_selected+'><?php //echo $state; 
                             ?></option > ';
                    <?php } ?>
            var ddData = '<select name="ArrState[]" class="form-control">' + selOption + '</select>';

        var ddData1 = '<select name="ArrCanDeliverPerishable[]" class="form-control"><option>Select</option><option value="Yes">Yes</option><option value="No">No</option></select>';
        var ddData2 = '<select name="ArrDeliveryTypes[]" class="form-control" onChange="setDay(this.value,000);"><option>Select</option><option value="Express Delivery">Express Delivery</option><option value="Same Day Delivery">Same Day Delivery</option><option value="Twise in a week">Twise in a week</option></select>';

        var style = 'display:none;';
        var ddData3 = '<select name="ArrDeliveryDays[]" class="form-control multiple_category_select days000" multiple="" style="' + style + '"><option>Select</option><option value="Monday">Monday</option><option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option><option value="Thursday">Thursday</option><option value="Friday">Friday</option><option value="Saturday">Saturday</option><option value="Sunday">Sunday</option></select>';


        var row = '<tr>' +
            '<td>' + ddData + '</td>' +
            '<td><input type="text" class="form-control" name="ArrStateData[]" id="zipcode"></td>' +
            '<td><input type="text" class="form-control" name="ArrStateData[]" id="area_name"></td>' +
            '<td><input type="text" class="form-control" name="ArrStateData[]" id="town_name"></td>' +
            '<td><input type="text" class="form-control" name="ArrStateData[]" id="minimum_order_value"></td>' +
            '<td>' + ddData1 + '</td>' +
            '<td>' + ddData2 + '</td>' +
            '<td>' + ddData3 + '</td>' +
            '<td>' + actions + '</td>' +
            '</tr>';
        $("table").append(row);
        $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
        $('[data-toggle="tooltip"]').tooltip();
        ajax_page_drop_down('multiple_category_select');
    });
    // Edit row on edit button click

    $(document).on("click", ".add", function () {
        var empty = false;
        var input = $(this).parents("tr").find('input[type="text"]');
        var select = $(this).parents("tr").find('select');
        var input_value = '';
        var number = 1;

        input.each(function () {
            if (!$(this).val()) {
                $(this).addClass("error");
                empty = true;
            } else {
                input_value = input_value + "&" + $(this).attr('name') + "=" + $(this).val();
                $(this).removeClass("error");
            }
            number++;
        });

        select.each(function () {
            if (!$(this).val()) {
                $(this).addClass("error");
                empty = true;
            } else {
                input_value = input_value + "&" + $(this).attr('name') + "=" + $(this).val();
                $(this).removeClass("error");
            }
            number++;
        });


        $(this).parents("tr").find(".error").first().focus();
        var colCount = 1;
        if (!empty) {
            input.each(function () {
                $(this).parent("td").html($(this).val());
                colCount++;
            });
            select.each(function () {
                if (colCount == 8) {
                    var dy = $(this).val();
                    let dy_text = dy.toString();
                    $(this).parent("td").html(dy_text);
                } else {
                    $(this).parent("td").html($(this).val());
                }
                colCount++;
            });
            $(this).parents("tr").find(".add, .edit").toggle();
            $(".add-new").removeAttr("disabled");
        }
        saveState(input_value);

    });
    $(document).on("click", ".edit", function () {
        // alert('edit');
        var current_state = $(this).attr("data");
        $("#current_state").val(current_state);
        var number = 1;
        var twise_in_week = '';
        $(this).parents("tr").find("td:not(:last-child)").each(function () {

                // if(number==1){     
                // 	var selOption = '';
                // 	<?php //foreach($ArrStateOption as $key=>$state) { 
                ?>
                //     var is_selected = ''; 
                //     if($(this).text()=='<?php //$state = str_replace("'", "", $state); echo $state; 
                ?> '){ is_selected=' selected'; }
                // 	selOption = selOption + '<option value="<?php //$state = str_replace("'", "", $state);
                //     echo $state; 
                ?> "'+is_selected+'><?php //echo $state; 
                 ?></option > ';
                    // 	<?php //} 
                    ?>
                //     var ddData = '<select name="ArrState[]" class="form-control">'+selOption+'</select>';
                //     $(this).html(ddData);
                // }
                if (number == 2) {
                $(this).html('<input type="text" name="ArrStateData[]" class="form-control" value="' + $(this).text() + '">');
            } else {
                // $(this).html('<input type="text" name="ArrZipCodeData[]" class="form-control" value="' + $(this).text() + '">');
            }
            number++;
        });
        $(this).parents("tr").find(".add, .edit").toggle();
        $(".add-new").attr("disabled", "disabled");
    });


    // Delete row on delete button click
    $(document).on("click", ".delete", function () {
        var zipcode_id = $(this).attr("data");
        $(this).parents("tr").remove();
        $(".add-new").removeAttr("disabled");
        deleteZIPCode(zipcode_id);
    });
    });

    function saveState(input_value) {
        var current_state = $("#current_state").val();
        // alert(current_state);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>save-state",
            data: "state_id=" + current_state + input_value,
            success: function (results) {
                toastr.success('A record has been saved successfully!');
            },
            error: function () {
                toastr.error('Oops...! record(s) added operation has been failed, please try again.');
            }
        });
    }
</script>
</head>

<body>
    <div class="container-lg">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-4">
                            <!-- <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Add New</button> -->
                            <input type="hidden" name="current_state" id="current_state" value="" />
                        </div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>State</th>
                            <th>Tax</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ArrState as $data) { ?>
                            <tr>
                                <td>
                                    <?php echo $data['state']; ?>
                                </td>
                                <td>
                                    <?php echo $data['tax']; ?>
                                </td>
                                <!-- <td><?php //echo $data['area_name']; 
                                    ?></td> -->

                                <td>
                                    <a data="<?php echo $data['state_id']; ?>" class="add" title="Add"
                                        data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                                    <a data="<?php echo $data['state_id']; ?>" class="edit" title="Edit"
                                        data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                                    <!-- <a data="<?php //echo $data['state_id']; ?>" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a> -->
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>