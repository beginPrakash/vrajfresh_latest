<?php require_once('common/header.php'); ?>

<section class="categories-banner cms_header" id="section-header">

    <!-- <h2>VEGETABLES</h2> -->

</section>

<section class="category-product">

    <div class="container">

        <input type="hidden" id="cms_url" value=<?php echo $url; ?>>

        <div class="container-flex1" id="section-content">



        </div>

    </div>

</section>

<?php require_once('common/common_js.php'); ?>

<script>

    $(document).ready(function () {

        //  const api_url_prefix = "http://localhost/git/vraj-fresh-api/";

        showProgress('div#spinner');

        get_content(api_url_prefix);

        hideProgress('div#spinner');

    });

    function get_content(api_url_prefix) {

        var url = $("#cms_url").val()

        var json_request = {



            "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

            "cms_url": url



        };

        var slider_banner = "";

        $.ajax({

            "type": "POST",

            "url": api_url_prefix + 'get-cms-by-slug',

            "data": JSON.stringify(json_request),

            "dataType": "JSON",

            "success": function (response) {

                if (response.data != null) {

                    var title = response.data[0].meta_title;
                    var description = response.data[0].meta_descriptions;

                    $("#section-header").html("<h1>" + response.data[0].cms_title + "</h1>");

                    $("#section-content").html(response.data[0].cms_description);

                    // ✅ Set Meta Title
                    document.title = title;

                    // ✅ Set Meta Description
                    $('meta[name="description"]').attr("content", description);

                }

                else {

                    $("#section-content").html("No Content Found");

                }



            },

            "error": function (response) {

                $("#section-content").html(response.errors);

            }

        });

    }

</script>

<?php require_once('common/footer.php'); ?>