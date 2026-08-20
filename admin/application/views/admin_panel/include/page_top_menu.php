<?php
/* CALL FUNCTION IN MENU HELPER */
$active_menu = getActiveMenuName();
?>
<ul class="nav navbar-nav">


    <?php if (is_page_view_access('Promotion Code') || is_page_view_access('Promotion Client Group') || is_page_view_access('Promotion Campaign') || is_page_view_access('Promotion Template')) { ?>
        <?php if ($active_menu == 'promotion') { ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Promotional Code <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <?php if (is_page_add_access('Promotion Code')) { ?>
                        <li><a href="<?php echo base_url(); ?>promotional-code-add">Create Code</a> </li>
                    <?php } ?>
                    <?php if (is_page_view_access('Promotion Code')) { ?>
                        <li><a href="<?php echo base_url(); ?>promotional-code">View Code</a> </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
    <?php } ?>




    <?php if (is_page_view_access('Web Forms') || is_page_view_access('Landing Page') || is_page_view_access('Landing Page User Request') || is_page_view_access('Feedback') || is_page_view_access('Newsletter Subscriber')) { ?>
        <?php if ($active_menu == 'inquires') { ?>
            <?php if (is_page_view_access('Web Forms')) { ?>
                <li><a href="<?php echo base_url(); ?>contact-inquiry">Web Forms</a></li>
            <?php } ?>


            <?php if (is_page_view_access('Newsletter Subscriber')) { ?>
                <li><a href="<?php echo base_url(); ?>newsletter-subscriber">Newsletter Subscriber</a></li>
            <?php } ?>
        <?php } ?>
    <?php } ?>




</ul>