<?php
require_once '_utils/functions.php';
$config_vars['title'] = 'EBETON | Log in';
$config_vars['style']['0'] = 'template/plugins/iCheck/square/blue.css';
_print_init($config_vars);
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    verify_user_login($_POST['username'], $_POST['password']);
}
?>


<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="index.php"><b>Admin</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Autentificare</p>

        <form action="login.php" method="post">
            <input type="text" style="display:none">
            <input type="password" style="display:none">
            <div class="form-group has-feedback">
                <input type="username" class="form-control" placeholder="Utilizator sau email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Parola">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <a href="#">Am uitat parola</a><br>
                </div>
                <!-- /.col -->
                <div class="col-xs-6">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Autentificare</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <!-- /.social-auth-links -->



    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<?php
_print_footer($config_vars);
?>