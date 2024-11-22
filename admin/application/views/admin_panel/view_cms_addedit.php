<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">

      <form role="form" id="cmsform" method="post" action="<?php echo SITE_URL ?>adminpanel/controller_cms/save">
        <div class="box-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Page Title:<span class="red">*</span></label>
            <input type="text" class="form-control" id="cmstitle" name="cmstitle" placeholder="Title"
              onKeyUp="convertToSlug();"
              value="<?php echo (isset($ArrFieldData['cms_title'])) ? trim($ArrFieldData['cms_title']) : ''; ?>" required>
            <div class="alert alert-danger" role="alert" style="display:none"></div>
            <input type="hidden" name="cms_id" id="cms_id"
              value="<?php echo (isset($ArrFieldData['cms_id'])) ? trim($ArrFieldData['cms_id']) : ''; ?>">
            <?php echo form_error('cmstitle'); ?>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Page Slug:<span class="red">*</span></label>
            <input type="hidden" name="check_url" id="check_url"
              value="<?php echo base_url() ?>adminpanel/controller_cms/ajaxCheckUrl">
            <input type="text" class="form-control" id="cms_url" name="cms_url" placeholder="Slug"
              value="<?php echo (isset($ArrFieldData['cms_url'])) ? trim($ArrFieldData['cms_url']) : ''; ?>" required>
            <div class="alert alert-danger" role="alert" style="display:none"></div>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Page Description</label>
            <div class="box-body pad">
              <textarea id="editor1" name="cms_description" rows="10" cols="80">
                    <?php echo (isset($ArrFieldData['cms_description'])) ? trim($ArrFieldData['cms_description']) : ''; ?>
                    </textarea>
            </div>
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
              <?php if (isset($ArrFieldData['is_active']) && $ArrFieldData['cms_id'] > 0) { ?>
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
          <input type="submit" name="save_page" class="btn btn-default"
            value="<?php echo (isset($ArrFieldData['cms_id']) && $ArrFieldData['cms_id'] > 0) ? 'Update' : 'Add'; ?>"
            id="submit">
          <a href="<?php echo SITE_URL ?>cms" class="btn btn-info">Cancel</a>
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