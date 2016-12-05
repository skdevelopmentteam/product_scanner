<?php

/* 
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

/* This function is used to check whether the user exists or not */
function email_exists($email){
    global $link;
    $email  =   sanitize($email);
    $result =   mysqli_query($link, "SELECT count(`id`) FROM `user` WHERE `email`='$email'");
    $row    =   mysqli_fetch_row($result);
    
    return ($row[0] > 0) ? true : false;
}

/* This function is used to check whether the user isActive or not */
function is_active($email){
    global $link;
    $email  =   sanitize($email);
    $result =   mysqli_query($link, "SELECT count(`id`) FROM `user` WHERE `email`='$email' and isActive=1");
    $row    =   mysqli_fetch_row($result);
    
    return ($row[0] > 0) ? true : false;
}

/* This function will get the userid from the email */
function userid_from_email($email) {
    global $link;
    $email  =   sanitize($email);
    $result =   mysqli_query($link, "SELECT `id` FROM `user` WHERE `email`='$email'");
    $row    =   mysqli_fetch_row($result);
    
    return (!empty($row)) ? $row[0] : false;
}

/* This fucntion is used to login the user based on the email-id and password */
function login($email,$password){
    global $link;
    $userid     =   userid_from_email($email);
    $email      =   sanitize($email);
    $password   =   encrypt_password($password);
    $result     =   mysqli_query($link, "SELECT count(`id`) FROM `user` WHERE `email`='$email' AND `password`='$password'");
    $row        =   mysqli_fetch_row($result);
    
    return ($row[0] > 0) ? $userid : false;
}

/* Check whether the USER is loggedin or not */
function is_loggedin(){
    return (isset($_SESSION['userid'])) ? true : false;
}

/* Get the USER Details from the USERID */
function user_data_from_userid($userid){
    global $link;
    /* Typecast the userid to integer */
    $userid     =   (int)$userid;
    /* Get the total number or variable arguments that are passed to this funtion */
    $num_args   =   func_num_args();
    /* Get the arguments names that are passed as the parameters */
    $get_args   =   func_get_args();
    
    if($num_args > 1){
        //remove the userid[integer passed] so as to make the fields which is used to access the data from the user table
        unset($get_args[0]);
        $user_fields  =   '`'.implode('`, `', $get_args).'`';
        
        $result         =   mysqli_query($link, "SELECT $user_fields FROM `user` WHERE `id`=$userid");
        $user_details   =   mysqli_fetch_assoc($result);
        
        return $user_details;
    }
}