<?php

/* 
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

/* Inlcude the init.php to include initialize all the files */
include 'core/init.php';

/* Authenticate the USER with the credentials that are provided by him */
if(empty($_POST) === false){
    $email      =   $_POST['email'];
    $password   =   $_POST['password'];
    
    /* $error - To set the ERROR message */
    $error      =   null;
    
    if(empty($email) === true || empty($password) === true){
        $error  =   'Enter Email and Password';
    }else if(filter_var ($email, FILTER_VALIDATE_EMAIL) === false){
        $error  =   'Enter your email-id properly';
    }else if(email_exists($email) === false){
        $error  =   'Entered user does not exists !!!';
    }else if(is_active($email) === false){
        $error  =   'Your account has not yet been activated';
    }else{
        $login  =   login($email,$password);
        if($login === false){
            $error  =   'Email/Password is wrong';
        }else{
            if(isset($_POST['remember_me'])){
                setcookie('cookie_email',$email, time()+60*60*24*7);
                setcookie('cookie_password',$password, time()+60*60*24*7);
            }
            $_SESSION['userid']  =   $login;
            header('Location: home.php');
            exit();
        }
    }
    
    if(isset($error)){
        header('Location: login.php?error='.$error);
        exit();
    }
}else{
    /* if trying to post manually then redirect to error.php page */
    header('Location: error.php');
    exit();
}