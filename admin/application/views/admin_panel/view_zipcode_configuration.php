<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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



  .table-responsive {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive .table-wrapper {
        min-width: 100%;
        padding: 20px;
    }

    .table-responsive table {
        width: 1800px;
        min-width: 1800px;
        table-layout: auto;
    }

    .table-responsive th,
    .table-responsive td {
        white-space: nowrap;
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

        margin: 0 0px;

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

    .date-range-icon {
        cursor: pointer;
        color: #007bff;
    }

    /* .daterangepicker {
    width: 430px !important;
    min-width: 430px !important;
}

.daterangepicker .drp-calendar {
    max-width: 200px !important;
    width: 200px !important;
}

.daterangepicker .drp-calendar.left {
    float: left !important;
}

.daterangepicker .drp-calendar.right {
    float: right !important;
} */

.daterangepicker .calendar-table {
    width: 100% !important;
}

.daterangepicker .calendar-table table {
    width: 100% !important;
}

.daterangepicker .calendar-table th,
.daterangepicker .calendar-table td {
    padding: 0 !important;
    width: 19px !important;
    min-width: 19px !important;
    max-width: 19px !important;
    height: 19px !important;
    line-height: 19px !important;
    font-size: 9px !important;
}
    

</style>

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Initialize DateRangePicker directly on the icon
    |--------------------------------------------------------------------------
    */

   $('.date-range-icon').each(function () {

    var $icon = $(this);

    var zipcode_id = $icon.attr('data-id');

    // Get database dates for this specific row
    var dbStartDate = $icon.attr('data-start-date');
    var dbEndDate   = $icon.attr('data-end-date');

    // Default picker options
    var pickerOptions = {

        autoUpdateInput: false,

        opens: 'left',

        drops: 'down',

        locale: {
            format: 'MM-DD-YYYY',
            cancelLabel: 'Clear',
            applyLabel: 'Apply'
        }
    };

    // If database dates exist, set them
    if (
        dbStartDate &&
        dbStartDate !== '0000-00-00' &&
        dbEndDate &&
        dbEndDate !== '0000-00-00'
    ) {

        pickerOptions.startDate = moment(dbStartDate, 'YYYY-MM-DD');
        pickerOptions.endDate   = moment(dbEndDate, 'YYYY-MM-DD');

    } else {

        // For empty database date
        pickerOptions.startDate = moment();
        pickerOptions.endDate   = moment();
        pickerOptions.minDate   = moment();

    }

    // Initialize daterangepicker
    $icon.daterangepicker(pickerOptions);


    // APPLY BUTTON
    $icon.on('apply.daterangepicker', function (ev, picker) {

        console.log('APPLY CLICKED');

        var zipcode_id = $(this).attr('data-id');
        var start_date = '';
        var end_date = '';

        if (picker.startDate && picker.endDate) {
            start_date = picker.startDate.format('YYYY-MM-DD');
            end_date   = picker.endDate.format('YYYY-MM-DD');
        }

        $.ajax({

            type: 'POST',

            url: '<?php echo base_url("save-holiday-date"); ?>',

            data: {
                zipcode_id: zipcode_id,
                start_date: start_date,
                end_date: end_date
            },

            dataType: 'json',

            beforeSend: function () {
                console.log('AJAX START');
            },

            success: function (response) {

                console.log('AJAX SUCCESS:', response);

                if (
                    response.status == true ||
                    response.status == 'true' ||
                    response.status == 1 ||
                    response.status == '1'
                ) {

                    toastr.success('Holiday date saved successfully!');

                    // Update the selected dates in HTML
                    $icon.attr('data-start-date', start_date);
                    $icon.attr('data-end-date', end_date);

                } else {

                    toastr.error(
                        response.message || 'Unable to save holiday date.'
                    );

                }

            },

            error: function (xhr, status, error) {

                // console.log('AJAX ERROR');
                // console.log('HTTP STATUS:', xhr.status);
                // console.log('STATUS:', status);
                // console.log('ERROR:', error);
                // console.log('RESPONSE:', xhr.responseText);

                toastr.error('Holiday Date save operation failed.');

            }

        });

    });


    // CANCEL BUTTON
    $icon.on('cancel.daterangepicker', function (ev, picker) {

        // Clear the input
        picker.element.val('');

        // Clear selected dates
        picker.setStartDate(moment());
        picker.setEndDate(moment());
        $.ajax({

            type: 'POST',

            url: '<?php echo base_url("save-holiday-date"); ?>',

            data: {
                zipcode_id: zipcode_id,
                start_date: '',
                end_date: ''
            },

            dataType: 'json',

            beforeSend: function () {
                console.log('AJAX START');
            },

            success: function (response) {

                console.log('AJAX SUCCESS:', response);

                if (
                    response.status == true ||
                    response.status == 'true' ||
                    response.status == 1 ||
                    response.status == '1'
                ) {

                    toastr.success('Holiday date saved successfully!');

                } else {

                    toastr.error(
                        response.message || 'Unable to save holiday date.'
                    );

                }

            },

            error: function (xhr, status, error) {

                // console.log('AJAX ERROR');
                // console.log('HTTP STATUS:', xhr.status);
                // console.log('STATUS:', status);
                // console.log('ERROR:', error);
                // console.log('RESPONSE:', xhr.responseText);

                toastr.error('Holiday Date save operation failed.');

            }

        });

    });

});


        $('[data-toggle="tooltip"]').tooltip();

        var actions = $("table td:last-child").clone();
        actions.find(".date-range-icon").remove();
        actions.find(".daterange").remove();
        actions = actions.html();

        // Append table with add row form on add new button click

        $(".add-new").click(function () {

            window.scrollTo(0, document.body.scrollHeight);



            $("#current_zip_code").val(0);

            $(this).attr("disabled", "disabled");

            var index = $("table tbody tr:last-child").index();



            var selOption = '';

            <?php foreach ($ArrStateOption as $key => $state) { ?>

                var is_selected = '';

                selOption = selOption + '<option value="<?php echo $state; ?>"' + is_selected + '><?php echo $state; ?></option>';

        <?php } ?>

        var ddData = '<select name="ArrState[]" class="form-control" autofocus>' + selOption + '</select>';



        var ddData1 = '<select name="ArrCanDeliverPerishable[]" class="form-control"><option value="">Select</option><option value="Yes">Yes</option><option value="No">No</option></select>';

        var ddData2 = '<select name="ArrDeliveryTypes[]" class="form-control" onChange="setDay(this.value,000);"><option value="">Select</option><option value="Express Delivery">Express Delivery</option><option value="Same Day Delivery">Same Day Delivery</option><option value="Twise in a week">Twise in a week</option></select>';

        

        var style = 'display:none;';

        var ddData3 = '<select name="ArrDeliveryDays[]" class="form-control multiple_category_select days000 skip-validation" multiple="" style="' + style + '"><option value="">Select</option><option value="Monday">Monday</option><option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option><option value="Thursday">Thursday</option><option value="Friday">Friday</option><option value="Saturday">Saturday</option><option value="Sunday">Sunday</option></select>';

        var ddData4 = '<select name="ArrCanDeliverLiker[]" class="form-control skip-validation"><option value="">Select</option><option value="Yes">Yes</option><option value="No">No</option></select>';
        var ddData5 = '<select name="ArrCanDeliverCookFood[]" class="form-control skip-validation"><option value="">Select</option><option value="Yes">Yes</option><option value="No">No</option></select>';

        //get timeslot list
        var timeOptions = '<option value="">Select Time</option>';

        var startHour = 8;
        var endHour = 24;

        for (var hour = startHour; hour < endHour; hour++) {

            // 30-minute interval from 8 AM through 8 PM
            var minutesList = (hour <= 20) ? [0, 30] : [0];

            for (var i = 0; i < minutesList.length; i++) {

                var minutes = minutesList[i];

                var hour24 = String(hour).padStart(2, '0');
                var minute = String(minutes).padStart(2, '0');

                var value = hour24 + ':' + minute;

                var period = hour < 12 ? 'AM' : 'PM';

                var hour12 = hour % 12;
                hour12 = hour12 === 0 ? 12 : hour12;

                var displayTime =
                    String(hour12).padStart(2, '0') +
                    ':' +
                    minute +
                    ' ' +
                    period;

                timeOptions +=
                    '<option value="' + value + '">' +
                    displayTime +
                    '</option>';
            }
        }

        var cuttoff_time_list =
            '<select name="ArrDeliveryCutoff[]" class="form-control" onchange="setDay(this.value,000);">' +
                timeOptions +
            '</select>';


        //get zone list
        var zoneselOption = '';
        zoneselOption = '<option value="">Select Zone</option>';

            <?php if(count($ArrZonelist) > 0){
                foreach ($ArrZonelist as $key => $zonedata) { ?>

                var is_selected = '';

                zoneselOption = zoneselOption + '<option value="<?php echo $zonedata['zone_id']; ?>"' + is_selected + '><?php echo $zonedata['title']; ?></option>';

        <?php } }?>

        var zonedata = '<select name="ArrZone[]" class="form-control skip-validation" autofocus>' + zoneselOption + '</select>';


        var row = '<tr>' +

            '<td>' + ddData + '</td>' +

            '<td><input type="text" class="form-control" name="ArrZipCodeData[]" id="zipcode"></td>' +

            '<td><input type="text" class="form-control" name="ArrZipCodeData[]" id="area_name"></td>' +

            '<td><input type="text" class="form-control" name="ArrZipCodeData[]" id="town_name"></td>' +

            '<td><input type="text" class="form-control" name="ArrZipCodeData[]" id="minimum_order_value"></td>' +

            '<td>' + ddData1 + '</td>' +

            '<td>' + ddData4 + '</td>' +

            '<td>' + ddData5 + '</td>' +

            '<td>' + ddData2 + '</td>' +

            '<td>' + ddData3 + '</td>' +

            '<td>' + cuttoff_time_list + '</td>' +

            '<td>' + zonedata + '</td>' +

            '<td>' + actions + '</td>' +

            '</tr>';

        $("table").append(row);

        $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();

        $('[data-toggle="tooltip"]').tooltip();

        ajax_page_drop_down('multiple_category_select');

    });



    // Add row on add button click

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
                if ($(this).hasClass('skip-validation')) {
                    $(this).removeClass("error");
                }else{
                    $(this).addClass("error");

                    empty = true;
                }
                

            } else {

                input_value = input_value + "&" + $(this).attr('name') + "=" + $(this).val();
  var value = $(this).val();
    var text = $(this).find("option:selected").text();

    if ($(this).attr("name") == "ArrDeliveryCutoff[]") {
        $(this).parent("td").html(text);
    }if ($(this).attr("name") == "ArrZone[]") {
        $(this).parent("td").html(text);
    }if ($(this).attr("name") == "ArrDeliveryDays[]") {
        var changetext = text.replace(/([a-z])([A-Z])/g, '$1 <br> $2');
        console.log(changetext);
        $(this).parent("td").html(changetext);
    }
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

                }

                else {

                    $(this).parent("td").html($(this).val());

                }

                colCount++;

            });

            $(this).parents("tr").find(".add, .edit").toggle();

            $(".add-new").removeAttr("disabled");

        }

        saveZIPCode(input_value);

    });





    // Edit row on edit button click

    $(document).on("click", ".edit", function () {



        var current_zip_code = $(this).attr("data");

		

        $("#current_zip_code").val(current_zip_code);

        var number = 1;

        var twise_in_week = '';

        $(this).parents("tr").find("td:not(:last-child)").each(function () {


            if (number == 1) {

                var selOption = '';

                <?php foreach ($ArrStateOption as $key => $state) { ?>

                    var is_selected = '';

                        if ($(this).text().trim() == '<?php echo $state; ?>') { is_selected = ' selected'; }

                        selOption = selOption + '<option value="<?php echo $state; ?>"' + is_selected + '><?php echo $state; ?></option>';

                <?php } ?>

                var ddData = '<select name="ArrState[]" class="form-control">' + selOption + '</select>';

                $(this).html(ddData);

            }

            else if (number == 6 || number == 7 || number == 8) {

                var yes_chk = '';

                var no_chk = '';

                if ($(this).text().trim() == 'Yes') { yes_chk = ' selected'; }

                else if ($(this).text().trim() == 'No') { no_chk = ' selected'; }

                
                if(number == 6){
                    var InputtName = 'ArrCanDeliverPerishable[]';
                } else if(number == 7){
                    var InputtName = 'ArrCanDeliverLiker[]';
                } else if(number == 8){
                    var InputtName = 'ArrCanDeliverCookFood[]';
                }

                var ddData = '<select name="' + InputtName + '" class="form-control skip-validation"><option value="">Select</option><option value="Yes"' + yes_chk + '>Yes</option><option value="No"' + no_chk + '>No</option></select>';

                $(this).html(ddData);

            } else if (number == 9) {

                var option1_chk = '';

                var option2_chk = '';

                var option3_chk = '';

                twise_in_week = $(this).text().trim();

                if ($(this).text().trim() == 'Express Delivery') { option1_chk = ' selected'; }

                else if ($(this).text().trim() == 'Same Day Delivery') { option2_chk = ' selected'; }

                else if ($(this).text().trim() == 'Twise in a week') { option3_chk = ' selected'; }

                var ddData = '<select name="ArrDeliveryTypes[]" class="form-control" onChange="setDay(this.value,' + current_zip_code + ');"><option value="">Select</option><option value="Express Delivery"' + option1_chk + '>Express Delivery</option><option value="Same Day Delivery"' + option2_chk + '>Same Day Delivery</option><option value="Twise in a week"' + option3_chk + '>Twise in a week</option></select>';

                $(this).html(ddData);

            } else if (number == 10) {
                var m_chk = '';

                var t_chk = '';

                var w_chk = '';

                var th_chk = '';

                var f_chk = '';

                var st_chk = '';

                var s_chk = '';

                days = $(this).text().trim();

                const dayArray = days.split(" ");

                if (dayArray.indexOf("Monday") > -1) { m_chk = ' selected'; }

                if (dayArray.indexOf("Tuesday") > -1) { t_chk = ' selected'; }

                if (dayArray.indexOf("Wednesday") > -1) { w_chk = ' selected'; }

                if (dayArray.indexOf("Thursday") > -1) { th_chk = ' selected'; }

                if (dayArray.indexOf("Friday") > -1) { f_chk = ' selected'; }

                if (dayArray.indexOf("Saturday") > -1) { st_chk = ' selected'; }

                if (dayArray.indexOf("Sunday") > -1) { s_chk = ' selected'; }

                var style = 'display:none;';

                if (twise_in_week == 'Twise in a week') { style = 'display:block;'; }

                var ddData3 = '<select name="ArrDeliveryDays[]" class="form-control skip-validation multiple_category_select days' + current_zip_code + '" multiple="" style="' + style + '"><option value="">Select</option><option value="Monday"' + m_chk + '>Monday</option><option value="Tuesday"' + t_chk + '>Tuesday</option><option value="Wednesday"' + w_chk + '>Wednesday</option><option value="Thursday"' + th_chk + '>Thursday</option><option value="Friday"' + f_chk + '>Friday</option><option value="Saturday"' + st_chk + '>Saturday</option><option value="Sunday"' + s_chk + '>Sunday</option></select>';

                $(this).html(ddData3);

                ajax_page_drop_down('multiple_category_select');
                
            } else if (number == 11){
                
                //get time lsot
                var timeOptions = '<option value="">Select Time</option>';

                for (var hour = 0; hour < 24; hour++) {

                    // 30-minute interval from 08:00 to 20:00
                    var minutesList = (hour >= 8 && hour <= 20) ? [0, 30] : [0];

                    for (var i = 0; i < minutesList.length; i++) {

                        var minutes = minutesList[i];

                        var hour24 = String(hour).padStart(2, '0');
                        var minute = String(minutes).padStart(2, '0');

                        var value = hour24 + ':' + minute;

                        // Display as 12-hour format
                        var period = hour < 12 ? 'AM' : 'PM';
                        var hour12 = hour % 12;
                        hour12 = hour12 === 0 ? 12 : hour12;

                        var displayTime =
                            String(hour12).padStart(2, '0') +
                            ':' +
                            minute +
                            ' ' +
                            period;

                        var option_chk = '';

                        if ($(this).text().trim() == displayTime) {
                            option_chk = ' selected';
                        }

                        timeOptions +=
                            '<option value="' + value + '"' + option_chk + '>' +
                            displayTime +
                            '</option>';
                    }
                }
                

                var cutofftimeslot_list =
                    '<select name="ArrDeliveryCutoff[]" class="form-control">' +
                        timeOptions +
                    '</select>';
                    
                $(this).html(cutofftimeslot_list);

            }else if (number == 12) {

                $(this).html($(this).text().trim());

            }else {

                $(this).html('<input type="text" name="ArrZipCodeData[]" class="form-control" value="' + $(this).text().trim() + '">');

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

    function setDay(day, current_zip_code) {

        if (day == 'Twise in a week') {

            $(".days" + current_zip_code).show();

        }

        else {

            $(".days" + current_zip_code).hide();

        }

    }

    function saveZIPCode(input_value) {

        var current_zip_code = $("#current_zip_code").val();

        $.ajax({

            type: "POST",

            url: "<?php echo base_url() ?>save-zipcode",

            data: "zipcode_id=" + current_zip_code + input_value,

            success: function (results) {

                toastr.success('A record has been saved successfully!');

            },

            error: function () {

                toastr.error('Oops...! record(s) added operation has been failed, please try again.');

            }

        });

    }

    function deleteZIPCode(zipcode_id) {

        $.ajax({

            type: "POST",

            url: "<?php echo base_url() ?>delete-zipcode",

            data: "zipcode_id=" + zipcode_id,

            success: function (results) {

                toastr.success('A record has been deleted successfully!');

            },

            error: function () {

                toastr.error('Oops...! record(s) deletion operation has been failed, please try again.');

            }

        });

    }

</script>

</head>



<body>
<?php 
$timeslotlist = get_timeslot_list(); ?>
    <div class="container-lg">

        <div class="table-responsive">

            <div class="table-wrapper">

                <div class="table-title">
                <div class="row">

                    <form id="frmListDataFilter" action="<?php echo SITE_URL ?>zipcode-configuration" method="post">

                    <div class="col-sm-2">
                        <input size="15" type="text" value="<?php echo $txtSearchZipcode; ?>" class="extra_field form-control" name="txtSearchZipcode" id="txtSearchZipcode" placeholder="Search By Zipcode" />
                    </div>
                    <div class="col-sm-2">
                        <select class="extra_field form-control" name="txtSearchState" id="txtSearchState">
                            <option value="">Search By State</option>
                            <?php if(!empty($ArrStateOption)) { 
                                $cnt = 0;
                                foreach ($ArrStateOption as $key => $state) {
                                    $is_selected = "";
                                    if($state == $txtSearchState){
                                        $is_selected = "selected";
                                    }
                                    if($cnt != 0){
                            ?>

                            <option <?php echo  $is_selected; ?> value="<?php echo $state; ?>"><?php echo $state; ?></option>
                            <?php } $cnt++; $is_selected = "";} } ?>

                        </select>
                    </div>
                    <div class="col-sm-2">
                        <input size="15" type="text" class="extra_field form-control" value="<?php echo $txtSearchArea; ?>" name="txtSearchArea" id="txtSearchArea" placeholder="Search By Area" />
                    </div>
                    <div class="col-sm-2">

                        <input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit" value="Search">

                        <a href="<?php echo SITE_URL ?>zipcode-configuration"  class="btn btn-info">Reset</a>

                    </div>

                    </form>

                    <div class="col-sm-2">

                    <!--<input type="submit" class="btn btn-warning" name="searchExport" id="searchExport" value="Export to CSV">-->

                    <input type="hidden" value="<?php echo SITE_URL ?>adminpanel/controller_category/category_list_export"

                        id="searchExportaction">

                    </div>

                    </div>
                    <div class="row">

                        <div class="col-sm-8"></div>

                        <div class="col-sm-4">

                            <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Add

                                New</button>

                            <input type="hidden" name="current_zip_code" id="current_zip_code" value="" />

                        </div>

                    </div>

                </div>
                <div class="table-responsive">

                    <div class="table-wrapper">
                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th>State</th>

                                    <th>ZIP Code</th>

                                    <th>Area</th>

                                    <th>Town Name</th>

                                    <th>Minimum Order Value</th>

                                    <th>Deliver Perishable Products?</th>

                                    <th>Deliver Liquor Products?</th>

                                    <th>Deliver Cook Food Products?</th>

                                    <th>Delivery Types</th>

                                    <th>Delivery Days</th>

                                    <th>CutOff Time</th>

                                    <th>Zone</th>

                                    <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($Arrzipcode as $data) { ?>

                                    <tr>

                                        <td>

                                            <?php echo $data['state']; ?>

                                        </td>

                                        <td>

                                            <?php echo $data['zipcode']; ?>

                                        </td>

                                        <td>

                                            <?php echo $data['area_name']; ?>

                                        </td>

                                        <td>

                                            <?php echo $data['town_name']; ?>

                                        </td>

                                        <td>

                                            <?php echo $data['minimum_order_value']; ?>

                                        </td>

                                        <td>

                                            <?php echo $data['can_deliver_perishable_products']; ?>

                                        </td>

                                        <td>

                                            <?php echo $data['can_deliver_liker_products']; ?>

                                        </td>

                                        <td>

                                            <?php echo $data['can_deliver_cook_food_products']; ?>

                                        </td>

                                        <td>

                                            <?php echo $data['delivery_types']; ?>

                                        </td>

                                        <td>
                                            <?php if($data['delivery_days'] != '') { ?>
                                                <?= str_replace(',', ' <br>', $data['delivery_days']); ?>
                                            <?php } ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if(!empty($data['cutoff_time'])){
                                                $formattedTime = date('h:i A', strtotime($data['cutoff_time'])); 
                                                echo $formattedTime;
                                            }
                                            //    
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            $zone_name = getZonenameById($data['zone_id']);
                                            echo $zone_name; ?>

                                        </td>

                                        <td class="zipcode_icon_list">

                                            <a data="<?php echo $data['zipcode_id']; ?>" class="add" title="Add"

                                                data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>

                                            <a data="<?php echo $data['zipcode_id']; ?>" class="edit" title="Edit"

                                                data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                                            <!-- start holiday date code -->
                                            <?php if(!empty($data['zipcode_id'])){ ?>
                                                <a href="javascript:void(0);"
                                                    class="date-range-icon"
                                                    data-id="<?php echo $data['zipcode_id']; ?>"
                                                    data-start-date="<?php echo $data['holiday_start_date'] ?>"
                                                    data-end-date="<?php echo $data['holiday_end_date'] ?>"
                                                    title="Select Holiday Date">
                                                        <i class="material-icons">date_range</i>
                                                </a>
                                            <?php } ?>
                                            <!-- end holiday date code -->
                                            <a data="<?php echo $data['zipcode_id']; ?>" class="delete" title="Delete"

                                                data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                                            

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>
                    </div>

                </div>

            </div>

        </div>

    </div>