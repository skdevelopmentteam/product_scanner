<?php
//include 'core/init.php';
include 'product.php';
protected_page();
include 'includes/overall/header.php';

$result = price_details($_GET['code']);
?>
<div class="panel ">
    <div class="panel-heading alert-info panel-primary">
        <?php echo("<b> "."  Price Details for ".$result['current'][0]['color']."</b>");?>
    </div>
<div class="row">
    <div class="col-xs-12"><br/>
        <div class="col-md-12">
            <div class="col-md-8 col-md-offset-2">
            <table class="table table-responsive">
                <tr class="active">
    <!--                <th class="alert-info">Inventory Name</th>-->
                    <th class="alert-info">Grade</th>
                    <th class="alert-info">Thickness</th>
                    
                    <th class="alert-info">Price</th>
                </tr>
                <?php 
                foreach ($result['current'] as $k => $v) {
                    ?>
                    <tr>
                        <td><?php echo $v['grade'];?></td>
                        <td><?php echo $v['thickness'];?></td>
                        
                        <td><?php echo number_format($v['price'],2,'.','');?></td>
                    </tr>
                <?php }
                ?>
            </table>    
            
            
                </div>
        </div>
    </div>
</div>
</div>


<div class="panel ">
    <div class="panel-heading alert-info panel-primary">
        <?php echo("<b> "." Related Price Details for ".$result['current'][0]['color']."</b>");?>
    </div>
<div class="row">
    <div class="col-xs-12"><br/>
        <div class="col-md-12">
            <div class="col-md-8 col-md-offset-2">
            <table class="table table-responsive">
                <tr class="active">
    <!--                <th class="alert-info">Inventory Name</th>-->
                    <th class="alert-info">Grade</th>
                    <th class="alert-info">Thickness</th>
                    
                    <th class="alert-info">Price</th>
                </tr>
                <?php 
                foreach ($result['others'] as $k => $v) {
                    ?>
                    <tr>
                        <td><?php echo $v['grade'];?></td>
                        <td><?php echo $v['thickness'];?></td>
                        
                        <td><?php echo number_format($v['price'],2,'.','');?></td>
                    </tr>
                <?php }
                ?>
            </table>   
            
            
                </div>
        </div>
    </div>
</div>
</div>
<?php include 'includes/overall/footer.php'; ?>
