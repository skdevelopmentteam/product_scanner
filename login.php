<?php
/*
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

include 'core/init.php';
login_redirect();
/* Check whether the COOKIES are set, if so then validate with the user name */
if(isset($_COOKIE['cookie_email'],$_COOKIE['cookie_password'])){
    $email      =   $_COOKIE['cookie_email'];
    $password   =   $_COOKIE['cookie_password'];
    
    $login      =   login($email, $password);
    
    if($login === false){
        header('Location: login.php');
        exit();
    }else{
        $_SESSION['userid'] = userid_from_email($email);
        header('Location: home.php');
        exit();
    }
            
}
?>


<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>SKERP Login</title>
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/generic.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="container">

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12 col-md-offset-4 col-sm-offset-3">
                    <h3>Production Login</h3>

                    <?php
                    if (isset($_GET['error']) && !empty($_GET['error'])) {
                        echo '<div class="alert alert-danger" style="padding: 6px 6px 6px 10px;">';
                        echo sanitize($_GET['error']);
                        echo '</div>';
                    }
                    ?>

                    <form action="authenticate.php" method="post" role="form">
                        <div class="input-group input-group-sm margin-bottom5 ">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-user"></span>
                            </span>
                            <input type="text" class="form-control" name="email" placeholder="Email-id" autofocus="autofocus" />
                        </div>
                        <div class="input-group input-group-sm margin-bottom5">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-qrcode"></span>
                            </span>
                            <input type="password" class="form-control" name="password" placeholder="Password" />
                        </div>
                        <label class="checkbox">
                            <input type="checkbox" name="remember_me" value="1"> Remember me
                        </label>
                        <div class="margin-bottom5">
                            <input type="submit" class="form-control btn btn-primary btn-sm" value="Login" />
                        </div>

                    </form> 
                </div>
            </div>
        </div>


        <script src="assets/js/jquery-2.1.1.js" type="text/javascript"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>