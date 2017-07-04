<?php
/* The magic happens here */
require_once '_utils/functions.php';
$config_vars['title'] = 'EBETON | Register';
$config_vars['style']['0'] = 'template/plugins/iCheck/square/blue.css';
_print_init($config_vars);

if (!empty($_POST['username'])) {
    require_once '_utils/user.php';
    $user = new User();
    $error = array();

    if($_POST['password'] != $_POST['password1']){
        $error['password1'] = 'Cele 2 parole nu corespund';
    }


    $data = array(
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'password' => $_POST['password'],
        'active' => 0
    );

    var_dump($data);die;
    $userID = $user->insertUser($data);//The method returns the userID of the new user or 0 if the user is not added
    if ($userID==0)
        echo 'User not registered';//user is allready registered or something like that
    else
        echo 'User registered with user id '.$userID;
}
/* The magic happens here */

?>
<body class="hold-transition register-page">
<div class="wrapper register-box">
  <div class="register-logo">
    <a href="index.php"><b>Admin</b></a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Inregistrare utilizator</p>

    <form action="register.php" method="post" name="register_form" onkeyup="checkInputs('register_form');"> <!--onkeyup="checkInputs('register_form');"-->
        <div class="form-group has-feedback has-warning" id="firstname_group">
            <label class="control-label" for="firstname" id="firstname_label"><i class="fa fa-bell-o"></i> Introduceti numele de familie</label>
            <input type="text" class="form-control" placeholder="Nume" name="firstname" id="firstname" check="name" alt="Nume" value="">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback has-warning" id="lastname_group">
            <label class="control-label" for="lastname" id="lastname_label"><i class="fa fa-bell-o"></i> Introduceti prenumele</label>
            <input type="text" class="form-control" placeholder="Prenume" name="lastname" id="lastname" check="name" alt="Prenume" value="">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback has-warning" id="username_group">
            <label class="control-label" for="email" id="username_label"><i class="fa fa-bell-o"></i> Introduceti utilizator</label>
            <input type="text" class="form-control" placeholder="Utilizator" name="username" id="username" check="username" value="" alt="Utilizator">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback has-warning" id="email_group">
            <label class="control-label" for="email" id="email_label"><i class="fa fa-bell-o"></i> Introduceti adresa de email</label>
            <input type="email" class="form-control" placeholder="Email" name="email" id="email" check="email" value="" alt="Email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback has-warning" id="password_group">
            <label class="control-label" for="password" id="password_label"><i class="fa fa-bell-o"></i> Introduceti parola</label>
            <input type="password" class="form-control" placeholder="Parola" name="password" id="password" check="password" value="" alt="Parola">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback has-warning" id="password1_group">
            <label class="control-label" for="password1" id="password1_label"><i class="fa fa-bell-o"></i> Introduceti parola din nou</label>
            <input type="password" class="form-control" placeholder="Parola din nou" name="password1" id="password1" check="password_retype" value="" alt="Parola din nou">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        <div class="form-group" id="submit_group">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Inregistrare</button>
          <!-- /.col -->
        </div>
    </form>

    <div class="social-auth-links text-center">
      <p>- SAU -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Inregistrare folosind Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Inregistrare folosind Google+</a>
    </div>

    <a href="login.php" class="text-center">Am deja cont</a>
  </div>
  <!-- /.form-box -->
</div>
<?php
$config_vars['js']['0'] = 'plugins/iCheck/icheck.min.js';
$config_vars['js']['1'] = 'template/js/modified_app.js';
_print_footer($config_vars);
?>