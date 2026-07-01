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
                            $attributes = array('class' => 'form-horizontal', 'id' => 'product_coming_soon_form', 'role' => 'form', 'enctype' => 'multipart/form-data');
                            $comingsoon_id = (!empty($edit_id) && $edit_id > 0) ? $edit_id : '';
                            if (!empty($comingsoon_id)) {
                                echo form_open('product/coming-soon/update/' . $comingsoon_id, $attributes);
                            } else {
                                echo form_open('product/coming-soon/add', $attributes);
                            }
                            ?>
                            <input type="hidden" name="prod_id" id="prod_id" value="<?php echo $comingsoon_id; ?>" />
                            <!-- text input -->
                            <div class="box_bg_color">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Product Name <span class="red">*</span>: </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div>
                                                <input type="text" placeholder="Name" class="form-control" id="product_name" name="product_name" value="<?php echo (!empty($edit_id)) ? $product['product_name'] : ''; ?>" required>
                                                <?php echo form_error('product_name'); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Product Details <span class="red">*</span>: </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div>
                                                <textarea placeholder="Details" class="form-control" name="product_details" id="product_details"><?php echo (!empty($edit_id)) ? $product['product_details'] : ''; ?></textarea>
                                                <?php echo form_error('product_details'); ?>
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
                            var editor = CKEDITOR.replace('product_details');
                            
                            // 2. Force CKEditor to update the hidden textarea on change
                            editor.on('change', function() {
                                editor.updateElement();
                                // Optional: Trigger validation instantly as the user types
                                $("#product_details").valid(); 
                            });

                            // (Kept from your original code if you are using it elsewhere)
                            $(".textarea").wysihtml5(); 
                        });

                        $(document).ready(function() {
                            var prod_id = $('#prod_id').val();

                            $("#product_coming_soon_form").validate({
                                ignore: [],
                                rules: {
                                    product_name: {
                                        required: true
                                    },
                                    product_details: {
                                        required: true,
                                    },
                                },
                                messages: {
                                    product_name: {
                                        required: "The field is required"
                                    },
                                    product_details: {
                                        required: "The field is required"
                                    },

                                },
                                errorPlacement: function(error, element) {
                                    if (element.attr("name") == "product_details") {
                                        // Find the CKEditor container container and place the error after it
                                        error.insertAfter("#cke_product_details");
                                    } else {
                                        // Default placement for other fields (like product_name)
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