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

?>
<form action="locate_bins_isipl.php" method="POST">
    <div class="row">
        <div class="col-md-12">
            <label for="isipl">ISIPL#</label>
            <input type="text" name="isipl" id="isipl" class="form-control" autofocus="autofocus" placeholder="Enter ISIPL#" required="required" />
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
<h3>Click below to scan the slab.</h3><br />
<a href="zxing://scan/?ret=<?php echo $base_url; ?>locate_bins.php?code={CODE}" class="btn btn-danger form-control">
    Scan Slab
</a>
<br /><br />
<?php include 'includes/overall/footer.php';