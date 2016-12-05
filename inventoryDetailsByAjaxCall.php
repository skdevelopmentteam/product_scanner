<?php

/* 
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

include_once 'core/init.php';
include_once 'core/functions/area_helper.php';
global $link;

$inventoryNameId    = (int)$_POST['inventorynameid'];
$thicknessId        = (int)$_POST['thicknessid'];

$relatedSlabsQuery = "SELECT count(im.`id`) as slab_count,b.name as bin_name,sum(im.`netLength` * im.`netHeight`) as slab_area, bi.isipl_number, u.name, u.sqname"
        . " FROM `inventory_master` im"
        . " JOIN bins b on b.id = im.binid"
        . " JOIN unit u on u.id = im.unitid"
        . " JOIN block_inventory bi on bi.id = im.blockinventoryid"
        . " WHERE im.inventorynameid = $inventoryNameId and im.thicknessid = $thicknessId and im.status = 'Available'"
        . " GROUP BY im.blockinventoryid,b.id";
$relatedSlabsResult = mysqli_query($link, $relatedSlabsQuery);
$finalrelatedSlabsResult = array();
$i=0;
while($row = mysqli_fetch_assoc($relatedSlabsResult)){
    $finalrelatedSlabsResult[$i]['slab_count'] = $row['slab_count'];
    $finalrelatedSlabsResult[$i]['bin_name'] = $row['bin_name'];
    $finalrelatedSlabsResult[$i]['isipl_number'] = $row['isipl_number'];
    $finalrelatedSlabsResult[$i]['slab_area'] = number_format((getUnitConvertedArea($row['slab_area'], $row['sqname'], 'sqfeet')),3,'.','');
    $i++;
}
echo json_encode(array('finalRelatedSlabDetails'=>$finalrelatedSlabsResult));