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
    $isipl_no = preg_replace('/\\s/', '', $_POST['isipl_no']);
    $result = mysqli_query($link, "SELECT im.* FROM `inventory_master` im INNER JOIN `block_inventory` bi ON bi.id = im.blockinventoryid WHERE bi.isipl_number = '$isipl_no' AND ((im.isstockstatusverified IS NULL) OR (im.isstockstatusverified = 0))");
    if(mysqli_num_rows($result) < 1){
        echo  '<span class="text-danger">No records found for ISIPL # '.$isipl_no.'.</span>';
    }
}

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="row">
        <div class="col-md-12">
            <label for="isipl_no">ISIPL #</label>
            <input type="text" name="isipl_no" id="isipl_no" class="form-control" placeholder="Enter ISIPL #" required="required" />
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
<?php 
if(isset($result) && mysqli_num_rows($result) > 0){
    echo 'Inventory Details for ISIPL# '.$isipl_no.' for which the inventory slabs are not verified <br/>';
    echo 'Overall Slabs not verified - '.mysqli_num_rows($result).'<br/><br/>';
    ?>
    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Slab No</th>
            </tr>
        </thead>
        <tbody>
    <?php            
    while($row = mysqli_fetch_assoc($result)){
        ?>
        <tr>
            <td><?php echo $row['SKU']; ?></td>
            <td><?php echo $row['serailNo']; ?></td>
        </tr>
        <?php
    }
    ?>
        </tbody>
    </table>
<?php
}

include 'includes/overall/footer.php';