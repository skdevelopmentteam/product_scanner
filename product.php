<?php

/*
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */
?>
<?php

include 'core/init.php';
protected_page();
include_once 'core/functions/area_helper.php';
//  'inventory_finish'.name as finish, 'thickness'.name as thickness, 'inventory_location'.locName as location 
function slab_details($uniqueNumber = null) {
    global $link;
    $uniqueNumber = sanitize($uniqueNumber);
    $query = " SELECT "
            . " inventory_master.*, `block_inventory`.isipl_number as isipl, `inventory_names`.name as color, `inventory_finish`.name as finish, `thickness`.name as thickness, `inventory_location`.locName as location, `bins`.name as bin, `inventory_category`.name as category, `grades`.name as grade, u.name as unit"
            . " FROM `inventory_master`"
            . " JOIN `inventory_names` on inventory_master.inventoryNameId=inventory_names.id "
            . " JOIN `grades` on inventory_master.gradeId=grades.id "
            . " JOIN `inventory_finish` on inventory_master.inventoryFinishId=inventory_finish.id "
            . " JOIN `block_inventory` on inventory_master.blockInventoryId=block_inventory.id "
            . " JOIN `thickness` on inventory_master.thicknessId=thickness.id "
            . " JOIN `inventory_location` on inventory_master.inventLocId=inventory_location.id "
            . " JOIN `bins` on inventory_master.binId=bins.id "
            . " JOIN `inventory_category` on inventory_master.inventCatId=inventory_category.id "
            . " JOIN `unit` u on inventory_master.unitid=u.id"
            . " WHERE uniqueNumber = '$uniqueNumber'";
    $foundSlab = mysqli_fetch_assoc(mysqli_query($link, $query)) or die(mysqli_error($link));
    $inventoryNameId = $foundSlab['inventoryNameId'];
    $blockInventoryId = $foundSlab['blockInventoryId'];
    $relatedSlabs = array();
    $overall_area = 0;
    $relatedSlabsQuery = "SELECT `thickness`.id as thickness_id,`thickness`.name as thickness, im.netLength, im.netHeight, u.name as unit, tu.name as thickness_unit"
            . " FROM `inventory_master` im"
            . " JOIN `thickness` on im.thicknessId=thickness.id"
            . " JOIN `unit` u on im.unitid=u.id"
            . " JOIN `unit` tu on thickness.unitid=u.id"
            . " WHERE im.inventoryNameId = '$inventoryNameId' AND im.status = 'Available'";
    $relatedSlabsResult = mysqli_query($link, $relatedSlabsQuery);
    while ($row = mysqli_fetch_assoc($relatedSlabsResult)) {
        $key = $row['thickness'];
        $overall_area = convertArea($row['netLength'], $row['netHeight'], $row['unit']);
        if (!isset($relatedSlabs[$key])) {
            //Not set
            $relatedSlabs[$key] = array(
                'thickness'         => $row['thickness'],
                'overall_area'      => $overall_area,
                'thickness_unit'    => $row['thickness_unit'],
                'thickness_id'      => $row['thickness_id'],
                'inventory_name_id' => $inventoryNameId,
                'count'             => 1
            );
        } else {
            //Is set
            $relatedSlabs[$key]['overall_area'] += $overall_area;
            $relatedSlabs[$key]['count'] += 1;
        }
    }
    $slab_image = '';
    /* Check whether the slab image is there or not, if not then fetch from any slab picutre */
    if(isset($foundSlab['pictureid']) && $foundSlab['pictureid'] == NULL){
        $slab_image = mysqli_fetch_assoc(mysqli_query($link, "SELECT pictureid FROM `inventory_master` WHERE (blockinventoryid = $blockInventoryId) AND (pictureid IS NOT NULL) LIMIT 1"));
    }
    $response['row'] = $foundSlab;
    $response['relatedSlabs'] = $relatedSlabs;
    $response['slab_image'] = ($slab_image != '') ? $slab_image['pictureid'] : '';
    return $response;
}

