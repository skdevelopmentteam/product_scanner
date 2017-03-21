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
include 'includes/overall/header.php';
if (isset($_SESSION['scanned_slabs'])) {
    unset($_SESSION['scanned_slabs']);
}
?>

<script type="text/javascript">
    $( document ).ready(function() {
        $("#slabDetails").on('click',function(){
            var code = $('#slabDetailsVal').val();
             window.location = "<?php echo $base_url; ?>slab_details.php?code="+code;
        });

        console.log( "ready!" );
    });

   
</script>

<br />
<!--To add the single dummy row-->                
<div class="col-xs-12 col-sm-6 alert alert-success">
    <!-- <a href="zxing://scan/?ret=<?php echo $base_url; ?>slab_details.php?code={CODE}">
        Check Slab Details
    </a> -->
    Slab Details: <input type="text" id="slabDetailsVal"/>
    <a  href="#" id="slabDetails">
        Send Request
    </a>
</div>
<div class="col-xs-12 col-sm-6 alert alert-info">
<!--        <a href="zxing://scan/?ret=<?php echo $base_url; ?>locate_bins.php?code={CODE}">
        Locate Slab Bins
    </a>-->
    <a href="locate_bins_landing.php">Locate Slab Bins</a>
</div>
<div class="col-xs-12 col-sm-6 alert alert-default">
    <a href="pickticket_details.php">Locate Pick Ticket Items</a>
</div>

<div class="col-xs-12 col-sm-6 alert alert-danger">
    <a href="zxing://scan/?ret=<?php echo $base_url; ?>price_details.php?code={CODE}">
        Quick Price Check
    </a>
</div>
<div class="col-xs-12 col-sm-6 alert alert-warning">
    <a href="zxing://scan/?ret=<?php echo $base_url; ?>available_check.php?code={CODE}">
        Quick Available Check
    </a>
</div>

<div class="col-xs-12 col-sm-6 alert alert-custom-violet">
    <a href="zxing://scan/?ret=<?php echo $base_url; ?>update_bin.php?code={CODE}">
        Update Bin
    </a>
</div>
<!-- <div class="col-xs-12 col-sm-6 alert alert-success">
    <a href="check_customer_material.php">
        Check Customer Material
    </a>
</div>
<div class="col-xs-12 col-sm-6 alert alert-default">
    <a href="check_non_verified_slabs.php">
        Check Non Verified Slabs
    </a>
</div> -->
<div class="col-xs-12 col-sm-6 alert alert-primary">
    <a href="zxing://scan/?ret=<?php echo $base_url; ?>slab_details.php?code={CODE}container_slab_dispatched.php">
        Container Slabs Dispatched
    </a>
</div>
<?php
include 'includes/overall/footer.php';
