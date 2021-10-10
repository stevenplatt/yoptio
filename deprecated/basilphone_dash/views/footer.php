  </aside>
        <!-- right-side -->
    </div>
    <!-- ./wrapper -->
    <a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top" data-toggle="tooltip" data-placement="left">
        <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
    </a>
    <!-- global js -->
    <script src="<?php echo SITEURL;?>/assets/js/appbasilphone.js" type="text/javascript"></script>
	<script src="<?php echo SITEURL;?>/assets/js/jquery.flot.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript" src="<?php echo SITEURL;?>/assets/js/jquery.flot.js"></script>
     <script language="javascript" type="text/javascript" src="<?php echo SITEURL;?>/assets/js/jquery.flot.time.js"></script>
	<script src="<?php echo SITEURL;?>/assets/js/formelements.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo SITEURL;?>/assets/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?php echo SITEURL;?>/assets/js/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo SITEURL;?>/assets/js/dataTables.responsive.js"></script>

	
	<script src="<?php echo SITEURL;?>/assets/js/select2.js" type="text/javascript"></script>
    <script src="<?php echo SITEURL;?>/assets/js/icheck.js" type="text/javascript"></script>
    <script src="<?php echo SITEURL;?>/assets/js/sweetalert.min.js" type="text/javascript"></script>
    
    <!-- end of global js -->
	<script>
	$('#sales-users-op, #support-users-op').select2({
		allowClear: true,
		theme: "bootstrap",
		placeholder: "select"
	});
	$(document).ready(function() {
		$('.add-support-user, .add-sales-user').on('click',function(){
			var crefObj = $(this);
			if(crefObj.hasClass('add-sales-user')){ //sale users to list
				var sel_val = $('#sales-users-op').val();
				if(sel_val && sel_val !='' && sel_val != 'undefined'){
					var exist = 0;
					if($('.selected-user-list .tb-sale-user tr').length){
						$('.selected-user-list .tb-sale-user tr').each(function(){
							var userid = $(this).data('userid');
							console.log($(this).attr('data-userid')+'---22--'+userid);
							if(userid == sel_val){
								exist = 1;
							}
						});
					}
					var sale_users_ids = new Array();
					if(exist == 0){
						var selObj = $('#sales-users-op').select2('data')[0];
						var sale_users_id=$('#sale_users_id').val();
						$('.selected-user-list .tb-sale-user').prepend('<tr data-userid="'+sel_val+'"><td>'+selObj.text+'</td></tr>');
						
						if(sale_users_id!='') sale_users_id=sale_users_id+","+sel_val;
						else  sale_users_id=sel_val;
						
						$('#sale_users_id').val(sale_users_id);
					}
				}
			}
			else{ //support users to list
				var sel_val = 'support--'+$('#support-users-op').val();
				var sel_val = $('#support-users-op').val();
				if(sel_val && sel_val !='' && sel_val != 'undefined'){
					var exist = 0;
					if($('.selected-user-list .tb-support-user tr').length){
						$('.selected-user-list .tb-support-user tr').each(function(){
							var userid = $(this).data('userid');
							if(userid == sel_val){
								exist = 1;
							}
						});
					}
					if(exist == 0){
						var support_users_ids = $('#support_users_id').val();
						var selObj = $('#support-users-op').select2('data')[0];
						$('.selected-user-list .tb-support-user').prepend('<tr data-userid="'+sel_val+'"><td>'+selObj.text+'</td></tr>');
						
						if(support_users_ids!='') support_users_ids=support_users_ids+","+sel_val;
						else  support_users_ids=sel_val;
						
						$('#support_users_id').val(support_users_ids);
					}
				}
			}
		});
	 Ajax_UpdatedTable();
	})
	</script>
</body>

</html>
