<?php 
global $extensions;
$user_id = get_current_user_id();
$extensionslists=$extensions->get();

$getgreetings=$extensions->getgreetings();
$getgreetings=$getgreetings[0];


$office_number                 = get_user_meta($user_id,'office_number',true);
$office_areacode               = get_user_meta($user_id,'office_areacode',true);
$office_friendly               = get_user_meta($user_id,'office_friendly',true);
$conference_number             = get_user_meta($user_id,'conference_number',true);
$conference_areacode           = get_user_meta($user_id,'conference_areacode',true);
$conference_friendly           = get_user_meta($user_id,'conference_friendly',true);

$showoffice_number=$office_areacode." ".$office_friendly;
$showconference_number=$conference_areacode." ".$conference_friendly;
?>
<section class="content">
<!-- Reception Greeting  -->
<div class="col-md-12">
	<div class="panel panel-success" id="hidepanel6">
		 <div class="panel-body">
		   <div class="row">
			   <div class="col-md-12">
				<div class="my_numbers">
				<ul class="my_numbers_ul">
					<li><div class="number_txt">Reception Number</div> <div class="number"> <?php echo $showoffice_number; ?></div></li>
					<li><div class="number_txt">Conference Number</div> <div class="number"><?php echo $showconference_number; ?></div></li>
					
				</ul>
			</div>
			</div>
		  </div>
			<div class="inromationofresponse">
				<div class="notification"></div>
			</div>
			<form role="form" class="greetingtype-form" id="greetingtype-form" method="POST">
			 <div class="form-group greeting-types">
					<div class="radio">
						<label class="col-md-12">
							<input type="radio" <?php echo ($getgreetings->greeting_type==1) ? "checked='checked'" : ''; ?> name="optionsRadios" class="custom-radio" id="optionsRadios1" value="1">&nbsp; Standard Greetings</label>
					</div>
					<div class="radio">
						<label class="col-md-4"><input  <?php echo ($getgreetings->greeting_type==2) ? "checked='checked'" : ''; ?> type="radio" name="optionsRadios" class="custom-radio" id="optionsRadios2" value="2">&nbsp; Custom Greetings</label>
						<div class="custom col-md-8"><input type="text" name="custom_text" class="form-control" id="custom_text" value="<?php echo $getgreetings->custom_text;?>" placeholder="Provide Custom Greetings">  </div>  
					</div>
					<div class="radio">
						 <label class="col-md-12">
							<input type="radio" <?php echo ($getgreetings->greeting_type==3) ? "checked='checked'" : ''; ?> name="optionsRadios" class="custom-radio" id="optionsRadios3" value="3">&nbsp; Call Queue / Menu Tree</label>
						 <div class="call-greeting">
						  <div class="sales-users col-md-6">
							<fieldset>
								<legend>Sales Users</legend>
									<input type="hidden" name="sale_users_id" id="sale_users_id" value="<?php echo  $getgreetings->sales_users;?>" />
									<div class="selected-user-list"> 
									   <table class="tb-sale-user">
										 <?php 
											  if(!empty($getgreetings->sales_users)):
											   $sale_users=$extensions->get($getgreetings->sales_users);
											   if(!empty($sale_users)):
										 ?>   
										  <tbody>
										 <?php	 foreach($sale_users as $key=>$eachuser):  ?> 
											<tr><td data-userid="<?php echo $eachuser->id;?>"><?php  echo $eachuser->first_name." ".$eachuser->last_name;?></td></tr>
										 <?php endforeach;
											   endif; 
											   endif; 
										  ?>
									   </table>
									</div>
							</fieldset>
						  
							<div class="action">
							<div class="form-group">
							<div class="input-group select2-bootstrap-prepend">
							<span class="input-group-btn">
							<button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
							<span class="glyphicon glyphicon-search"></span>
							</button>
							</span>
							<select id="sales-users-op" class="form-control">
							<option value="">Search</option>
							<?php
							foreach($extensionslists as $user_details){
							  echo '<option value="'.$user_details->id.'">'.$user_details->first_name." ".$user_details->last_name.'</option>';
							}
							?>
							</select>
							</div>
							</div>
							 <input type="button" class="btn btn-default add-sales-user" value="Add"/>
							</div>
							</div>
						  <div class="support-users col-md-6">
							<fieldset>
							<legend>Support Users</legend>
							<input type="hidden" name="support_users_id" id="support_users_id" value="<?php echo  $getgreetings->support_users;?>"/>
							<div class="selected-user-list"> 
							<table class="tb-support-user">
							 <?php if(!empty($getgreetings->support_users)):
											   
											   $support_users=$extensions->get($getgreetings->support_users);
											   if(!empty($support_users)):
											  
										 ?>   
										  <tbody>
										 <?php	 
												foreach($support_users as $key=>$eachuser):  
										  ?>
											<tr><td data-userid="<?php echo $eachuser->id;?>"><?php  echo $eachuser->first_name." ".$eachuser->last_name;?></td></tr>
										 <?php endforeach;?>
										 
										 <?php endif; ?>
										 
										 <?php endif; ?>
							</table>
							</div>
							</fieldset>
						  
							<div class="action">
							<div class="form-group">
							<div class="input-group select2-bootstrap-prepend">
							<span class="input-group-btn">
							<button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
							<span class="glyphicon glyphicon-search"></span>
							</button>
							</span>
							<select id="support-users-op" class="form-control">
							<option value="">Search</option>
							<?php 
							foreach($extensionslists as $user_details){
							  echo '<option value="'.$user_details->id.'">'.$user_details->first_name." ".$user_details->last_name.'</option>';
							}
							?>
							</select>
							</div>
							</div>
							 <input type="button" class="btn btn-default add-support-user" value="Add"/>
							</div>
							</div>
						 </div>
					</div>         
				</div>
				<div class="col-md-12">
				<input type="hidden" name="greetingid" class="greetingid" id="greetingid" value="<?php echo $getgreetings->id;?>">
				<button type="button" class="btn btn-responsive btn-updatevirtual  btn-info  btn-default">Update</button></div>
			</form>
		</div>
	</div>
</div>



</div>
</div>
</section>
<!-- content -->