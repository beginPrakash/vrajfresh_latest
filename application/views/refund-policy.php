<?php require_once('common/header.php'); ?>
<section class="categories-banner" id="section-header">
    <!-- <h2>VEGETABLES</h2> -->
</section>
<section class="category-product">
    <div class="container">
        <div class="container-flex1" id="section-content">

        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        // const url_prefix = "http://localhost/git/vraj-fresh-api/";
        showProgress('div#spinner');
        get_content(api_url_prefix);
        hideProgress('div#spinner');
    });
    function get_content(api_url_prefix) {
        var json_request = {

            "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
            "cms_id": "4"

        };
        var slider_banner = "";
        $.ajax({
            "type": "POST",
            "url": api_url_prefix + 'get-cms-by-id',
            "data": JSON.stringify(json_request),
            "dataType": "JSON",
            "success": function (response) {
                $("#section-header").html("<h2>" + response.data[0].cms_title + "</h2>");
                $("#section-content").html(response.data[0].cms_description);

            },
            "error": function (e) {
                console.log(e.responseText);
            }
        });
    }
</script>
<?php require_once('common/footer.php'); ?>