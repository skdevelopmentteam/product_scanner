<?php
/*
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

include_once 'core/init.php';
protected_page();
include 'includes/overall/header.php';
/* If the page is not POSTED and material is Scanned then the following code is executed */
if(isset($_GET['code'])){
    global $link;
    $scanned_barcode =   trim($_GET['code']);
    $barcode_length = strlen(sanitize($scanned_barcode));
    if($scanned_barcode == '' || $barcode_length == 0){
        echo '<h3>Please scan Slab properly.</h3>';
    }else{
        if(!isset($_SESSION['check_customer_materials_scanned'])){
            $_SESSION['check_customer_materials_scanned'] = array();
        }
        if(!isset($_SESSION['check_customer_materials_scanned']['scanned_materials_specs'])){
            $_SESSION['check_customer_materials_scanned']['scanned_materials_specs'] = array();
        }
        if(!isset($_SESSION['check_customer_materials_scanned']['inventory_master_ids'])){
            $_SESSION['check_customer_materials_scanned']['inventory_master_ids'] = array();
        }
        $query = "SELECT im.id as inventorymasterid, ic.name as color, g.name as grade, t.value as thickness, u.name as thickness_unit, f.name as finish
                FROM inventory_master im 
                JOIN inventory_names ic ON ic.id = im.inventorynameid
                JOIN grades g ON g.id = im.gradeid
                JOIN thickness t ON t.id = im.thicknessid
                JOIN unit u ON u.id = t.unitid
                JOIN inventory_finish f ON f.id = im.inventoryfinishid
                WHERE im.uniqueNumber = '$scanned_barcode'";
        $inventory_master_details = mysqli_query($link, $query);
        $scanned_materials_specs = array();
        if(mysqli_num_rows($inventory_master_details) > 0){
            $inventory_details  = mysqli_fetch_assoc($inventory_master_details);
            $inventoryid        = $inventory_details['inventorymasterid'];
            /* Search the array for any duplicate material if any then dont store in an array */
            if(array_search($inventoryid,$_SESSION['check_customer_materials_scanned']['inventory_master_ids']) == FALSE){
                $color          = $inventory_details['color'];
                $inventory_color= strtolower(preg_replace('/\\s/', '_', $color));
                $grade          = $inventory_details['grade'];
                $thickness      = $inventory_details['thickness'];
                $thickness_unit = $inventory_details['thickness_unit'];
                $finish         = $inventory_details['finish'];
                /* Create the array of materials by COLOR, GRADE, THICKNESS AND FINISH */
                $specs_key = $inventory_color.$thickness.$thickness_unit.$grade.$finish;
                if(!isset($_SESSION['check_customer_materials_scanned']['scanned_materials_specs'][$specs_key])){
                    $_SESSION['check_customer_materials_scanned']['scanned_materials_specs'][$specs_key]['color']       = trim($color);
                    $_SESSION['check_customer_materials_scanned']['scanned_materials_specs'][$specs_key]['grade']       = $grade;
                    $_SESSION['check_customer_materials_scanned']['scanned_materials_specs'][$specs_key]['thickness']   = $thickness.$thickness_unit;
                    $_SESSION['check_customer_materials_scanned']['scanned_materials_specs'][$specs_key]['finish']      = trim($finish);
                    $_SESSION['check_customer_materials_scanned']['scanned_materials_specs'][$specs_key]['count']       = 1;
                }else{
                    $_SESSION['check_customer_materials_scanned']['scanned_materials_specs'][$specs_key]['count']       += 1;
                }

                $_SESSION['check_customer_materials_scanned']['inventory_master_ids'][]     = $inventoryid;
            }
        }
    }
}
/* On click of compare button the form will be POST and the following code will be executed */
if(isset($_POST['compare_materials']) === TRUE && $_SERVER['REQUEST_METHOD'] == 'POST'){
    $msg_error = '';
    $email_error = '';
    /* Array Unique is used to remove the duplicate values from the array */
    $packing_inventory_ids = array_unique($_SESSION['check_customer_materials']['inventory_master_ids']);
    $packing_material_specs = $_SESSION['check_customer_materials']['packing_material_specs'];
    $scanned_inventory_ids = array_unique($_SESSION['check_customer_materials_scanned']['inventory_master_ids']);
    $scanned_materials_specs = $_SESSION['check_customer_materials_scanned']['scanned_materials_specs'];
    $packing_list_details = $_SESSION['check_customer_materials']['pre_package_details'];
    if(isset($packing_inventory_ids) === TRUE && isset($scanned_inventory_ids) === TRUE && isset($packing_list_details)){
        if(count($packing_inventory_ids) > count($scanned_inventory_ids)){
            $msg_error .= 'Scanned materials are less than that of Packing List materials for '.$packing_list_details['number'];
        }else if(count($packing_inventory_ids) < count($scanned_inventory_ids)){
            $msg_error .= 'Scanned materials are greater than that of Packing List materials for '.$packing_list_details['number'];
        }
    }
    if(isset($packing_material_specs) === TRUE && isset($scanned_materials_specs) === TRUE && isset($packing_list_details)){
        foreach($scanned_materials_specs as $key=>$scanned_materials){
            $scanned_color      = $scanned_materials['color'];
            $scanned_grade      = $scanned_materials['grade'];
            $scanned_thickness  = $scanned_materials['thickness'];
            $scanned_finish     = $scanned_materials['finish'];
            if(array_key_exists($key, $packing_material_specs) === TRUE){
                /* Scanned material found in packing list */
                if($scanned_materials['count'] != $packing_material_specs[$key]['count']){
                    /* If the count are not matching then different material is loaded */
                    $email_error .= 'Material Not Matching, Inventory Color - '.$scanned_color.', Grade - '.$scanned_grade.', Thickness - '.$scanned_thickness.', Finish - '.$scanned_finish.' is not found in Paking List ('.$packing_list_details['number'].').<br/>';
                }
                
            }else{
                /* Scanned material not found in packing list */
                $email_error .= 'Inventory Color - '.$scanned_color.', Grade - '.$scanned_grade.', Thickness - '.$scanned_thickness.', Finish - '.$scanned_finish.' is not found in Paking List ('.$packing_list_details['number'].').<br/>';
            }
        }
    }
    $person_to_notify = array(
        'Himanshu'=>array(
            'contact'=>9344701326,
            'email'=>'himanshu@skworld.co'
        ),
        'Mohit'=>array(
            'contact'=>8870478182,
            'email'=>'mohit.isipl@gmail.com'
        )
    );
    /* If any error is set */
    if($msg_error != ''){
        include_once 'core/helpers/BashSmsHelper.php';
        if(isset($packing_list_details)){
            $packing_no = $packing_list_details['number'];
            $bashHelper = new BashSmsHelper();
            /* Himanshu and Mohit */
            $message = $packing_no.' Paking List materials are not matching. Check your Email';
            foreach($person_to_notify as $person){
                $bashHelper->sendSms($person['contact'], $message);
            }
            echo $message;
        }
    }
    
    /* If any error is set */
    if($email_error != ''){
        $to_email = 'Himanshu <himanshu@skworld.co>, Mohit <mohit.isipl@gmail.com>';
        //$to_email = 'Channaveer Hakari <channaveer888@gmail.com>';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($to_email,'Packing List Miss Match',$email_error,$headers);
    }
}

?>
<div class="row">
    <div class="col-md-12">
        <a href='zxing://scan/?ret=".$base_url."check_customer_material_slabs.php?code={CODE}' class="btn btn-success">
            Scan Material
        </a>
    </div>
</div>
<br />
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="row">
        <div class="col-md-12">
            <input type="submit" name="compare_materials" value="Compare Materials" class="btn btn-primary" />
        </div>
    </div>
</form>
<br /><br />
NOTE - On click of Compare Materials, if all the slabs are not scanned then must start the process from scratch.
<br /><br />
<?php include 'includes/overall/footer.php';