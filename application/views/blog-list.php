<?php require_once('common/header.php'); ?>
<div class="container mt-4">

    <div class="row">
        <?php if (!empty($blogs)) { ?>
            <?php foreach ($blogs as $blog) { ?>
<div class="col-12 mb-3">
    <div class="card">
        <div class="row g-0 align-items-center">

            <!-- Image -->
            <div class="col-4">
                <img src="<?php echo base_url('admin/uploads/blog/'.$blog['blog_image']); ?>" 

                class="img-fluid w-100"
                     style="height:200px; object-fit:cover;">
            </div>

            <!-- Content -->
            <div class="col-8">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $blog['blog_title']; ?></h5>

                    <p class="card-text mb-2">
                        <?php echo substr(strip_tags($blog['blog_description']), 0, 150); ?>...
                    </p>

                    <a href="<?php echo base_url('blog/details/'.$blog['blog_slug']); ?>" 
                       class="btn btn-primary btn-sm">
                       Read More
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

            <?php } ?>
        <?php } else { ?>
            <div class="col-12">
                <p class="text-center">No blogs found</p>
            </div>
        <?php } ?>
    </div>

</div>
<?php require_once('common/footer.php'); ?>