<?php
/*
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */
?>

<?php
include_once 'core/init.php';
protected_page();
include 'includes/overall/header.php';

if(empty($_POST) === FALSE){
    $pickticket_no = sanitize($_POST['pickticket_no']);
    global $link;
    $query = "SELECT b.name as bin_name,im.sku,im.uniquenumber"
            . " FROM `pre_package_items` ppi"
            . " JOIN `inventory_master` im ON im.id = ppi.inventorymasterid"
            . " JOIN `bins` b ON b.id = im.binid"
            . " JOIN `pre_package_bundle` ppb ON ppb.id = ppi.prepackagebundleid"
            . " JOIN `pre_package` pp ON pp.id = ppb.prepackageid"
            . " WHERE pp.number = '$pickticket_no' AND ppi.confirm IN (0,1)";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $pickticket_records = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $pickticket_records[] = $row;
    }
    $final_pickticket_records = array();
    foreach($pickticket_records as $key=>$pickticket_record){
        $unique_key = $pickticket_record['bin_name'];
        if(!isset($final_pickticket_records[$unique_key])){
            $final_pickticket_records[$unique_key]     = array(
                'bin_name'=>$pickticket_record['bin_name'],
                'inventory_details' => array(
                    array(
                        'sku'=>$pickticket_record['sku'],
                        'unique_no'=>$pickticket_record['uniquenumber']
                    )
                )
            );
        }else{
            $final_pickticket_records[$unique_key]['inventory_details'][] = array(
                'sku'=>$pickticket_record['sku'],
                'unique_no'=>$pickticket_record['uniquenumber']
            );
        }
    }
    
    ?>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th rowspan="2" class="text-center">Bin Name</th>
                <th colspan="2" class="text-center">PickTicket Slabs</th>
            </tr>
            <tr>
                <th class="text-center">SKU</th>
                <th class="text-center">Unique No</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if(count($final_pickticket_records) > 0){
                foreach ($final_pickticket_records as $final_pickticket_record) {
                    if(count($final_pickticket_record['inventory_details']) == 1){
                ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $final_pickticket_record['bin_name']; ?>
                        </td>
                        <td>
                            <?php echo $final_pickticket_record['inventory_details'][0]['sku']; ?>
                        </td>
                        <td>
                            <?php echo $final_pickticket_record['inventory_details'][0]['unique_no']; ?>
                        </td>
                    </tr>
            <?php 
                    }else if(count($final_pickticket_record['inventory_details']) > 1){
                        foreach ($final_pickticket_record['inventory_details'] as $key => $inventory_details) {
                            if($key == 0){ ?>
                                <tr>
                                    <td rowspan="<?php echo count($final_pickticket_record['inventory_details']); ?>"  class="text-center">
                                        <?php echo $final_pickticket_record['bin_name'];  ?>
                                    </td>
                                    <td>
                                        <?php echo $inventory_details['sku']; ?>
                                    </td>
                                    <td>
                                        <?php echo $inventory_details['unique_no']; ?>
                                    </td>
                                </tr>
                            <?php
                            }else{
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $inventory_details['sku']; ?>
                                    </td>
                                    <td>
                                        <?php echo $inventory_details['unique_no']; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                }
            }else{ ?>
            <tr>
                <td colspan="3"><h3 class="text-danger text-center">No Records Found</h3></td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>

    <?php
}

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="row">
        <div class="col-md-12">
            <label for="pickticket_no">Pick Ticket#</label>
            <input type="text" name="pickticket_no" id="pickticket_no" class="form-control" placeholder="Enter Pick Ticket#" required="required" />
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-12">
            <input type="submit" value="Get Details" class="btn btn-primary" />
        </div>
    </div>
</form>
<br /><br />
<?php include 'includes/overall/footer.php';