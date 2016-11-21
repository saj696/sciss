<!DOCTYPE html>
<!-- 
Template Name:Mazba'cake
Version: 1.2
Author: Mazba kamal
Website: http://www.softbdltd.com/
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?= __('ISS') ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="<?= $this->request->webroot; ?>assets/admin/pages/css/login-soft.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME STYLES -->
    <link href="<?= $this->request->webroot; ?>assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $this->request->webroot; ?>assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="<?= $this->request->webroot; ?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">

<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGO -->
<div class="logo">
    <h1 style="color: #f1f1e3"><b><?= __('Shetu Corporation Inventory & Sales System') ?></b></h1>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <?= $this->Flash->render() ?>
    <?= $this->Flash->render('auth') ?>
    <?php echo $this->fetch('content'); ?>
</div>
<div class="copyright hide">
    2016 © Softbd Ltd
</div>
<!-- END LOGIN -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?= $this->request->webroot; ?>assets/global/plugins/respond.min.js"></script>
<script src="<?= $this->request->webroot; ?>assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot; ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?= $this->request->webroot; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot; ?>assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?= $this->request->webroot; ?>assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot; ?>assets/admin/pages/scripts/login-soft.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        Login.init();
        Demo.init();
        // init background slide images
        $.backstretch([
                "<?= $this->request->webroot; ?>assets/admin/pages/media/bg/1.jpg",
                "<?= $this->request->webroot; ?>assets/admin/pages/media/bg/2.jpg",
                "<?= $this->request->webroot; ?>assets/admin/pages/media/bg/3.jpg",
                "<?= $this->request->webroot; ?>assets/admin/pages/media/bg/4.jpg"
            ], {
                fade: 1000,
                duration: 8000
            }
        );
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>