<?php

/* 
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

function isBinByBarcode($barcode){
    global $link;
    $barcode    = sanitize($barcode);
    $result     =   mysqli_query($link, "SELECT count(`id`) FROM `bins` WHERE `name`='$barcode'");
    $row        =   mysqli_fetch_row($result);
    return ($row[0] > 0) ? true : false;
}
