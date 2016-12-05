<?php
/*
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

include_once 'core/init.php';
protected_page();
include 'includes/overall/header.php';

if(empty($_POST) === FALSE){
    global $link;
    $prepackage_no = preg_replace('/\\s/', '', $_POST['prepackage_no']);
    $result = mysqli_query($link, "SELECT * FROM `pre_package` WHERE number = '$prepackage_no'");
    if(mysqli_num_rows($result) > 0){
        /* Store Pre Package Details */
        $prepackage_details = mysqli_fetch_assoc($result);
        /* Unset all the previous details */
        unset($_SESSION['check_customer_materials']);
        unset($_SESSION['check_customer_materials_scanned']);
        
        $_SESSION['check_customer_materials']['pre_package_details'] = $prepackage_details;
        
        $query = "SELECT im.id as inventorymasterid, ic.name as color, g.name as grade, t.value as thickness, u.name as thickness_unit, f.name as finish FROM pre_package_items ppi
                JOIN pre_package_bundle ppb ON ppb.id = ppi.prepackagebundleid
                JOIN pre_package pp ON pp.id = ppb.prepackageid
                JOIN inventory_master im ON im.id = ppi.inventorymasterid
                JOIN inventory_names ic ON ic.id = im.inventorynameid
                JOIN grades g ON g.id = im.gradeid
                JOIN thickness t ON t.id = im.thicknessid
                JOIN unit u ON u.id = t.unitid
                JOIN inventory_finish f ON f.id = im.inventoryfinishid
                WHERE pp.number = '".$prepackage_details['number']."'";
        
        $inventory_details = mysqli_query($link, $query);
        $prepackage_inventory_items = array();
        $inventory_ids = array();
        /* Group the packing materials by COLOR, GRADE, THICKNESS AND FINISH */
        $packing_materials_specs = array();
        
        if(mysqli_num_rows($inventory_details) > 0){
            while($row = mysqli_fetch_assoc($inventory_details)){
                $inventoryid    = $row['inventorymasterid'];
                $color          = $row['color'];
                $inventory_color= strtolower(preg_replace('/\\s/', '_', $color));
                $grade          = $row['grade'];
                $thickness      = $row['thickness'];
                $thickness_unit = $row['thickness_unit'];
                $finish         = $row['finish'];
                /* Capturing the inventory ids */
                $inventory_ids[] = $inventoryid;
                /* Create the array of materials by COLOR, GRADE, THICKNESS AND FINISH */
                $specs_key = $inventory_color.$thickness.$thickness_unit.$grade.$finish;
                if(!isset($packing_materials_specs[$specs_key])){
                    $packing_materials_specs[$specs_key]['color']       = trim($color);
                    $packing_materials_specs[$specs_key]['grade']       = $grade;
                    $packing_materials_specs[$specs_key]['thickness']   = $thickness.$thickness_unit;
                    $packing_materials_specs[$specs_key]['finish']      = trim($finish);
                    $packing_materials_specs[$specs_key]['count']       = 1;
                }else{
                    $packing_materials_specs[$specs_key]['count']       += 1;
                }
            }
        }
        $_SESSION['check_customer_materials']['inventory_master_ids'] = $inventory_ids;
        $_SESSION['check_customer_materials']['packing_material_specs'] = $packing_materials_specs;
        header("Location: zxing://scan/?ret=".$base_url."check_customer_material_slabs.php?code={CODE}");
    }else{
        echo  '<span class="text-danger">No records found for Packing List.</span>';
    }
}

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="row">
        <div class="col-md-12">
            <label for="prepackage_no">Packing List#</label>
            <input type="text" name="prepackage_no" id="prepackage_no" class="form-control" placeholder="Enter Packing List#" required="required" />
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-12">
            <input type="submit" value="Start to Check" class="btn btn-primary" />
        </div>
    </div>
</form>
<br /><br />
<?php include 'includes/overall/footer.php';