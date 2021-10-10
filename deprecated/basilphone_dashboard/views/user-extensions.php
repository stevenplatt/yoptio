<!-- User Extensions View Pages -->
<?php 
global $extensions;
$user_id 						= get_current_user_id();
$getExtensionid                 = $extensions->getExtensionid();
$Extensionid                    = ($getExtensionid>0) ? $getExtensionid : 100; $office_areacode  = get_user_meta($user_id,'office_areacode',true);
?>
<section class="content">
    <div class="col-md-12">
    	<div class="panel panel-success" id="hidepanel6">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 add-extensions pull-left">
                        <div class="portlet-body">
                        	<h3 class="panel-inner-title">Add Extensions</h3>
                            <div class="table-scrollable">
                            <form class="add-extensions-form" method="post">
                                <table class="table table-hover">
                                    <thead>
                                        <tr><th>Extensions</th><th>Conference ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Mobile Number</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="extensions"><span><?php echo $Extensionid;?></span><input type="hidden" name="extensions_id" class="form-control req" id="extensions_id" value="<?php echo $Extensionid;?>"/></td>
                                            <td class="conference-id"><span><?php echo $Extensionid;?></span><input type="hidden" name="conference_id" class="form-control req" id="conference_id" value="<?php echo $Extensionid;?>"/></td>
                                            <td><input type="text" name="first_name" class="form-control req" id="first_name"  value=""/></td>
                                            <td><input type="text" name="last_name" class="form-control req" id="last_name" value=""/></td>
                                            <td><input type="text" name="email" class="form-control req" id="email" value=""/></td>
                                            <td><div class="mobilenumber_block"><div class="country_code"><input type="hidden" name="areacode" class="form-control req" id="areacode" value="<?php echo $office_areacode;?>"/><?php echo $office_areacode;?></div><div class="mobile_input"><input type="text" name="mobile_number" class="form-control req" id="mobile_number" value="" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/></div></div></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="notification"></td><td colspan="2"><button type="button" class="btn-info addextensions">Add</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 list-extensions pull-left extensionslists">
                    <h3 class="panel-inner-title">Extensions</h3>
                    <div class="table-scrollable">
                                                <div class="loader"></div>
                                                <table class="table table-striped table-bordered" id="table1">
                                                     
                                                     
                                                     <thead>
                                                      <tr class="top_heading"><th>Extensions</th><th>Conference ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Mobile Number</th><th class="action">Actions</th></tr>
                                                     </thead>
                                                      <tbody>
                                                       <?php 
														  $extensionslist= $extensions->get();
														  if(!empty($extensionslist)){
															 foreach($extensionslist as $eachextension){
													   ?>
                                                        <tr class="extension<?php echo $eachextension->id;?>">
                                                           <td><?php echo $eachextension->id;?></td>
                                                           <td><?php echo $eachextension->id;?></td>
                                                            <td><?php echo $eachextension->first_name;?></td>
                                                            <td><?php echo $eachextension->last_name;?></td>
                                                            <td><?php echo $eachextension->email;?></td>
                                                            <td><?php echo $office_areacode." ".$eachextension->mobile_number;?></td>
                                                            <td><a href="javascript:void(0)" class="remove-item" data-id="<?php echo $eachextension->id;?>">Remove</a></td>
                                                        </tr>
                                                        <?php }  } else {  ?>
                                                        <?php }    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                    </div>                        
                </div>
            </div>
    	</div>
    </div>
</section>