function price_details($uniqueNumber = null) {
    global $link;
    $uniqueNumber = sanitize($uniqueNumber);
    $result = mysqli_query($link, "select t.id as thickness,n.id as color,g.id as grade"
            . " from inventory_master m,"
            . "inventory_names n,"
            . "grades as g,"
            . "thickness as t "
            . "where m.inventoryNameId=n.id"
            . " and m.thicknessId=t.id"
            . " and m.gradeId=g.id"
            . " and m.uniqueNumber='$uniqueNumber'");
    $response = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $colorId = $row['color'];
        $gradeId = $row['grade'];
        $thicknessId = $row['thickness'];
    }
    $current = mysqli_query($link, "select t.name as thickness,g.name as grade,n.name as color, i.price"
            . " from item_master i"
            . " ,inventory_names n,"
            . " grades g,"
            . " thickness t"
            . " where "
            . " i.thicknessId='$thicknessId' and"
            . " n.id=i.inventoryNameId and"
            . " g.id='$gradeId' and"
            . " t.id='$thicknessId' and"
            . " i.gradeId='$gradeId' and"
            . " i.inventoryNameId='$colorId'");
    while ($row = mysqli_fetch_assoc($current)) {
        $response['current'][] = $row;
    }

    $prices = mysqli_query($link, "SELECT n.name as color,g.name as grade,t.name as thickness,m.price as price "
            . "from item_master as m,"
            . "inventory_names as n,"
            . "grades as g,"
            . "thickness as t,"
            . "unit as u"
            . " where m.inventoryNameId='$colorId'"
            . " and m.thicknessId=t.id"
            . " and m.gradeId=g.id"
            . " and m.inventoryNameId=n.id"
            . " and u.id=m.unitId");

    while ($row = mysqli_fetch_assoc($prices)) {
        if ($response['current'][0]['thickness'] == $row['thickness'] && $response['current'][0]['grade'] == $row['grade'] && $response['current'][0]['thickness'] == $row['thickness'])
            continue;
        $response['others'][] = ($row);
    }
    return($response);
}

function available_check($uniqueNumber = null) {
    global $link;
    $inventory_name_query = "SELECT inn.*"
            . " FROM `inventory_master` im"
            . " JOIN `inventory_names` inn ON im.inventoryNameId=inn.id"
            . " WHERE `uniqueNumber`='$uniqueNumber'";

    $inventory_name_result = mysqli_query($link, $inventory_name_query);
    $inventory_name_details = mysqli_fetch_assoc($inventory_name_result);
    /* Check if No of rows is greater than  1 */
    if (mysqli_num_rows($inventory_name_result) > 0) {
        $inventory_name_id = $inventory_name_details['id'];
        $inventory_name = $inventory_name_details['name'];

        // global array to store SLABS and BLOCKS details 
        $available_details = array();

        // array to store SLABS details 
        $available_slab_details = array();

        // array to store BLOCKS details 
        $available_block_details = array();

        $slabs_available_details = array();

        $status_id_query = "SELECT id,name FROM `status` WHERE `name`='cut' OR `name`='available'";
        $status_id_result = mysqli_query($link, $status_id_query);
        $available = null;
        $cut = null;
        while ($row1 = mysqli_fetch_assoc($status_id_result)) {
            if (strtolower($row1['name']) == 'available') {
                $available = $row1['id'];
            } else if (strtolower($row1['name']) == 'cut') {
                $cut = $row1['id'];
            }
        }

        $blocks_available_query = "SELECT bi.id as block_inventory_id,bi.netLength,bi.netHeight,bi.netWidth,bi.area as block_area,bi.statusId"
                . " FROM `block_inventory` bi"
                . " WHERE `inventoryNameId`=$inventory_name_id AND (bi.statusId=$available OR bi.statusId=$cut)";

        $blocks_available_result = mysqli_query($link, $blocks_available_query);
        $i = 0;
        while ($row = mysqli_fetch_assoc($blocks_available_result)) {
            $available_block_details[] = $row;
            if ($row['statusId'] == $available) {
                $available_block_details[$i++]['status_name'] = 'available';
            } else if ($row['statusId'] == $cut) {
                $available_block_details[$i++]['status_name'] = 'cut';
            }
        }

        /* if BLOCK then directly calculate area from block_inventory area
         * else go and check all the SLABS in inventory_master which are available and based on the thickness calcuate the area seperately
         */
        $block_area = 0;
        foreach ($available_block_details as $available_block_detail) {
            if ($available_block_detail['status_name'] == 'block') {
                $block_area += $available_block_detail['block_area'];
            } else if ($available_block_detail['status_name'] == 'cut') {
                $block_inventory_id = $available_block_detail['block_inventory_id'];
                $slabs_available_query = "SELECT t.name as thickness_unit,t.value as thickness,im.unit,SUM(im.netLength*im.netHeight) as area"
                        . " FROM `inventory_master` as im"
                        . " JOIN `thickness` t"
                        . " ON im.thicknessId=t.id"
                        . " WHERE `blockInventoryId`=$block_inventory_id and `status`='Available'"
                        . " GROUP BY `thicknessId`";

                $slabs_available_result = mysqli_query($link, $slabs_available_query);

                while ($row = mysqli_fetch_assoc($slabs_available_result)) {
                    $slabs_available_details[] = $row;
                }

                foreach ($slabs_available_details as $key => $item) {
                    $key = $item['thickness'];
                    if (!isset($available_slab_details[$key])) {
                        $available_slab_details[$key] = array(
                            'thickness_unit' => $item['thickness_unit'],
                            'thickness' => $item['thickness'],
                            'area' => $item['area']
                        );
                    } else {
                        $available_slab_details[$key]['area'] += $item['area'];
                    }
                }
            }
        }
        $available_details['available_block_area'] = $block_area;
        $available_details['available_salb_area'] = $available_slab_details;
        $available_details['inventory_name'] = $inventory_name;
        return $available_details;
    }
}
/* Fetches InventoryMaster table details based on the unique number */
function getInventoryMasterDetails($uniqueNumber) {
    global $link;
    $inventory_name_query = "SELECT *"
            . " FROM `inventory_master`"
            . " WHERE `uniqueNumber`='$uniqueNumber'";
    $inventory_name_result = mysqli_fetch_assoc(mysqli_query($link, $inventory_name_query));
    return ($inventory_name_result != NULL) ? $inventory_name_result : FALSE;
}

