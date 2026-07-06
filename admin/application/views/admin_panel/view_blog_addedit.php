<div class="row">

  <div class="col-md-12">

    <div class="box box-primary">



      <form role="form" method="post" action="<?php echo SITE_URL ?>adminpanel/controller_blog/save"

        enctype='multipart/form-data'>

        <input type="hidden" name="current_blog_image"

          value="<?php echo (isset($ArrFieldData['blog_image'])) ? trim($ArrFieldData['blog_image']) : ''; ?>" />

        <div class="box-body">

          <div class="form-group">

            <label for="exampleInputEmail1">Blog Title</label>

            <input type="text" class="form-control" id="blogtitle" name="blogtitle" placeholder="Title"

              onKeyUp="convertToSlug();"

              value="<?php echo (isset($ArrFieldData['blog_title'])) ? trim($ArrFieldData['blog_title']) : ''; ?>">

            <div class="alert alert-danger" role="alert" style="display:none"></div>

            <input type="hidden" name="blog_id" id="blog_id"

              value="<?php echo (isset($ArrFieldData['blog_id'])) ? trim($ArrFieldData['blog_id']) : ''; ?>">

          </div>
          <?php

          $category_id = $ArrFieldData['category_id'] ?? '0';

          $options_category = array();
                     if (isset($category) && count($category) > 0)
                      $options_category[''] = 'Select Category';
                        foreach ($category as $row) {
                           $options_category[$row['category_id']] = $row['category_name'];
                        }
                        ?>

          <div class="form-group">

            <label for="exampleInputEmail1">Blog Slug</label>

            <input type="hidden" name="check_url" id="check_url"

              value="<?php echo base_url() ?>adminpanel/controller_blog/ajaxCheckUrl">

            <input type="text" class="form-control" id="blog_slug" name="blog_slug" placeholder="Slug"

              value="<?php echo (isset($ArrFieldData['blog_slug'])) ? trim($ArrFieldData['blog_slug']) : ''; ?>">

            <div class="alert alert-danger" role="alert" style="display:none"></div>

          </div>

          <div class="form-group">

            <label>Category<span class="red">*</span></label>
            <?php
            $attributes = array('id' => 'category_id', 'class' => 'form-control');
            echo form_dropdown('category_id', $options_category, $category_id, $attributes);
            ?>
            <label for="category_iderror" id="category_iderror" class="error"></label>

          </div>

          
          <div class="form-group">

            <label for="exampleInputEmail1">Featured Image</label>

            <?php if (isset($ArrFieldData['blog_image']) && $ArrFieldData['blog_image'] != '') { ?>

              <img height="25px" width="25px"

                src='<?php echo base_url(); ?>uploads/blog/<?php echo $ArrFieldData['blog_image'] ?>' border=0>

              <input type="file" class="" name="blog_image" accept="image/*" data-type="image">

            <?php } else { ?>

              <input type="file" class="form-control" name="blog_image" accept="image/*" data-type="image">

            <?php } ?>

            <?php echo form_error('blog_image'); ?>

          </div>

          <div class="form-group">

            <label for="exampleInputEmail1">Table Of Content</label>

            <div class="box-body pad">

              <textarea class="form-control" id="table_of_content" name="table_of_content" rows="5">

                    <?php echo (isset($ArrFieldData['table_of_content'])) ? trim($ArrFieldData['table_of_content']) : ''; ?>

                    </textarea>

            </div>

          </div>

          <div class="form-group">

            <label for="exampleInputEmail1">Blog Description</label>

            <div class="box-body pad">

              <textarea class="form-control" id="editor1" name="blog_description" rows="10" cols="80">

                    <?php echo (isset($ArrFieldData['blog_description'])) ? trim($ArrFieldData['blog_description']) : ''; ?>

                    </textarea>

            </div>

          </div>
          <div class="form-group">

            <label for="exampleInputEmail1">CTA Title</label>

            <input type="text" class="form-control" id="cta_title" name="cta_title" placeholder="CTA Title"

              value="<?php echo (isset($ArrFieldData['cta_title'])) ? trim($ArrFieldData['cta_title']) : ''; ?>">



          </div>
          <div class="form-group">

            <label for="exampleInputEmail1">CTA SUb Title</label>

            <input type="text" class="form-control" id="cta_sub_title" name="cta_sub_title" placeholder="CTA Sub Title"

              value="<?php echo (isset($ArrFieldData['cta_sub_title'])) ? trim($ArrFieldData['cta_sub_title']) : ''; ?>">



          </div>
          <div class="form-group">

            <label for="exampleInputEmail1">CTA Button Text</label>

            <input type="text" class="form-control" id="cta_btn_text" name="cta_btn_text" placeholder="CTA Button Text"

              value="<?php echo (isset($ArrFieldData['cta_btn_text'])) ? trim($ArrFieldData['cta_btn_text']) : ''; ?>">



          </div>

          <div class="form-group">

            <label for="exampleInputEmail1">CTA Link</label>

            <input type="text" class="form-control" id="internal_link" name="internal_link" placeholder="CTA Link"

              value="<?php echo (isset($ArrFieldData['internal_link'])) ? trim($ArrFieldData['internal_link']) : ''; ?>">

            <div class="alert alert-danger" role="alert" style="display:none"></div>

          </div>
          <div class="form-group">

            <label for="exampleInputEmail1">Meta Title</label>

            <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title"

              value="<?php echo (isset($ArrFieldData['meta_title'])) ? trim($ArrFieldData['meta_title']) : ''; ?>">



          </div>

          <div class="form-group">

            <label for="exampleInputEmail1">Meta Descriptions</label>

            <textarea class="form-control" rows="3" id="meta_desc" name="meta_desc"

              placeholder="Enter Meta Description"><?php echo (isset($ArrFieldData['meta_descriptions'])) ? trim($ArrFieldData['meta_descriptions']) : ''; ?></textarea>

          </div>

          <div class="form-group">

            <label for="exampleInputEmail1">BLog JSON Schema</label>

            <textarea class="form-control" rows="10" id="blog_schema" name="blog_schema"

              placeholder="Enter Blog Schema"><?php echo (isset($ArrFieldData['blog_schema'])) ? trim($ArrFieldData['blog_schema']) : ''; ?></textarea>

          </div>

          <div class="form-group radio_btn_design_nomal">

            <label for="exampleInputEmail1">Is Active</label>

            <div class="radio">

              <?php if (isset($ArrFieldData['is_active']) && $ArrFieldData['blog_id'] > 0) { ?>

                <label><input type="radio" name="is_active" id="is_active1" value="1" <?php echo (isset($ArrFieldData['is_active']) && $ArrFieldData['is_active'] == "1") ? 'checked' : ''; ?>>Yes</label>

                <label><input type="radio" name="is_active" id="is_active2" value="0" <?php echo (isset($ArrFieldData['is_active']) && $ArrFieldData['is_active'] == "0") ? 'checked' : ''; ?>>No</label>

              <?php } else { ?>

                <label><input type="radio" name="is_active" id="is_active1" value="1" checked>Yes</label>

                <label><input type="radio" name="is_active" id="is_active2" value="0">No</label>

              <?php } ?>



            </div>

          </div>

        </div>

        <div class="box-footer">

          <input type="submit" name="save_blog" class="btn btn-default"

            value="<?php echo (isset($ArrFieldData['blog_id']) && $ArrFieldData['blog_id'] > 0) ? 'Update' : 'Add'; ?>"

            onClick="return validate_addblog();">

          <a href="<?php echo SITE_URL ?>blog" class="btn btn-info">Cancel</a>

        </div>

      </form>

      <script>

        $(function () {

          // Replace the <textarea id="editor1"> with a CKEditor

          CKEDITOR.replace('editor1');
          CKEDITOR.replace('table_of_content');

          $(".textarea").wysihtml5();

        });

      </script>

    </div>

  </div>

</div>