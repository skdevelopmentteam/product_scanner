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
if(empty($_POST) === false){
    $isiplNumber   = $_POST['isipl'];
    $slabDetails    = getInventoryMasterDetailsByIsipl($isiplNumber);

    if ($slabDetails === FALSE) { ?>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="alert alert-danger">
                    Details not found for ISIPL# <?php echo $isiplNumber; ?>
                </div>
            </div>
        </div> 
    <?php 
    } else {
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
                 if(count($slabDetails) > 0){
                    foreach($slabDetails as $slabDetail){ ?>
                    <tr>
                        <th><?php echo $slabDetail['bin_name']; ?></th>
                        <th><?php echo $slabDetail['slabs_count']; ?></th>
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
    <?php }
}
    ?>  
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
    Scan Slab
</a>
<br /><br />
<?php include 'includes/overall/footer.php';