/* Fetches InventoryMaster table details based on the unique number */
function getInventoryMasterDetailsByIsipl($isiplNumber) {
    global $link;
    $inventory_name_query = "SELECT count(im.id) as slabs_count,b.name as bin_name"
            . " FROM `inventory_master` im"
            . " JOIN `block_inventory` bi ON bi.id = im.blockinventoryid"
            . " JOIN `bins` b ON b.id = im.binid"
            . " WHERE bi.`isipl_number`='$isiplNumber' AND im.status = 'Available'"
            . " GROUP BY im.binid";
    $inventory_name_details = mysqli_query($link, $inventory_name_query) or die(mysqli_error($link));
    $final_inventory_details = array();
    while ($row =  mysqli_fetch_assoc($inventory_name_details)) {
        $final_inventory_details[] = $row;
    }
    return (count($final_inventory_details) > 0) ? $final_inventory_details : FALSE;
}

function getInventoryMasterDetailsByBinId($uniqueNumber) {
    global $link;
    $uniqueNumber = $uniqueNumber;
    $inventorymasterDetails = getInventoryMasterDetails($uniqueNumber);
    $binId = $inventorymasterDetails['binId'];
    $finalBlockInventoryDetails = array();
    if($binId !== FALSE){
        $sql = "SELECT count(im.id) as total_slabs,sum(im.netlength * im.netheight) slab_area,im.binid,t.value as thickness_value, t.name as thickness_name,uu.name as thickness_unit, u.sqname as unit_name"
            . " FROM `inventory_master` im JOIN thickness t on t.id = im.thicknessid"
            . " JOIN unit u on u.id = im.unitid"
            . " JOIN unit uu on uu.id = t.unitid"
            . " WHERE im.binid = $binId AND im.status = 'Available'"
            . " GROUP BY im.thicknessid";
        $blockInventoryDetails =mysqli_query($link, $sql);
        while ($row =  mysqli_fetch_assoc($blockInventoryDetails)) {
            $finalBlockInventoryDetails[] = $row;
        }
    }
    return $finalBlockInventoryDetails;
}

function block_slabs_details($uniqueNumber){
    global $link;
    $uniqueNumber = $uniqueNumber;
    $inventorymasterDetails = getInventoryMasterDetails($uniqueNumber);
    $blockInventoryId = $inventorymasterDetails['blockInventoryId'];
    $query = "SELECT COUNT(im.id) as count,SUM(im.netlength * im.netheight) as area,u.sqname as area_unit, uu.name as thickness_unit,t.name as thickness_name"
            . " FROM inventory_master im"
            . " JOIN thickness t on t.id = im.thicknessid"
            . " JOIN unit u on u.id = im.unitid"
            . " JOIN unit uu on uu.id = t.unitid"
            . " WHERE im.blockinventoryid = $blockInventoryId AND im.status = 'Available'"
            . " GROUP BY im.thicknessid";
    $result = mysqli_query($link,$query);
    while($row = mysqli_fetch_assoc($result)){
        $blockSlabDetails[] = $row;
    }
    
    return $blockSlabDetails;
}