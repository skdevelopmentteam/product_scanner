<?php

/* 
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

/* starting the session */
session_start();
/* unset userid that is stored in the session */
unset($_SESSION['userid']);
/* Destroy the sessions */
session_destroy();

/* Check whether the COOKIES are set, if so then delete those */
if(isset($_COOKIE['cookie_email'],$_COOKIE['cookie_password'])){
    setcookie('cookie_email','', time()+60*60*24*7);
    setcookie('cookie_password','', time()+60*60*24*7);
}

header('Location: login.php');
exit();
