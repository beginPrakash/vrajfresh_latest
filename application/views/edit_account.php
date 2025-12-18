<?php require_once('common/header.php'); ?>
<?php 
unset($_SESSION['redirect_after_login']);
if (!IsUserLogin()) {
    // Save requested page
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    header("Location: ".BASE_URL . "login");
} ?>
<section class="categories-banner">

    <h2>Edit Account</h2>

</section>

<section class="my-account-page">

    <div class="container container-flex">

        <?php require_once('common/sidebar.php'); ?>

        <div class="account-info">

            <div class="billing-left">

                <?php if (isset($_GET['is_success']) && $_GET['is_success'] == '1' && $this->session->flashdata('success_message')) { ?>

                    <div class="alert-success">

                        <?php echo $this->session->flashdata('success_message'); ?>

                    </div>

                <?php } ?>

                <?php if (isset($_GET['is_error']) && $_GET['is_error'] == '1' && $this->session->flashdata('error_message')) { ?>

                    <div class="alert alert-danger">

                        <?php echo $this->session->flashdata('error_message'); ?>

                    </div>

                <?php } ?>

                <form method="post" action="<?php echo BASE_URL . 'edit-profile'; ?>">

                    <label>First Name <small title="required">*</small></label>

                    <input placeholder="First Name" name="first_name" type="text"

                        value="<?php echo $this->session->userdata['logged_in']['first_name']; ?>" required>

                    <?php echo form_error("first_name", "<div class='form-error'>", "</div>"); ?>



                    <label>Last Name <small title="required">*</small></label>

                    <input placeholder="Last name" name="last_name" type="text"

                        value="<?php echo $this->session->userdata['logged_in']['last_name']; ?>" required>

                    <?php echo form_error("last_name", "<div class='form-error'>", "</div>"); ?>



                    <label>Display name <small title="required">*</small></label>

                    <input placeholder="Display name" name="display_name" type="text"

                        value="<?php echo $this->session->userdata['logged_in']['display_name']; ?>" required>

                    <?php echo form_error("display_name", "<div class='form-error'>", "</div>"); ?>



                    <b>This will be how your name will be displayed in the account section and in reviews</b><br />

                    <label>Email address <small title="required">*</small></label>

                    <input placeholder="Email address" name="email" type="email"

                        value="<?php echo $this->session->userdata['logged_in']['email']; ?>" disabled>

                    <input type="hidden" name="user_id"

                        value="<?php echo $this->session->userdata['logged_in']['user_id']; ?>">

                    <input type="hidden" name="user_role_id"

                        value="<?php echo $this->session->userdata['logged_in']['user_role_id']; ?>">

                    <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">

                    <button type="submit">Save Changes</button>

                </form>

            </div>

        </div>

    </div>

</section>

<?php require_once('common/common_js.php'); ?>

<script>

    $(document).ready(function () {

        $("#my-address").removeClass("active");

        $("#my-account").addClass("active");

        $("#my-orders").removeClass("active");

        $("#change_password").removeClass("active");



        showProgress('div#spinner');

        // get_content(api_url_prefix);

        hideProgress('div#spinner');



    });



</script>

<?php require_once('common/footer.php'); ?>