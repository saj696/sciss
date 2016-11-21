<?php
$user = $this->request->Session()->read('Auth')['User'];
?>
<!DOCTYPE html>
<!-- 
Name: Mazba's Cake
Version: 1.6
Author: Mazba Kamal
Website: http://www.soft-bd.com/
Contact: mazba.cse@gmail.com
Follow: www.twitter.com/mazba_kamal
Like: www.facebook.com/mazbakamal
License: You must have a valid license purchased only from softbd
-->
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>
        ISS :
        <?= $this->fetch('title'); ?>
    </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/font-awesome/css/font-awesome.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"
          rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet"
          type="text/css"/>
    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="<?= $this->request->webroot; ?>assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
    <link href="<?= $this->request->webroot; ?>assets/global/css/components-rounded.css" id="style_components"
          rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/admin/layout4/css/themes/default.css" rel="stylesheet"
          type="text/css" id="style_color"/>
    <link href="<?= $this->request->webroot; ?>assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>css/common.css" rel="stylesheet" type="text/css"/>

    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>

<!--    jquery -->

    <script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-header-fixed page-sidebar-closed-hide-logo ppage-sidebar-closed-hide-logo">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="#">
<!--                    <img src="img/logo-light.png" alt="logo" class="logo-default"/>-->
                </a>
                <h3 style="color: white; font-weight: bold"><?= __('Shetu Corporation Inventory & Sales System') ?></h3>
    <!--            <div class="menu-toggler sidebar-toggler"></div>-->
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
               data-target=".navbar-collapse">
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->

            <!-- BEGIN PAGE TOP -->
            <div class="page-top">

                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li class="separator hide"></li>
                        <li class="dropdown dropdown-user dropdown-dark">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                               data-close-others="true">
                                <span class="username username-hide-on-mobile"><?= $user['full_name_en'] ? $user['full_name_en'] : $user['full_name_bn'] ;?></span>
                                <?php
                                if(isset($user['picture']) && strlen($user['picture'])>0)
                                {
                                    ?>
                                        <img alt="" class="img-circle" src="<?= $this->request->webroot; ?><?= $user['picture']?>"/>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <img alt="" class="img-circle" src="<?= $this->request->webroot; ?>assets/admin/layout4/img/avatar.png"/>
                                    <?php
                                }
                                ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="<?= $this->request->webroot; ?>Users/view/<?= $user['id'];?>">
                                        <i class="icon-user"></i> My Profile </a>
                                </li>
                                <li>
                                    <a href="<?= $this->request->webroot; ?>Dashboard/logout">
                                        <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END PAGE TOP -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix"></div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <div class="page-sidebar navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
                <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <?php
    //            $left_menu = 'left_menu'.$this->request->session()->read('Auth.User.user_group_id');
    //            echo $this->element('left_menu',[],['cache'=>['config'=>'mcake','key'=>$left_menu]]);
                echo $this->element('left_menu');
                ?>
            </div>
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <?= $this->Flash->render() ?>
                <?= $this->Flash->render('auth') ?>
                <!-- BEGIN PAGE HEAD -->

                <!-- END PAGE BREADCRUMB -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <?php echo $this->fetch('content'); ?>
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END CONTENT -->
    </div>
    <div id="loader">
        <img src="<?php echo $this->request->webroot; ?>assets/global/img/loading-spinner-default.gif">
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/respond.min.js"></script>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/excanvas.min.js"></script>
    <![endif]-->

    <script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery-migrate.min.js"
            type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js"
            type="text/javascript"></script>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js"
            type="text/javascript"></script>
    <script
        src="<?= $this->request->webroot; ?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"
        type="text/javascript"></script>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"
            type="text/javascript"></script>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery.blockui.min.js"
            type="text/javascript"></script>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/uniform/jquery.uniform.js"
            type="text/javascript"></script>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"
            type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= $this->request->webroot; ?>assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="<?= $this->request->webroot; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="<?= $this->request->webroot; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
    <script src="<?= $this->request->webroot; ?>assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/select2/select2.min.js" type="text/javascript"></script>
    <link href="<?php echo $this->request->webroot; ?>assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>



    <script src="<?= $this->request->webroot; ?>js/sys_mcake.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
    jQuery(document).ready(function () {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
    });
    </script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>