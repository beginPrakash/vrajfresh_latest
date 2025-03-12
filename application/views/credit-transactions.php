<?php require_once('common/header.php'); ?>
<style>
    .card {
      background: linear-gradient(135deg, #4caf50, #1E53A5);
      color: white;
      border-radius: 12px;
      padding: 20px;
      width: 400px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      margin-bottom:25px;
    }
    .card-header {
      font-size: 18px;
      font-weight: bold;
      text-transform: uppercase;
    }
    .card-balance {
      font-size: 36px;
      font-weight: bold;
      margin: 20px 0;
      text-align: center;
    }
    .card-footer {
      font-size: 14px;
      text-align: center;
      opacity: 0.8;
    }
</style>

<section class="categories-banner">



    <h2>Credit Transactions</h2>



</section>



<section class="my-account-page">



    <div class="container container-flex">



        <?php require_once('common/sidebar.php'); ?>



        <div id="user_id" style="display:none;">

            <?php echo $this->session->userdata['logged_in']['user_id']; ?>

        </div>



        <div class="account-info">

        <div class="card">
            <div class="card-header">Credit Balance</div>
            <div class="card-balance">$0.00</div>
            <div class="card-footer">As of <?php echo date('F d, Y'); ?></div>
        </div>

            <table width="100%" cellpadding="10" cellspacing="10" align="left" id="transcr_table">











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

            $("#credit-transactions").removeClass("active");

            $("#my-profile").removeClass("active");

            $("#my-address").removeClass("active");

        } else {

            $("#credit-transactions").addClass("active");

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



            "url": api_url_prefix + 'get-credit-transaction-by-user-id',



            "data": JSON.stringify(json_request),



            "dataType": "JSON",



            "success": function (response) {







                if (response.data != null) {

                    $('.card-balance').text('$'+response.data.total_credit);

                    var table = '<tr><th scope="col">ORDER</th><th scope="col">DATE</th><th scope="col">STATUS</th><th scope="col">	Credit Amount</th></tr>';



                    for (let a = 0; a < response.data.trans_data.length; a++) {

                        table += '<tr><td><a href="#">' + response.data.trans_data[a].order_id + '</a></td><td>' + response.data.trans_data[a].order_datetime + '</td><td>' + response.data.trans_data[a].order_status + '</td><td>' + response.data.trans_data[a].amount + '</td></tr>';



                    }







                } else {



                    table = '<tr><td>' + response.errors + '</td></tr>';



                }



                $("#transcr_table").html(table);



            },



            "error": function (response) {



                $("#transcr_table").html("<tr>" + response.errors + "</tr>");



            }



        });



    }

	

	get_user_address($("#user_id").text());

</script>



<?php require_once('common/footer.php'); ?>