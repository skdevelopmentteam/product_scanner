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
include_once 'product.php';
include 'includes/overall/header.php';

$uniqueNumber   = $_GET['code'];
$slabDetails    = getInventoryMasterDetails($uniqueNumber);
if ($slabDetails === FALSE) { ?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="alert alert-danger">
                The slab <?php echo $_GET['code']; ?> not found
            </div>
        </div>
    </div> 
<?php 
} else {
    $block_inventory_id = $slabDetails['blockInventoryId'];
    $query  =   "SELECT count(im.id) as slabs_count,b.name as bin_name"
                ." FROM `inventory_master` im"
                ." JOIN `bins` b ON b.id = im.binid"
                ." WHERE im.blockinventoryid = $block_inventory_id AND im.status = 'Available'"
                ." GROUP BY im.binid";
    $result  = mysqli_query($link, $query);
    $located_slab_bins  = array();
    while ($row =  mysqli_fetch_assoc($result)) {
        $located_slab_bins[] = $row;
    }
    ?>
    <h3>Slabs Found in following Bins</h3>
    <table class="table table-condensed table-bordered table-striped">
        <thead>
            <tr>
                <th>Bin</th>
                <th>Slabs Count</th>
            </tr>
        </thead>
        <tbody>
            <?php 
             if(count($located_slab_bins) > 0){
                foreach($located_slab_bins as $located_slab_bin){ ?>
                <tr>
                    <th><?php echo $located_slab_bin['bin_name']; ?></th>
                    <th><?php echo $located_slab_bin['slabs_count']; ?></th>
                </tr>
             <?php 
                } 
             }else{ ?>
             <tr>
                 <td colspan="2">
                     <h3 class="text-danger">No Slabs Found in any of Bins</h3>
                 </td>
             </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php } ?>  
    <br /><br />
<form action="locate_bins_isipl.php" method="POST">
    <div class="row">
        <div class="col-md-12">
            <label for="isipl">ISIPL#</label>
            <input type="text" name="isipl" id="isipl" class="form-control" placeholder="Enter ISIPL#" required="required" />
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
<h3>Click below to scan the slab.</h3> <br />
<a href="zxing://scan/?ret=<?php echo $base_url; ?>locate_bins.php?code={CODE}" class="btn btn-danger form-control">
    Scan Again
</a>
<br /><br />
<?php include 'includes/overall/footer.php';