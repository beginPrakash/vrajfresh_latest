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
          <div class="form-group">
            <label for="exampleInputEmail1">Blog Slug</label>
            <input type="hidden" name="check_url" id="check_url"
              value="<?php echo base_url() ?>adminpanel/controller_blog/ajaxCheckUrl">
            <input type="text" class="form-control" id="blog_url" name="blog_url" placeholder="Slug"
              value="<?php echo (isset($ArrFieldData['blog_url'])) ? trim($ArrFieldData['blog_url']) : ''; ?>">
            <div class="alert alert-danger" role="alert" style="display:none"></div>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Blog Author</label>
            <input type="text" class="form-control" id="author" name="author" placeholder="Author"
              value="<?php echo (isset($ArrFieldData['author'])) ? trim($ArrFieldData['author']) : ''; ?>">
            <div class="alert alert-danger" role="alert" style="display:none"></div>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Blog Sort Description</label>
            <div class="box-body pad">
              <textarea class="form-control" id="blog_sort_description" name="blog_sort_description" rows="5">
                    <?php echo (isset($ArrFieldData['blog_sort_description'])) ? trim($ArrFieldData['blog_sort_description']) : ''; ?>
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
            <label for="exampleInputEmail1">Blog Image</label>
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
            <label for="exampleInputEmail1">Meta Title</label>
            <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title"
              value="<?php echo (isset($ArrFieldData['meta_title'])) ? trim($ArrFieldData['meta_title']) : ''; ?>">

          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Meta Descriptions</label>
            <textarea class="form-control" rows="3" id="meta_desc" name="meta_desc"
              placeholder="Enter Meta Description"><?php echo (isset($ArrFieldData['meta_descriptions'])) ? trim($ArrFieldData['meta_descriptions']) : ''; ?></textarea>
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
          $(".textarea").wysihtml5();
        });
      </script>
    </div>
  </div>
</div>