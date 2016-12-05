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
include 'product.php';
include 'includes/overall/header.php';

$avilable_check_details =   available_check($_GET['code']);
?>
<h2>Product Availability List</h2>
<!--<h2>
    <small><?php echo $avilable_check_details['inventory_name']; ?></small>
</h2>-->

<h3>
    <small>
        Total Block Area Available for 
        <span class="text-success">
            <?php echo $avilable_check_details['inventory_name']; ?>
        </span> 
    </small>
</h3>
<?php 
if($avilable_check_details['available_block_area'] > 0){
    echo $avilable_check_details['available_block_area']; 
}else{
    echo '<div class="text-danger">No Blocks Found</div>';
}
?>
<h3>
    <small>
        Total Slabs Area Available for
        <span class="text-success">
            <?php echo $avilable_check_details['inventory_name']; ?>
        </span> 
    </small>
</h3>
<?php 
if($avilable_check_details['available_salb_area'] > 0){
    foreach($avilable_check_details['available_salb_area'] as $available_detail){
        echo "<div class='text-primary'>$available_detail[thickness_unit] - $available_detail[area]</div>";
    }
}else{
    echo '<div class="text-danger">No Slabs Found</div>';
}
?>

<?php include 'includes/overall/footer.php'; 