<?php require_once('common/header.php'); ?>
<style>
    .blog-detail-image img{
    width:100%;
    height:auto;
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

.blog-description h2, 
.blog-description h3 {
    display: block;
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

/* Lists */
.blog-description ul,
.blog-description ol{
    display:block !important;
    float:none !important;
    width:100%;
    padding-left:30px !important;
    margin:20px 0 !important;
    list-style-position:outside;
}

.blog-description ul{
    list-style-type:disc !important;
}

.blog-description ol{
    list-style-type:decimal !important;
}

.blog-description li{
    display:list-item !important;
    float:none !important;
    width:auto !important;
    margin-bottom:8px;
    padding:0;
    text-align:left;
}

.blog-description li p{
    display:inline;
    margin:0;
}

.blog-description p{
    display:block !important;
    width:100%;
    margin-bottom:15px;
}

/* Table */
.blog-description table{
    width:100%;
    border-collapse:collapse;
    margin:30px 0;
    overflow:hidden;
    display:block;
    overflow-x:auto;
}

.blog-description table th{
    background:#016492;
    color:#fff;
    padding:14px;
    border:1px solid #ddd;
    text-align:left;
    white-space:nowrap;
}

.blog-description table td{
    padding:12px;
    border:1px solid #ddd;
    white-space:nowrap;
}

.blog-description table tr:nth-child(even){
    background:#f8f8f8;
}

.blog-content{
    padding:20px;
}
.blog_cta_wrap, .cta_main_info {
    background: #1e53a5;
    border-radius: 12px;
    padding: 50px 20px;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
}
.blog_cta_wrap::before, .cta_main_info::before {
    content: "";
    background-image: url(https://cdn.moontechnolabs.com/live/new_mtpl_assets/images/new_blog/before_cta.png);
    background-repeat: no-repeat;
    position: absolute;
    left: 0;
    top: 0;
    height: 280px;
    width: 307px;
}
.blog_cta_wrap span {
    color: #fff !important;
    position: relative;
    padding-left: 20px;
    line-height: 25px !important;
    margin: 0;
    font-weight: 600;
    font-size: 28px;
}
.blog_cta_wrap p, .cta_main_info p {
    font-size: 20px;
    font-weight: 400;
    line-height: 22px;
    color: #fff;
    padding-top: 10px;
    text-align: center;
}
.blog_summary_text p {
    font-size: 16px !important;
    font-weight: 400;
    line-height: 30px;
    color: #fff;
    padding-top: 16px;
}
.blog_cta_wrap a, .cta_main_info a {
    animation: 2s infinite pulse;
    background: rgba(255, 122, 47, 1);
    padding: 10px;
    color: #fff;
    margin-top: 30px;
    z-index: 9;
    position: relative;
    padding-top:8px;
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

               

                <?php $custom_section = '
                    <div class="blog_cta_wrap">
                    <span class="">'.$blog['cta_title'].'<br>
                    </span><p></p>
                    <p class="">'.$blog['cta_sub_title'].'</p>
                    <p class=""><a id="talk_to_expert" class="btn" href="'.$blog['internal_link'].'">'.$blog['cta_btn_text'].'<br>
                    </a></p>
                    </div>';

                    $table_of_content = str_replace('(CTA)', $custom_section, $blog['table_of_content']);
                    $blog_content = str_replace('(CTA)', $custom_section, $blog['blog_description']);
                    ?>

               
                <div class="blog-description">
                    <?= $blog_content; ?>
                </div>
                <div class="blog-description">
                    <?= $table_of_content; ?>
                </div>
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
<?php  echo $blog['blog_schema'] ?? ''; ?>
<?php require_once('common/footer.php'); ?>