<?php

/* 
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

$host       =   'localhost';
$user       =   'root';
$password   =   '';
$database   =   'skerp2';

$connection_error   =   'Sorry!!! We are experiencing problems with the database settings';

$link   =   mysqli_connect($host, $user, $password, $database) or DIE($connection_error);