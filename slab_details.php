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
include_once 'core/functions/area_helper.php';
include 'includes/overall/header.php';

$uniqueNumber = $_GET['code'];

$slabDetails = slab_details($uniqueNumber);
$slabsBlockDetails = block_slabs_details($uniqueNumber);
$blockInventoryMasterDetails = getInventoryMasterDetailsByBinId($uniqueNumber);
?>

<script type="text/javascript">
    $( document ).ready(function() {
        $("#slabDetails").on('click',function(){
            var code = $('#slabDetailsVal').val();
            //document.location = "<?php echo $base_url; ?>slab_details.php?code="+code;
            var myURL = document.location;
            document.location = myURL + "?code="+code";
        });

        console.log( "ready!" );
    });

   
</script>

<!--SK-ISIPL-34343434-0-1-->

<?php if($slabDetails['row'] == null){ ?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="alert alert-danger">
                The slab <?php echo $_GET['code'];?> not found
             </div>
       </div>
    </div> 
<?php } else { 
    /* By default will convert area to sqfeet so no need to pass the 4th parameter here, because i need in sqfeet */
    $converted_area = convertArea($slabDetails['row']['netLength'], $slabDetails['row']['netHeight'], $slabDetails['row']['unit']);
    ?>

    <h2>Slab Details</h2>
    <!-- <a href="zxing://scan/?ret=<?php echo $base_url; ?>slab_details.php?code={CODE}" class="btn btn-primary">
        Scan Again
    </a> -->
    Scan Again: <input type="text" id="slabDetailsVal"/>
    <a  href="#" id="slabDetails">
        Send Request
    </a>
    <br /><br />
    <h4>Slab Status - <?php echo ($slabDetails['row']['isStockStatusVerified'] == 1) ? '<span class="text-success">Verified</span>' : '<span class="text-danger">Not Verified</span>'; ?></h4>
    <table class="record_properties table table-bordered table-hover">
        <tbody>
            <tr>
                <th>SKU</th>
                <td><?php echo $slabDetails['row']['SKU'] ?></td>
            </tr>
            <tr>
                <th>Image</th>
                <td>
                    <?php 
                    if($slabDetails['row']['pictureId'] != NULL){ ?>
                    <img width='150' height='150' src="<?php echo $image_url; ?>slabs/<?php echo $slabDetails['row']['pictureId'] ?>" />
                    <?php
                    }else{
                    ?>
                    <img width='150' height='150' src="<?php echo $image_url; ?>slabs/<?php echo $slabDetails['slab_image'] ?>" />
                    <?php
                    }
                    ?>
                </td>
            </tr>
             <tr>
                <th>BIN</th>
                <td><?php echo ($slabDetails['row']['bin'] == 'N/A' || $slabDetails['row']['bin'] == '') ? 'N/A' : $slabDetails['row']['bin']; ?></td>
            </tr>
            <tr>
                <th>Block</th>
                <td><?php echo $slabDetails['row']['isipl']; ?></td>
            </tr>
            <tr>
                <th>Area</th>
                <td><?php echo round($converted_area,2).' SqFeet'; ?></td>
            </tr>
            <tr>
                <th>Net Length</th>
                <td><?php echo round($slabDetails['row']['netLength'],2).' '.$slabDetails['row']['unit']; ?></td>
            </tr>
            <tr>
                <th>Net Height</th>
                <td><?php echo round($slabDetails['row']['netHeight'],2).' '.$slabDetails['row']['unit']; ?></td>
            </tr>
            <tr>
                <th>Gross Length</th>
                <td><?php echo round($slabDetails['row']['grossLength'],2).' '.$slabDetails['row']['unit']; ?></td>
            </tr>
            <tr>
                <th>Gross Height</th>
                <td><?php echo round($slabDetails['row']['grossHeight'],2).' '.$slabDetails['row']['unit']; ?></td>
            </tr>
            <tr>
                <th>Gross Weight</th>
                <td><?php echo round($slabDetails['row']['grossWeight'],2).' KG'; ?></td>
            </tr>
             <tr>
                <th>Status</th>
                <td><?php echo $slabDetails['row']['status'] ?></td>
            </tr>
             <tr>
                <th>Description</th>
                <td><?php echo $slabDetails['row']['description'] ?></td>
            </tr>
             <tr>
                <th>External description</th>
                <td><?php echo $slabDetails['row']['longDescription'] ?></td>
            </tr>
             <tr>
                <th>Color</th>
                <td><?php echo $slabDetails['row']['color'] ?></td>
            </tr>
             <tr>
                <th>Grade</th>
                <td><?php echo $slabDetails['row']['grade'] ?></td>
            </tr>
             <tr>
                <th>Finish</th>
                <td><?php echo $slabDetails['row']['finish'] ?></td>
            </tr>
             <tr>
                <th>Thickness</th>
                <td><?php echo $slabDetails['row']['thickness'] ?></td>
            </tr>
             <tr>
                <th>Location</th>
                <td><?php echo $slabDetails['row']['location'] ?></td>
            </tr>
             <tr>
                <th>Category</th>
                <td><?php echo $slabDetails['row']['category'] ?></td>
            </tr>
        </tbody>
    </table>

    <h2>Block Slab Details</h2>
    <table class="record_properties table table-bordered table-hover">
        <tbody>
             <tr>
                <th>Thickness</th>
                <th>Total Slabs</th>
                <th>Area(SqFeet)</th>
            </tr>
            <?php 
            if(count($slabsBlockDetails) > 0){
                foreach($slabsBlockDetails as $slabsBlockDetail){ ?>
                <tr>
                    <th>
                        <?php echo $slabsBlockDetail['thickness_name'].' '.$slabsBlockDetail['thickness_unit']; ?>
                    </th>
                    <td><?php echo $slabsBlockDetail['count']; ?></td>
                    <td><?php 
                    $convertedArea = getUnitConvertedArea($slabsBlockDetail['area'], $slabsBlockDetail['area_unit'],'sqfeet');
                    echo number_format($convertedArea,3,'.',''); ?></td>
                </tr>
                <?php 
                }
            } ?>
        </tbody>
    </table>
    
    <h2>Related Slab Details</h2>

    <table class="record_properties table table-bordered table-hover">
        <tbody>
             <tr>
                <th>Thickness</th>
                <th>Total Slabs</th>
                <th>Area<?php echo ' (SqFeet)'; ?></th>
            </tr>
            <?php foreach($slabDetails['relatedSlabs'] as $slabDetail){ ?>
            <tr>
                <th>
                    <a href="javascript:void(0)" data-inventorynameid ="<?php echo $slabDetail['inventory_name_id'] ?>" data-thicknessid="<?php echo $slabDetail['thickness_id'] ?>" onclick="findInventoryDetails(this);"><?php echo $slabDetail['thickness'].' '.$slabDetail['thickness_unit']; ?></a>
                </th>
                <td><?php echo $slabDetail['count']; ?></td>
                <td><?php echo number_format($slabDetail['overall_area'],3,'.',''); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <h2>Bin Slabs Details</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-condensed">
            <tr>
                <th>Thickness</th>
                <th>Total Slabs</th>
                <th>Slab Area (Sq.Ft.)</th>
            </tr>
            <?php foreach($blockInventoryMasterDetails as $blockInventoryMasterDetail){ ?>
            <tr>
                <td><?php echo $blockInventoryMasterDetail['thickness_name'].' '.$blockInventoryMasterDetail['thickness_unit']; ?></td>
                <td><?php echo $blockInventoryMasterDetail['total_slabs']; ?></td>
                <td>
                    <?php 
                    echo number_format (getUnitConvertedArea($blockInventoryMasterDetail['slab_area'], $blockInventoryMasterDetail['unit_name'], 'sqfeet'),3,'.',''); 
                    ?>
                </td>
            </tr>
             <?php } ?>
        </table>
    </div>
   
  <?php } ?>  
    
    // <a href="zxing://scan/?ret=<?php echo $base_url; ?>slab_details.php?code={CODE}" class="btn btn-primary">
    //     Scan Again
    // </a>
    <br /><br />
    <script type="text/javascript">
        function findInventoryDetails(e){
            var thicknessid     = $(e).attr('data-thicknessid');
            var inventorynameid = $(e).attr('data-inventorynameid');
            $.ajax({
                url     : 'inventoryDetailsByAjaxCall.php',
                data    : {thicknessid:thicknessid,inventorynameid:inventorynameid},
                dataType: 'JSON',
                type    : 'POST',
                success : function(retobj){
                    $('.inventory_details_modal_wrapper').html('');
                    $('.thickness-wrapper').html('');
                    if(retobj.finalRelatedSlabDetails === null || retobj.finalRelatedSlabDetails.length === 0){
                        $('.inventory_details_modal_wrapper').html('<h3 class="text-danger">No Records Found</h3>');
                    }else{
                        var inventoryDetailRecords = '';
                        $.each(retobj.finalRelatedSlabDetails,function(key,inventoryDetail){
                            inventoryDetailRecords +=   '<tr>'+
                                                            '<td>'+inventoryDetail.bin_name+'</td>'+
                                                            '<td>'+inventoryDetail.isipl_number+'</td>'+
                                                            '<td>'+inventoryDetail.slab_count+'</td>'+
                                                            '<td>'+inventoryDetail.slab_area+'</td>'+
                                                        '</tr>';
                        });
                        
                        $('.inventory_details_modal_wrapper').html(inventoryDetailRecords);
                    }
                    $('.thickness-wrapper').html('Thickness - '+$(e).text());
                    $('#inventory_details_modal').modal('show');
                }
            });
        }
    </script>
    <!-- Modal -->
    <div class="modal fade" id="inventory_details_modal" tabindex="-1" role="dialog" aria-labelledby="inventory_details_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Inventory Details</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="thickness-wrapper"></div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Bin#</th>
                                            <th>Block#</th>
                                            <th>Slabs#</th>
                                            <th>Total Qty(Sq.Ft.)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="inventory_details_modal_wrapper">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
<?php include 'includes/overall/footer.php'; ?>