var search_process = false;
var purchase_process = false;
jQuery(window).on("resize load",function(e){
showloader();
 hideloader();
});
function hideloader(){
	setTimeout(function(){  jQuery('#mask').hide(); 
	},2000);
}
function showloader(){
	 jQuery('#mask').show();
	 
}

var rootRef_office = jQuery('.office-number');
var o_searchbtn = rootRef_office.find('.edd-find-office_number');
var o_reservebtn = rootRef_office.find('.btn-oreserve');

var rootRef_conference = jQuery('.conference-number');
var c_searchbtn = rootRef_conference.find('.edd-find-conference_number');
var c_reservebtn = rootRef_conference.find('.btn-creserve');


jQuery(document).ready(function(){
	
	if ( typeof apppage !== 'undefined' && apppage == '1' ) {
		jQuery(document).keydown(function (e) {
			var tElem = jQuery(e.target).attr('name');
			if ((e.keyCode == 13) ) {
				if(jQuery('.tab-info #checkout').length && jQuery('.tab-info #checkout').hasClass('active')){
					return;
				}
				else if (jQuery.inArray(tElem, ['edd-find-conference_number', 'edd-find-office_number']) !== -1){
					if(jQuery('.tab-info .tabinfo.active').find('.findnumber .btn-info').length){
						jQuery('.tab-info .tabinfo.active').find('.findnumber .btn-info').trigger('click');
					}
					e.preventDefault();
				}
				else if(jQuery('.tabinfo#register').length && jQuery('.tabinfo#register').hasClass('active')){
					jQuery('.tabinfo#register').find('#edd_register_account_fields .btn-next').trigger('click');
					e.preventDefault();
				}
				else{
					e.preventDefault();
				}
			}
			return;
			
		});
	}
	else{
		console.log('apppage not exist');
	}

	
	hideloader();
	
	//COMPANY 
	jQuery('.btn-next').click(function(){
		var refObj = jQuery(this);
		refObj.html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
		var error=0;
		var button_txt = refObj.data('text');
		jQuery('.register .required').each(function(){
			if(jQuery(this).val()==''){
			    jQuery(this).addClass('error');
				error++;
			}else{
				jQuery(this).removeClass('error');
			}
		
		});
		if(error>0) { 
			 jQuery('p.notify-text').html('Please fill all the required fields').removeClass('success').addClass('error'); 
			 refObj.text('Next');
			 return false;
		}
		else{ 
			var data = {method:"register",form_data:jQuery('#edd_purchase_form').serializeArray()};
			setTimeout(function(){
				AJAX_ACTION(data,'');
			},1000);
		}
	});
	
	//jQuery('.edd-submit').click(function(){ });
	
	
	//OFFICE Number
	jQuery('.btn-oreserve').click(function(){
		var refObj = jQuery(this);
		refObj.html('<i class="fa fa-spinner fa-spin"></i> Please Wait');	
		var error=0;button_txt=refObj.data('text');
		jQuery('.office-number .required').each(function(){
			if(jQuery(this).val()==''){
			    jQuery(this).addClass('error');
				error++;
			}else{
				jQuery(this).removeClass('error');
			}
		
		});
		
		if(error>0) { 
		  refObj.html(button_txt);
		  return false;
		}
	});
	
	//Conference Number
	jQuery('.btn-creserve').click(function(){
		var refObj = jQuery(this);
		button_txt = refObj.data('text');
		refObj.html('<i class="fa fa-spinner fa-spin"></i> Please Wait');	
		var error=0;
		jQuery('.conference-number .required').each(function(){
			if(jQuery(this).val()==''){
			    jQuery(this).addClass('error');
				error++;
			}else{
				jQuery(this).removeClass('error');
			}
		
		});
		
		if(error>0) { 
		    refObj.html(button_txt);
			return false;
		}
	});

	if(jQuery('#edd_purchase_form').length){
		if(jQuery('#edd_purchase_form').find('.basilphone_tab .tab').last().hasClass('active')){
			jQuery('#edd_purchase_submit').show();
		}
	}
	
	 jQuery('.findnumber .btn-info').click(function(e){
			var findbtn= jQuery(this);
			findbtn.html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
			var error=0;
			var button_txt = findbtn.data('text');
			
			if(jQuery(this).hasClass('btn-find-o-reserve')){
				var target="#edd-find-office_number";
				var container=".office-num-list";
				var method="office";
		    }else{
				 var target="#edd-find-conference_number";
				 var container=".conference-num-list";
				 var method="conference";
			}
			
			 jQuery('.tab-info p.notify-text').html('').removeClass('error').addClass('success'); 
			  jQuery(target).removeClass('error');
			if(jQuery(target).val()==''){
			  jQuery(target).addClass('error');
			  jQuery('.tab-info p.notify-text').html('Please fill all the required fields').removeClass('success').addClass('error'); 
			  findbtn.text(button_txt);
			  return false;	
			}
			
			var country = jQuery('#edd-country').val();
			var areacode = '';
			var containnumber = jQuery(target).val();
			
			if(!country || country == 'undefined'){
				country = 'US'; //Need to confirm
			}
			if(!containnumber || containnumber == 'undefined'){
				containnumber = '';
			}
			if(containnumber == '' && (!areacode || areacode == 'undefined')){
				areacode = '';
			}
			var datas = {  
				action	      : 'api', 
				_action	      : 'search_number',
				c		      : country, 
				ac		      : areacode, 
				n		      : containnumber,
				action_method :  method
			};
			searchNumber(datas,container);
		});
		jQuery('.reserve_number .btn-info').click(function(e){
			if(jQuery(this).hasClass('btn-oreserve')){
				var phonenumber    = jQuery('#edd-office_number').val();
				var method="office";
			}else{
				var phonenumber = jQuery('#edd-conference_number').val();
				var method="conference";
			}
			
			if(!phonenumber || phonenumber == 'undefined'){
				return false;
			}
			var user_id = jQuery('.edd-input').data('uid');
			if(!user_id || user_id == 'undefined'){
				user_id = '1'; //To ensure action need to update later
			}
			var datas = {  
				action		      : 'api', 
				_action		      : 'purchase_number',
				purchase_number	  : phonenumber,
				user_id	          : user_id,
				method            : method
			};
			purchaseNumber(datas,method);
		});
	jQuery(document).on('change', '[name^=purchase_number]', function(e){
		var ref = jQuery(this);
	   
	   if(ref.data('action')=="office"){
	     jQuery('#edd-office_number').val(ref.val());
		 jQuery('.office_number .num').html(ref.val());
	   }
       else{
		  jQuery('#edd-conference_number').val(ref.val()); 
		   jQuery('.conference_number .num').html(ref.val());
	   }
	});
	if(jQuery('#edds-confirm-update-default').length){
		jQuery('#edds-confirm-update-default').prop('checked',true);
	}
	jQuery(".number_only").keydown(function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			 // Allow: Ctrl/cmd+A
			(e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
			 // Allow: Ctrl/cmd+C
			(e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
			 // Allow: Ctrl/cmd+X
			(e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
			 // Allow: home, end, left, right
			(e.keyCode >= 35 && e.keyCode <= 39)) {
				 // let it happen, don't do anything
				 return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
	if(jQuery('#mask').length){
		jQuery('#mask').hide().ajaxStart( function() {
			jQuery(this).show();  // show Loading Div
		} ).ajaxStop ( function(){
			jQuery(this).hide(); // hide loading div
		});
	}
	
	jQuery('.basilphonetab-inner li').click(function(){
		var cur_step = jQuery('.basilphonetab-inner').find('li.active').data('step');
		var cur_complete = jQuery('.basilphonetab-inner').find('li.active').data('complete');
		var iscomplete = jQuery(this).data('complete');
		var step = jQuery(this).data('step');
		var getTab = jQuery(this).data('href');
		if(iscomplete == '1' || cur_complete == '1'){ //No need any validation
			showTab(getTab);
		}
		else{ //Must proceed with current step
			//showTab(getTab);
			console.log('Must proceed with current step');
		}
	});
	
	var cur_step = jQuery('#cur_step').val();
	if(cur_step && cur_step !='undefined'){
		if(cur_step==2){
			showDefaultofficenumber();
		}
		else if(cur_step==3){
		  showDefaultconferencenumber();
		}
	}
	
});

//search-action
function searchNumber(datas,container){
	if(search_process) return false;
	if(!datas){
		datas = {  action: 'api', c:   'CA', ac:   '', n: '','_action':'search_number' };
	}
	search_process = true;
	datas.action = 'api';
	datas._action = 'search_number';
	showloader();
	set_notification('.tab-info');
	jQuery.post(
		ajaxurl, datas, 
		function(response){
		   search_process = false;
		  if(response !='' && response && jQuery.parseJSON(response)){
			response = jQuery.parseJSON(response);
			if(response.status){
				//Update search result to view
				if(jQuery(container).length){
					jQuery(container).html(response.availNumberHtml);
					jQuery(container).fadeIn(600);
				}
			}
			else{
				//Update error msg to user view sections
				jQuery('.tab-info p.notify-text').html(response.error_msg).removeClass('success').addClass('error');
			}
		  }
		  hideloader();
		}
	);
	jQuery('.findnumber .btn-info').text('Search');
}

function purchaseNumber(datas,method){
	if(purchase_process) return false;
	if(!datas){
		datas = {  'action': 'api','_action': 'purchase_number','user_id':   '2', 'purchase_number':   '+15103067280' };
	}
	purchase_process = true;
	datas.action = 'api';
	datas._action = 'purchase_number';
	console.log(datas);
	showloader();
	set_notification('.tab-info');
	jQuery.post(
		ajaxurl, datas, 
		function(response){
		  purchase_process = false;
		  if(response !='' && response && jQuery.parseJSON(response)){
			response = jQuery.parseJSON(response);
			if(response.status){
				//Update search result to view
				jQuery('.tab-info p.notify-text').html('Number reserved for your account').addClass('success').removeClass('error');
				jQuery('.basilphonetab-inner').find('li.active').data('complete',1);
			     if(method=="office"){
					showTab('conference-number'); 
				 }else{
					 showTab('checkout');
					jQuery('.tab-info').hide(); 
					jQuery('#edd_purchase_submit,#edd_cc_fields,#edd_cc_fields_extra').show();
				 }
			}
			else{
				//Need to update error msg to user view sections
				jQuery('.basilphonetab p.notify-text').html(response.error_msg).removeClass('success').addClass('error');
			}
		  }
		  hideloader(); 
		}
	);
	

}
function  AJAX_ACTION(data,container){
	set_notification('.tab-info');
	jQuery('.btn-next').html('Next');
    jQuery.ajax({
		type : "POST",
		url: ajax_url,	
		data:data,
		dataType:'json',
		success: function (response){
			if(response.status==0){
			   if(response.field=='edd-email')
					 jQuery('#'+response.field).addClass('error');
			   set_notification('.tab-info',response.msg, 'error');
			}
			if(response.status==1){
				jQuery('.notify-text').html('').removeClass('error').addClass('success');
				jQuery('.basilphonetab-inner').find('li.active').attr('data-complete',1);
				showTab(response.tab);
				if(response.tab=='checkout') { jQuery('.tab-info').hide(); jQuery('#edd_purchase_submit,#edd_cc_fields,#edd_cc_fields_extra').show();} 
			}
		},
		complete:function(){ }
	});
}
function showDefaultofficenumber(){
	var target="#edd-find-office_number";
	var container=".office-num-list";
	var method="office";
	var containnumber = jQuery('.edd-find-office_number').val();
	var country = jQuery('#edd-country').val();
	var areacode = '';
	if(!containnumber || containnumber == 'undefined'){
		containnumber = '';
	}
	if(containnumber == '' && (!areacode || areacode == 'undefined')){
		areacode = '';
	}
	var datas = {  
		action	      : 'api', 
		_action	      : 'search_number',
		c		      : country, 
		ac		      : areacode, 
		n		      : containnumber,
		action_method :  method
	};
	searchNumber(datas,container);
}
function showDefaultconferencenumber(){
	
	var target="#edd-find-conference_number";
	var container=".conference-num-list";
	var method="conference";
	var containnumber = jQuery('.edd-find-conference_number_number').val();
	var country = jQuery('#edd-country').val();
	var areacode = '';
			
	if(!containnumber || containnumber == 'undefined'){
		containnumber = '';
	}
	if(containnumber == '' && (!areacode || areacode == 'undefined')){
		areacode = '';
	}
	var datas = {  
	action	      : 'api', 
	_action	      : 'search_number',
	c		      : country, 
	ac		      : areacode, 
	n		      : containnumber,
	action_method :  method
	};
	searchNumber(datas,container);
}
function showTab(tab){
	var step=1; 
	jQuery('.basilphonetab-inner li').each(function(){
		if(jQuery(this).data('href')==tab) { 
			jQuery(this).addClass('active');
			step=jQuery(this).data('step');
	}else{  
			jQuery(this).removeClass('active');
	}
	});

	if(step==2){
		showDefaultofficenumber();
	}
	else if(step==3){
	  showDefaultconferencenumber();
	}
	
	if(step==4){
	  jQuery('#edd_cc_fields,#edd_purchase_submit,#checkout,#edd_cc_fields_extra').show();
	}else{
		jQuery('#edd_cc_fields,#edd_purchase_submit,#checkout,#edd_cc_fields_extra').hide();
	}
	
	jQuery('.tabinfo').removeClass('active'); 
	if(tab !='checkout'){
		jQuery("#"+tab).addClass('active'); 
		jQuery('.tab-info').show();
	 }
	
 var progress_bar = step * 25; 
 console.log(progress_bar);
 jQuery('.progressbar_inner').css("width",progress_bar+"%");
	 
}
function setTab(tab){
	 showloader();
	 showTab(tab);
	 hideloader();
}

function SetButtontext(refObjbutton,buttontext){
   setTimeout(function(){
					refObjbutton.html(button_txt);	
    },1000); 
}
function set_notification(root_name,msg,set_class){
	if(!root_name || root_name == 'undefined') var root_name = '.tab-info';
	if(!msg || msg == 'undefined'){ var msg = ''; }
	if(!set_class || set_class == 'undefined'){ var set_class = ''; }
	if(set_class == 'error'){
		jQuery(root_name+' p.notify-text').html(msg).removeClass('success').addClass('error');
	}
	else if(set_class == 'success'){
		jQuery(root_name+' p.notify-text').html(msg).removeClass('error').addClass('success');
	}
	else	
		jQuery(root_name+' p.notify-text').html(msg).removeClass('success').removeClass('error');
}