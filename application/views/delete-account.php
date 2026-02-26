<?php require_once('common/header.php'); ?>

<section class="contact vraj-title">

    <div class="container container-flex contact-info">
    

       

        <div class="contact-right">

            <h3>Delete User Account</h3>

            <form id="delete_account" method="post" action="">

                <input type="text" name="user_email" id="user_email" placeholder="User Email Or Phone Number"><br>

                <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">

                <input type="hidden" name="user_role_id" value="4">

                <button onclick="deleteuseraccount()" class="btn btn-primary">Submit</button>

            </form>

        </div>

    </div>

</section>

<?php require_once('common/common_js.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>


<script>

    $(document).ready(function () {

        //  const api_url_prefix = "http://localhost/git/vraj-fresh-api/";

        showProgress('div#spinner');

       // get_content(api_url_prefix);

        hideProgress('div#spinner');

    });



function deleteuseraccount() {

        $("#delete_account").validate({

            rules: {

                user_email: 'required',

            },

            messages: {

                user_email: 'This field is required',

            },

            submitHandler: function (form) {

                //form.submit();

                var form = $("#delete_account");



                $.ajax({

                    "type": "POST",

                    "url": front_url + 'vrajapp-api/V1/delete-user-account',

                    "data": form.serialize(),

                    "success": function (response) {

                        alert('User Deleted Successfully');
                        location.reload();
                        //$("#message").html("<div class='text-success'>Form Submitted Successfully</div>");

                    },

                    "error": function (e) {

                        //$("#message").html("<div class='text-error'>Form Not Submitted Successfully</div>");
                        alert('Something Went Wrong!');
                        location.reload();

                    }

                });

            },



        });



    }
</script>

<?php require_once('common/footer.php'); ?>