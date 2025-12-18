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

                        <p id="address">449, Market Street Saddle Brook, NJ 07663</p>

                    </div>

                </li>

                <li> <span><img src=<?php echo ASSET_URL . "images/phone.svg"; ?>></span>

                    <div>

                        <h4>Phone</h4>

                        <p><a href="tel:2016887887">201-688-7887</a></p>

                    </div>

                </li>

                <li> <span><img src=<?php echo ASSET_URL . "images/mail.svg"; ?>></span>

                    <div>

                        <h4>Email</h4>

                        <p><a href="mailto:info@vrajfresh.com">info@vrajfresh.com</a></p>

                    </div>

                </li>

            </ul>

        </div>

        <div class="contact-right">

            <h3>Just say Hello!</h3>

            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

            <form id="contact" method="post" action="">

                <input type="text" name="first_name" id="first_name" placeholder="First Name">

                <input type="text" name="last_name" placeholder="Last Name">

                <input type="email" name="user_email" placeholder="Email">

                <input type="text" name="mobile_no" placeholder="Phone">

                <textarea name="description" placeholder="Message" cols="" rows=""></textarea>

                <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">

                <input type="hidden" name="user_role_id" value="4">

                <button onclick="contactus()" class="btn btn-primary">Save</button>

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



function contactus() {

        $("#contact").validate({

            rules: {

                first_name: 'required',

                last_name: 'required',

                user_email: {

                    required: true,

                    email: true

                },

                mobile_no: 'required',
                description: 'required',

            },

            messages: {

                first_name: 'This field is required',

                last_name: 'This field is required',

                user_email: { required: 'This field is required', email: 'Enter a valid email' },

                mobile_no: 'This field is required',

                description: 'This field is required'

            },

            submitHandler: function (form) {

                //form.submit();

                var form = $("#contact");



                $.ajax({

                    "type": "POST",

                    "url": api_url_prefix + 'contact-mail',

                    "data": form.serialize(),

                    "success": function (response) {

                        alert('Form Submitted Successfully');
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