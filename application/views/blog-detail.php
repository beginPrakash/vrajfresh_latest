<?php require_once('common/header.php'); ?>
<style>
    .blog-detail-image img{
    width:100%;
    height:500px;
    object-fit:cover;
    border-radius:10px;
}

.blog-detail-content{
    padding-top:30px;
}

.blog-detail-content h1{
    font-size:36px;
    margin-bottom:15px;
}

.blog-meta{
    color:#777;
    margin-bottom:20px;
}

.blog-description{
    line-height:1.9;
    font-size:16px;
}

.blog-description img{
    max-width:100%;
    height:auto;
}

.related-blogs h2{
    margin-bottom:30px;
}

.blog-card{
    border:1px solid #eee;
    height:100%;
}

.blog-image img{
    width:100%;
    height:250px;
    object-fit:cover;
}

.blog-content{
    padding:20px;
}
</style>
<section class="blog-detail-section py-5">
    <div class="container">

        <div class="blog-detail-card">

            <div class="blog-detail-image">
                <img src="<?= base_url('admin/uploads/blog/'.$blog['blog_image']); ?>" alt="<?= $blog['blog_title']; ?>">
            </div>

            <div class="blog-detail-content">


                <h1><?= $blog['blog_title']; ?></h1>

                <div class="blog-description">
                    <?= $blog['blog_description']; ?>
                </div>

                <div class="blog-description">
                    <?= $blog['table_of_content']; ?>
                </div>
                <p><?= $blog['internal_link']; ?></p>
            </div>

        </div>

    </div>
</section>
<?php require_once('common/common_js.php'); ?>
<script>
    $(document).ready(function () {

        showProgress('div#spinner');
        hideProgress('div#spinner');

    });
</script>

<?php require_once('common/footer.php'); ?>