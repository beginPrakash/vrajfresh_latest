<?php require_once('common/header.php'); ?>
<section class="contact vraj-title">
    <div class="container">
        <h2>Contact us</h2>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
            industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
            scrambled</p>
    </div>
    <div class="container container-flex contact-info">
        <div class="contact-left">
            <ul>
                <li> <span><img src=<?php echo ASSET_URL . "images/map.svg"; ?>></span>
                    <div>
                        <h4>Address</h4>
                        <p id="address"></p>
                    </div>
                </li>
                <li> <span><img src=<?php echo ASSET_URL . "images/phone.svg"; ?>></span>
                    <div>
                        <h4>Phone</h4>
                        <p id="phone"></p>
                        <!-- <p><a href="tel:2016887887">201-688-7887</a></p> -->
                    </div>
                </li>
                <li> <span><img src=<?php echo ASSET_URL . "images/mail.svg"; ?>></span>
                    <div>
                        <h4>Email</h4>
                        <p id="email"></p>
                        <!-- <p><a href="mailto:info@vrajfresh.com">info@vrajfresh.com</a></p> -->
                    </div>
                </li>
            </ul>
        </div>
        <div class="contact-right">
            <h3>Just say Hello!</h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            <form id="contact" method="post">
                <input type="text" name="fist_name" placeholder="Fist Name">
                <input type="text" name="last_name" placeholder="Last Name">
                <input type="email" name="email" placeholder="Email">
                <input type="text" name="Phone" placeholder="Phone">
                <textarea name="Message" placeholder="Message" cols="" rows=""></textarea>
                <button onclick="send_mail()">Sign up</button>
            </form>
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
        var json_request = {
            "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
            "configuration_key": "'ADDRESS','PHONE','EMAIL'"
        };

        $.ajax({
            "type": "POST",
            "url": api_url_prefix + 'get-configurations-by-key',
            "data": JSON.stringify(json_request),
            "dataType": "JSON",
            "success": function (response) {
                $("#address").html(response.data[2].configuration_value);
                $("#phone").html(response.data[0].configuration_value);
                $("#email").html(response.data[1].configuration_value);

                //  $("#section-content").html(response.data[0].cms_description);

            },
            "error": function (e) {
                console.log(e.responseText);
            }
        });
    }
    function send_mail() {
        var form = $("#contact");

        $.ajax({
            "type": "POST",
            "url": api_url_prefix + 'contact-mail',
            "data": form.serialize(),
            "success": function (response) {
                $("#message").html("<div class='text-success'>Mail Sent Successfully</div>");
            },
            "error": function (e) {
                $("#message").html("<div class='text-error'>Mail Not Sent  Successfully</div>");
            }
        });
    }
</script>
<?php require_once('common/footer.php'); ?>