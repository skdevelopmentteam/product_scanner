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
/* Variable to hold the scanned slabs */
$scanned_barcode =   isset($_GET['code']) ? trim($_GET['code']) : '';
if(!isset($_SESSION['slabs_dispatched'])){
	$_SESSION['slabs_dispatched'] = array();
}
$dispatchDetailsUpdatedSlabs = array();
$barcode_length = strlen(sanitize($scanned_barcode));
if($scanned_barcode !== '' || $barcode_length !== 0){
	if(in_array($scanned_barcode, $_SESSION['slabs_dispatched']) == false){
		/* If slab in not scanned earlier than store in the array */
        $_SESSION['slabs_dispatched'][] = $scanned_barcode;
        header("Location: zxing://scan/?ret=".$base_url."container_slab_dispatched.php?code={CODE}");
    }else{
		/* Update the slab dispatch details in inventory master */
		if(count($_SESSION['slabs_dispatched']) > 0){
			$currenDateTime = date('Y-m-d H:i:s');
			$count = 0;
			foreach($_SESSION['slabs_dispatched'] as $key=>$uniqueNumber){
				$query = "UPDATE `inventory_master` SET `isDispatched` = 1, `dispatchedDate` = '$currenDateTime' WHERE uniqueNumber = '$uniqueNumber'";
				if(mysqli_query($link,$query)){
					$inventory_master_query =   "SELECT im.sku, bi.isipl_number, im.serailNo"
                                            . " FROM inventory_master im"
                                            . " JOIN bins b ON b.id = im.binid"
                                            . " JOIN block_inventory bi ON bi.id = im.blockinventoryid"
                                            . " WHERE im.uniqueNumber = '$uniqueNumber'";
	                $inventory_master_details = mysqli_query($link, $inventory_master_query) or die(mysqli_error($link));

                    while($row = mysqli_fetch_assoc($inventory_master_details)){
                        $dispatchDetailsUpdatedSlabs[$key]['sku']           = $row['sku'];
                        $dispatchDetailsUpdatedSlabs[$key]['isipl_number']	= $row['isipl_number'];
                        $dispatchDetailsUpdatedSlabs[$key]['slab_number']	= $row['serailNo'];
                    }
                    $count++;
				}
			}
			unset($_SESSION['slabs_dispatched']);
			echo '<div class="text-success">Updated '.$count.' slabs as dispacted on '.$currenDateTime.'</div> <br />';
			?>
			<a href="<?php echo $base_url; ?>home.php" class="text-danger">Click Here</a> to go to dashboard <br /><br />
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                        	<th>Slab #</th>
                            <th>SKU</th>
                            <th>ISIPL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dispatchDetailsUpdatedSlabs as $dispatchDetailsUpdatedSlab){ ?>
                        <tr>
                            <td><?php echo $dispatchDetailsUpdatedSlab['slab_number']; ?></td>
                            <td><?php echo $dispatchDetailsUpdatedSlab['sku']; ?></td>
                            <td><?php echo $dispatchDetailsUpdatedSlab['isipl_number']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
		<?php
		}
	}
}
?>
<div class="row col-md-12">
	<strong class="text-danger">NOTE - Scan slab twice to end the scanning...</strong> <br><br>
	<a class="btn btn-primary" href="zxing://scan/?ret=<?php echo $base_url; ?>container_slab_dispatched.php?code={CODE}">
	    Start Scan
	</a>
</div>