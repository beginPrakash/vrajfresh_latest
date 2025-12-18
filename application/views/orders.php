<?php require_once('common/header.php'); ?>
<?php 
unset($_SESSION['redirect_after_login']);
if (!IsUserLogin()) {
    // Save requested page
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    header("Location: ".BASE_URL . "login");
} ?>


<script src="<?php echo ASSET_URL . "js/popup-order.js"; ?>"></script>



<section class="categories-banner">



    <h2>Orders</h2>



</section>



<section class="my-account-page">



    <div class="container container-flex">



        <?php require_once('common/sidebar.php'); ?>



        <div id="user_id" style="display:none;">

            <?php echo $this->session->userdata['logged_in']['user_id']; ?>

        </div>



        <div class="account-info">



            <table width="100%" cellpadding="10" cellspacing="10" align="left" id="order_table">











            </table>











        </div>



    </div>



</section>



<div id="myModal" class="modal">







    <!-- Modal content -->



    <div class="modal-content">



        <span class="close">&times;</span>



        <iframe src=""></iframe>



    </div>







</div>







<?php require_once('common/common_js.php'); ?>











<script>

    $(document).ready(function () {



        /* if cokkie set is yes than redirect to changepassword */

        var get_forgot_password = Cookies.get('forget_password');

        if (get_forgot_password == 'yes') {

            $("#change_password").addClass("active");

            $("#my-orders").removeClass("active");

            $("#my-profile").removeClass("active");

            $("#my-address").removeClass("active");

        } else {

            $("#my-orders").addClass("active");

            $("#change_password").removeClass("active");

            $("#my-profile").removeClass("active");

            $("#my-address").removeClass("active");

        }



        showProgress('div#spinner');



        get_content(api_url_prefix);



        hideProgress('div#spinner');



    });



    function get_content(api_url_prefix) {



        var json_request = {



            "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",



            "user_id": $("#user_id").text()



        };







        $.ajax({



            "type": "POST",



            "url": api_url_prefix + 'get-order-by-user-id',



            "data": JSON.stringify(json_request),



            "dataType": "JSON",



            "success": function (response) {







                if (response.data != null) {



                    var table = '<tr><th scope="col">ORDER</th><th scope="col">DATE</th><th scope="col">STATUS</th><th scope="col">Payment Method</th><th scope="col">	TOTAL</th><th scope="col">ACTIONS</th></tr>';



                    for (let a = 0; a < response.data.length; a++) {



                        table += '<tr><td><a href="#">' + response.data[a].order_id + '</a></td><td>' + response.data[a].order_datetime + '</td><td>' + response.data[a].order_status + '</td><td>' + response.data[a].payment_method + '</td><td>' + response.data[a].order_total_amount + '</td><td><a href="<?php echo BASE_URL . 'Controller_users/orders_detail/'; ?>' + response.data[a].order_id + '" class="openpop" id="myBtn">View</a></td></tr>';



                    }







                } else {



                    table = '<tr><td>' + response.errors + '</td></tr>';



                }



                $("#order_table").html(table);



            },



            "error": function (response) {



                $("#order_table").html("<tr>" + response.errors + "</tr>");



            }



        });



    }

	

	get_user_address($("#user_id").text());

</script>



<?php require_once('common/footer.php'); ?>