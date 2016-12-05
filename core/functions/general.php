<?php

/*
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

/* This function is used to PRINT the ARRAY data in the preformatted manner */
if (!function_exists('pr')) {
    function pr($data) {
        echo '<pre>', print_r($data), '</pre>';
    }
}

/* This function is used to Sanitize the user data and make data safe to insert into the database */
function sanitize($data) {
    global $link;
    $data = trim($data);
    return htmlentities(strip_tags(mysqli_real_escape_string($link, $data)));
}

//Function is used to Encrypt the Password for the Security Purpose
function encrypt_password($password) {
    //Encrypting the Password with the Salt
    $salt = '4m0F20nqYiu9a2TOsa7l0CP8TqVS45k2';

    //Returns the Encrypted Password
    return hash('sha512', $password . $salt);
}

/* This fnction is used to generate the random keys of specific length
  Accepts parameter of certain length if not specified it will generate 20 bit length automatically
 */
function generate_random_key($length = 20) {
    //Initializing the varialble
    $keystring = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $random_key = '';
    for ($i = 0; $i < $length; $i++) {
        $random_key.=$keystring[rand(0, strlen($keystring) - 1)];
    }
    //Return the randomly generated key
    return $random_key;
}

/* This function outputs the errors in ul>li format with unstyled
 * To get the bullets styling remove class='list-unstyled' in <ul> tag */
function output_errors($errors){
    $output =   array();
    
    foreach ($errors as $error) {
        $output[]   =   '<li>'.$error.'</li>';
    }
    return '<ul class="list-unstyled">'.implode('', $output).'</ul>';
}


/* Checks whether the user is loggedin else will redirect to the protectect page */
function protected_page(){
    if(is_loggedin() === false){
//        header('Location: protected.php');
        header('Location: logout.php');
        exit();
    }
}

/* If user tries to access the page directly accessing through the URL,
 * If already loggedin then redirect him to any of the inner page 
 */
function login_redirect(){
    if(is_loggedin() === true){
        header('Location: home.php');
    }
}

/* Used to get the difference of 2 arrays
   Returns the array with difference	
 */
function multi_diff($arr1,$arr2){
  $result = array();
  foreach ($arr1 as $k=>$v){
    if(!isset($arr2[$k])){
      $result[$k] = $v;
    } else {
      if(is_array($v) && is_array($arr2[$k])){
        $diff = multi_diff($v, $arr2[$k]);
        if(!empty($diff))
          $result[$k] = $diff;
      }
    }
  }
  return $result;
}