<?php require_once('common/header.php'); ?>
<style>
    .blog-card{
    border:1px solid #eee;
    background:#fff;
    transition:.3s;
    }

    .blog-card:hover{
        transform:translateY(-5px);
    }

    .blog-image{
        overflow:hidden;
    }

    .blog-image img{
        width:100%;
        height:250px;
        object-fit:cover;
    }

    .blog-content{
        padding:20px;
    }

    .blog-content h4{
        font-size:22px;
        margin-bottom:12px;
    }

    .blog-content h4 a{
        color:#222;
        text-decoration:none;
    }

    .blog-content p{
        color:#666;
        line-height:1.7;
    }

    .blog-footer{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-top:15px;
        font-size:14px;
    }

    .blog-footer a{
        color:#1E53A5;
        text-decoration:none;
    }
.pagination{
    display:flex;
    justify-content:center;
    align-items:center;
    list-style:none;
    padding:0;
    margin:40px 0;
    width:100%;
}

.page-item{
    margin:0 5px;
}

.page-link{
    display:block;
    padding:10px 15px;
    border:1px solid #ddd;
    text-decoration:none;
    color:#333;
    border-radius:5px;
}

.page-item.active .page-link{
    background:#1E53A5;
    color:#fff;
    border-color:#1E53A5;
}
    .container{
        width:100%;
        max-width:1320px;
        margin:0 auto;
        padding:0 15px;
    }

    .row{
        display:flex;
        flex-wrap:wrap;
        margin-left:-15px;
        margin-right:-15px;
    }

    .col-lg-4,
    .col-md-6{
        width:33.333%;
        padding-left:15px;
        padding-right:15px;
        box-sizing:border-box;
    }


    @media(max-width:991px){
        .col-lg-4,
        .col-md-6{
            width:50%;
        }
    }

    @media(max-width:767px){
        .col-lg-4,
        .col-md-6{
            width:100%;
        }
    }

    .img-fluid{
        max-width:100%;
        height:auto;
        display:block;
    }

    .mb-4{
        margin-bottom:24px;
    }
</style>

<section class="billing-page blog-section py-5">
    <div class="container">
         <?php if (!empty($blogs)) { ?>
           
            <div class="row">
                <?php foreach ($blogs as $blog) { ?>
                    <!-- Blog Item -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="blog-card">
                            <div class="blog-image">
                                <img src="<?php echo base_url('admin/uploads/blog/'.$blog['blog_image']); ?>" class="img-fluid" alt="">
                            </div>

                            <div class="blog-content">
                                
                                <h4>
                                    <a href="<?php echo base_url('blog/details/'.$blog['blog_slug']); ?>"><?php echo $blog['blog_title']; ?></a>
                                </h4>

                                <p>
                                    <?php echo substr(strip_tags($blog['blog_description']), 0, 150); ?>...
                                </p>

                                <div class="blog-footer">
                                    <a href="<?php echo base_url('blog/details/'.$blog['blog_slug']); ?>">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>

        <!-- Pagination -->
        <nav class="mt-5">
            <?php if($total_pages > 1){ ?>
<nav>
    <ul class="pagination justify-content-center">

        <!-- Previous -->
        <?php if($current_page > 1){ ?>
            <li class="page-item">
                <a class="page-link"
                   href="<?= base_url('blogs?page='.($current_page-1)) ?>">
                    Previous
                </a>
            </li>
        <?php } ?>

        <!-- Page Numbers -->
        <?php for($i = 1; $i <= $total_pages; $i++){ ?>
            <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                <a class="page-link"
                   href="<?= base_url('blogs?page='.$i) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php } ?>

        <!-- Next -->
        <?php if($current_page < $total_pages){ ?>
            <li class="page-item">
                <a class="page-link"
                   href="<?= base_url('blogs?page='.($current_page+1)) ?>">
                    Next
                </a>
            </li>
        <?php } ?>

    </ul>
</nav>
<?php } ?>
        </nav>
   
        <?php } else { ?>
            <div class="col-12">
                <p class="text-center">No blogs found</p>
            </div>
        <?php } ?>
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