<?php

/* 
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 * @Description:   This file will INITALIZE DATABASE and FUNCTIONS files from the CORE folder
 */

/* Start Session Here */
if(!isset($_SESSION)){
    session_start();
}

date_default_timezone_set('Asia/Kolkata');

/* 
 * Define the Environment that you are gonna work, 
 * change the mode to DEVELOPMENT when developing on the project like in the localhost
 * change the mode to PRODUCTION when HOSTING in SERVER
 */
if(!defined('ENVIRONMENT')){
    define('ENVIRONMENT','DEVELOPMENT');
}

$base_url   =   null;
$image_url  =   null;

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'DEVELOPMENT':
                        $base_url   =   'http://localhost/product_scanner/';
                        $image_url  =   'http://localhost/skerp4/web/uploads/';
			error_reporting(E_ALL);
		break;
	
		case 'PRODUCTION':
                        $base_url   =   'http://106.51.251.51/product_scanner/';
                        $image_url  =   'http://106.51.251.51/skerp2/web/uploads/';
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}

/* Include the DATABASE FILES */
require_once 'database/connect.php';

/* Include the FUNCTION FILES */
require_once 'functions/general.php';

/* Include the user related functions */
require_once 'functions/user.php';

/* Array to store the errors and display them globally */
$errors =   array();

/* If the USER is LOGGED IN then fetch all the user-data and make it available througt the application */
if(is_loggedin() === true){
    //fetch the userdid from the session which is set in 'authenticate.php'
    $userid =   $_SESSION['userid'];
    /*Pass 1st parameter as userid
     * Then pass all the parameters which you want to fetch from the user table
     */
    $user_details   =   user_data_from_userid($userid,'id','email','password','firstname','lastname','erpAccess','roleId','deptId','employeeId','managerId','isActive','picture','status');
    
    if(is_active($user_details['email']) === false){
        session_destroy();
        header('Location: logout.php');
        exit();
    }
}

