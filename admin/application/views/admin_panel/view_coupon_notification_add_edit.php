<style>
    .tt-suggestion {
        background-color: #efefef;
        margin-top: 5px;
        padding: 5px;
        border: 1px solid;
        width: 100%;
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
                            $attributes = array('class' => 'form-horizontal', 'id' => 'coupon_notif_form', 'role' => 'form', 'enctype' => 'multipart/form-data');
                            $couponnotiid = (!empty($edit_id) && $edit_id > 0) ? $edit_id : '';
                            if (!empty($couponnotiid)) {
                                echo form_open('coupon-notification/update/' . $couponnotiid, $attributes);
                            } else {
                                echo form_open('coupon-notification/add', $attributes);
                            }
                            ?>
                            <input type="hidden" name="prod_id" id="prod_id" value="<?php echo $couponnotiid; ?>" />
                            <!-- text input -->
                            <div class="box_bg_color">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Coupon Code <span class="red">*</span>: </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div>
                                                <input type="text" placeholder="Code" class="form-control" id="code" name="code" value="<?php echo (!empty($edit_id)) ? $product['code'] : ''; ?>" required>
                                                <?php echo form_error('code'); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Coupon Title <span class="red">*</span>: </label>
                                        </div>
                                        <div class="col-sm-9">
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
                                            <label>Coupon Details <span class="red">*</span>: </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div>
                                                <textarea placeholder="Details" class="form-control" name="details" id="details"><?php echo (!empty($edit_id)) ? $product['details'] : ''; ?></textarea>
                                                <?php echo form_error('details'); ?>
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
                        $(function() {
                            // 1. Initialize CKEditor
                            var editor = CKEDITOR.replace('details');
                            
                            // 2. Force CKEditor to update the hidden textarea on change
                            editor.on('change', function() {
                                editor.updateElement();
                                // Optional: Trigger validation instantly as the user types
                                $("#details").valid(); 
                            });

                            // (Kept from your original code if you are using it elsewhere)
                            $(".textarea").wysihtml5(); 
                        });

                        $(document).ready(function() {
                            var prod_id = $('#prod_id').val();

                            $("#coupon_notif_form").validate({
                                ignore: [],
                                rules: {
                                    title: {
                                        required: true
                                    },
                                    details: {
                                        required: true,
                                    },
                                },
                                messages: {
                                    title: {
                                        required: "The field is required"
                                    },
                                    details: {
                                        required: "The field is required"
                                    },

                                },
                                errorPlacement: function(error, element) {
                                    if (element.attr("name") == "details") {
                                        // Find the CKEditor container container and place the error after it
                                        error.insertAfter("#cke_details");
                                    } else {
                                        // Default placement for other fields (like title)
                                        error.insertAfter(element);
                                    }
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