<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<style>
    .tt-suggestion {
        background-color: #efefef;
        margin-top: 5px;
        padding: 5px;
        border: 1px solid;
        width: 100%;
    }
    /* ZIPCODE BOX */
    .zipcode-box {
        border: 1px solid #d9dee5;
        border-radius: 6px;
        background: #fff;
        overflow: hidden;
    }

    /* Header */
    .zipcode-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 12px 15px;
        background: #f7f9fb;
        border-bottom: 1px solid #e1e5ea;
    }

    .zipcode-search {
        width: 300px;
    }

    .zipcode-search input {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 7px 12px;
        font-size: 14px;
    }

    .zipcode-search input:focus {
        border-color: #80bdff;
        outline: none;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, .1);
    }

    .zipcode-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .select-all-label {
        margin: 0;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
    }

    .select-all-label input {
        margin-right: 6px;
        vertical-align: middle;
    }

    .zipcode-count {
        background: #e9f2ff;
        color: #2166c1;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ZIPCODE LIST */
    .zipcode-list {
        height: 100px;
        overflow-y: auto;
        padding: 15px;

        display: grid;
        grid-template-columns: repeat(4, minmax(120px, 1fr));
        gap: 8px 12px;
    }

    /* Individual zipcode */
    .zipcode-item {
        display: flex;
        align-items: center;
        gap: 8px;

        margin: 0;
        padding: 9px 10px;

        border: 1px solid #e2e6ea;
        border-radius: 4px;

        background: #fff;
        cursor: pointer;

        transition: all .15s ease;
    }

    .zipcode-item:hover {
        background: #f5f9ff;
        border-color: #9ec5fe;
    }

    .zipcode-item input {
        width: 16px;
        height: 16px;
        margin: 0;
        cursor: pointer;
    }

    .zipcode-number {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    /* Selected zipcode */
    .zipcode-item:has(input:checked) {
        background: #eef6ff;
        border-color: #4d9bea;
    }

    .zipcode-item:has(input:checked) .zipcode-number {
        color: #1769aa;
    }

    /* Scrollbar */
    .zipcode-list::-webkit-scrollbar {
        width: 7px;
    }

    .zipcode-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .zipcode-list::-webkit-scrollbar-thumb {
        background: #c5cbd3;
        border-radius: 10px;
    }

    .zipcode-list::-webkit-scrollbar-thumb:hover {
        background: #9da5ae;
    }

    /* Required */
    .required {
        color: #e3342f;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box1">
                <div class="box-info">
                    <div class="box-text box-body">
                        <div class="box-body">
                            <?php
                            $attributes = array('class' => 'form-horizontal', 'id' => 'zone_form', 'role' => 'form', 'enctype' => 'multipart/form-data');
                            $zone_id = (!empty($edit_id) && $edit_id > 0) ? $edit_id : '';
                            if (!empty($zone_id)) {
                                echo form_open('zone/update/' . $zone_id, $attributes);
                            } else {
                                echo form_open('zone/add', $attributes);
                            }
                            ?>
                            <input type="hidden" name="zone_id" id="zone_id" value="<?php echo $zone_id; ?>" />
                            <!-- text input -->
                            <div class="box_bg_color">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Zone Title <span class="red">*</span>: </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div>
                                                <input type="text" placeholder="Name" class="form-control" id="title" name="title" value="<?php echo (!empty($edit_id)) ? $product['title'] : ''; ?>" required>
                                                <?php echo form_error('title'); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>CuttOff Time <span class="red">*</span>: </label>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="cutoff_time" class="form-control">
                                                <option value="">Select Time</option>
                                                <?php $get_timeslot_list = get_timeslot_list();
                                                
                                                if(count($get_timeslot_list) > 0){
                                                    foreach($get_timeslot_list as $key => $val){
                                                        $is_selected = ''; 
                                                        if (!empty($product['cutoff_time']) &&
                                                            $val['value'] == $product['cutoff_time']) {
                                                            $is_selected = 'selected';
                                                        }?>
                                                        <option value="<?php echo $val['value']; ?>" <?php echo $is_selected; ?>><?php echo $val['display']; ?></option>
                                                    <?php }
                                                } ?>
                                            
                                            </select>

                                            <?php echo form_error('cutoff_time'); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Date <span class="red">*</span>: </label>
                                        </div>
                                        <div class="col-md-3">

                                            <input type="text"
                                                placeholder="Date"
                                                name="holiday_date"
                                                id="holiday_date"
                                                value="<?php
                                                if (!empty($product['holiday_start_date']) && !empty($product['holiday_end_date'])) {
                                                    echo date('m-d-Y', strtotime($product['holiday_start_date'])) . ' - ' .
                                                        date('m-d-Y', strtotime($product['holiday_end_date']));
                                                } 
                                                ?>"
                                                class="daterange_picker_bottom_left form-control"
                                                autocomplete="off">

                                            <?php echo form_error('holiday_date'); ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Zipcode <span class="red">*</span>: </label>
                                        </div>
                                        <div class="col-md-9">

                                            <?php
                                            $selected_zipids = !empty($product['zipcode_ids'])
                                                ? explode(',', $product['zipcode_ids'])
                                                : [];
                                            ?>

                                            <div class="zipcode-box">

                                                <!-- Search + Select All -->
                                                <div class="zipcode-header">

                                                    <div class="zipcode-search">
                                                        <input type="text"
                                                            id="zipcodeSearch"
                                                            class="form-control"
                                                            placeholder="Search zipcode...">
                                                    </div>


                                                </div>

                                                <!-- Zipcode List -->
                                                <div class="zipcode-list" id="zipcodeList">

                                                    <?php foreach ($zipcodelist as $zip) {

                                                        $is_checked = in_array(
                                                            $zip['zipcode_id'],
                                                            $selected_zipids
                                                        );
                                                    ?>

                                                        <label class="zipcode-item"
                                                            data-zipcode="<?php echo htmlspecialchars($zip['zipcode']); ?>">

                                                            <input type="checkbox"
                                                                class="zipcode-checkbox"
                                                                name="zipcode_ids[]"
                                                                value="<?php echo $zip['zipcode_id']; ?>"
                                                                data-zone-id="<?php echo $zip['zipcode_id']; ?>"
                                                                <?php echo $is_checked ? 'checked' : ''; ?>>

                                                            <span class="zipcode-number">
                                                                <?php echo htmlspecialchars($zip['zipcode']); ?>
                                                            </span>

                                                        </label>

                                                    <?php } ?>

                                                </div>

                                            </div>

                                        </div>
                                  
                                    </div>
                                </div>

                          

                            <div class="clearfix"></div>
                            <div class="col-md-3 col-md-offset-3">
                                <div>
                                    <input type="hidden" value="<?php echo (isset($edit_id) && $edit_id > 0) ? $edit_id : ''; ?>" name="mockup_and_template_id">
                                    <button type="submit" class="btn btn-default" name="submit" id="submit" value="<?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?>" <?php echo 'name="save_user"'; ?>><?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?></button>
                                    <button type="reset" value="Reset" class="btn btn-info">Cancel</button>
                                </div>
                            </div>

                            </form>

                        </div>

                        
                    </div>
                    <!-- box-body -->

                    <script>
                       function searchCheckboxes() {

                            var search = $('#search').val().toLowerCase();

                            $('.checkbox-item').each(function () {

                                var text = $(this).text().toLowerCase();

                                if (text.indexOf(search) !== -1) {
                                    $(this).show();
                                } else {
                                    $(this).hide();
                                }

                            });
                        }
                        $(document).on('change', '.zipcode-checkbox', function () {

                            var checkbox = $(this);
                            var zipcode_id = checkbox.val();
                            var zone_id = checkbox.data('zone-id');

                            // Only validate when checking
                            if (!checkbox.is(':checked')) {
                                return;
                            }

                            $.ajax({
                                url: '<?php echo base_url("zone/check_zipcode_used"); ?>',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    zipcode_id: zipcode_id,
                                    zone_id: zone_id
                                },
                                success: function (response) {

                                    if (response.status == false) {

                                        alert(response.message);

                                        // Uncheck zipcode
                                        checkbox.prop('checked', false);

                                    }

                                },
                                error: function (xhr) {
                                    console.log(xhr.responseText);

                                    // Safety: uncheck if validation failed
                                    checkbox.prop('checked', false);
                                }
                            });

                        });
                        $(document).ready(function () {

                            // Search zipcode
                            $('#zipcodeSearch').on('keyup', function () {

                                var search = $(this).val().toLowerCase().trim();

                                $('.zipcode-item').each(function () {

                                    var zipcode = $(this)
                                        .data('zipcode')
                                        .toString()
                                        .toLowerCase();

                                    if (zipcode.indexOf(search) !== -1) {

                                        $(this).show();

                                    } else {

                                        $(this).hide();

                                    }

                                });

                            });


                           var $datePicker = $('.daterange_picker_bottom_left');

                            $datePicker.daterangepicker({
                                autoUpdateInput: false,
                                showDropdowns: true,
                                linkedCalendars: false,
                                opens: 'left',
                                minDate: moment(), // Disable previous dates
                                locale: {
                                    format: 'MM-DD-YYYY',
                                    separator: ' - ',
                                    applyLabel: 'Apply',
                                    cancelLabel: 'Cancel'
                                }
                            });

                            // When user clicks Apply
                            $datePicker.on('apply.daterangepicker', function (ev, picker) {

                                $(this).val(
                                    picker.startDate.format('MM-DD-YYYY') +
                                    ' - ' +
                                    picker.endDate.format('MM-DD-YYYY')
                                );

                            });

                            // When user clicks Cancel
                            $datePicker.on('cancel.daterangepicker', function () {
                                $(this).val('');
                            });
                            var zone_id = $('#zone_id').val();

                            $("#zone_form").validate({
                                ignore: [],
                                rules: {
                                    title: {
                                        required: true
                                    },
                                },
                                messages: {
                                    title: {
                                        required: "The field is required"
                                    }

                                },
                                errorPlacement: function(error, element) {
                                    
                                        // Default placement for other fields (like title)
                                        error.insertAfter(element);
                                    
                                },
                                submitHandler: function(form) {
                                    form.submit();
                                }
                            });
                        });
                    </script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>

                </div>
            </div>
        </div>
    </div>
</div>