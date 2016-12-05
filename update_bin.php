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
include 'core/functions/barcode.php';
include 'includes/overall/header.php';

/* Variable to hold the scanned slabs */
$scanned_barcode =   trim($_GET['code']);

$barcode_length = strlen(sanitize($scanned_barcode));
if($scanned_barcode == '' || $barcode_length == 0){
    echo '<h3>Please scan Slab/Bin properly.</h3>';
    exit;
}else{ 
    $isBinOrSlab = isBinByBarcode($scanned_barcode);
    if($isBinOrSlab === false){
        /*
         * Slab Scanned
         * Description - Once the scanned barcode is slab then store in variable which latter will be pushed to Scanned Bin
         */
        if(in_array($scanned_barcode, $_SESSION['scanned_slabs']) == false){
            $_SESSION['scanned_slabs'][] = $scanned_barcode;
        }
        header("Location: zxing://scan/?ret=".$base_url."update_bin.php?code={CODE}");
    }else if($isBinOrSlab === true){
        /*
         * Bin Scanned
         * Once Bin is scanned all the slabs will be pushed to this Bin
         */
        global $link;

        $bin_name = $scanned_barcode;
        if(count($_SESSION['scanned_slabs']) > 0){
            $bin_details = mysqli_fetch_assoc(mysqli_query($link,"SELECT `id` FROM `bins` WHERE name='$bin_name'"));
            $count = 0;
            $final_inventory_master_details = array();
            foreach($_SESSION['scanned_slabs'] as $key=>$unique_values){
                $inventory_master_query =   "SELECT im.sku, bi.isipl_number, b.name as bin_name"
                                            . " FROM inventory_master im"
                                            . " JOIN bins b ON b.id = im.binid"
                                            . " JOIN block_inventory bi ON bi.id = im.blockinventoryid"
                                            . " WHERE im.uniqueNumber = '$unique_values'";
                $inventory_master_details = mysqli_query($link, $inventory_master_query);

                $currenDateTime = date('Y-m-d H:i:s');
                $im_result = mysqli_query($link,"UPDATE `inventory_master` SET `binId` = '$bin_details[id]', `isStockStatusVerified` = 1, `lastVerifiedDate`='$currenDateTime' WHERE `uniqueNumber` = '$unique_values'");
                if($im_result){
                    //Updated successfully now store the details of the "inventory_master" table to dispaly
                    while($row = mysqli_fetch_assoc($inventory_master_details)){
                        $final_inventory_master_details[$key]['sku']            = $row['sku'];
                        $final_inventory_master_details[$key]['isipl_number']   = $row['isipl_number'];
                        $final_inventory_master_details[$key]['old_bin_name']   = $row['bin_name'];
                        $final_inventory_master_details[$key]['new_bin_name']   = $bin_name;
                    }
                    $count++;
                }
            }
            echo '<div class="text-success">Updated '.$count.' slabs with bin number to '.$bin_name.'</div> <br />';

            ?>
            <a href="<?php echo $base_url; ?>home.php" class="text-danger">Click Here</a> to go to dashboard <br /><br />
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>ISIPL</th>
                            <th>Old Bin</th>
                            <th>New Bin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($final_inventory_master_details as $final_inventory_master_detail){ ?>
                        <tr>
                            <td><?php echo $final_inventory_master_detail['sku']; ?></td>
                            <td><?php echo $final_inventory_master_detail['isipl_number']; ?></td>
                            <td><?php echo $final_inventory_master_detail['old_bin_name']; ?></td>
                            <td><?php echo $final_inventory_master_detail['new_bin_name']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
        }else{
            echo '<div class="text-danger">No slabs selected please select</div> <br />';
        }
        unset($_SESSION['scanned_slabs']);
        ?>
    <?php
    }
}

include 'includes/overall/footer.php';