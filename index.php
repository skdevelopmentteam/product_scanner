<?php
/* 
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

/* Check whether the COOKIES are set, if so then validate with the user name */
if(isset($_COOKIE['cookie_email'],$_COOKIE['cookie_password'])){
    include 'core/init.php';
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
            
}else{
    header('Location: login.php');
    exit();
}