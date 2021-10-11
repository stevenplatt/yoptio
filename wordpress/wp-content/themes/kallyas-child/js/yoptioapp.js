

jQuery(document).ready(function() {

	var table = jQuery('#call_logs').DataTable({
		scrollY: "450px",
		scrollCollapse: true
	});

	jQuery(document).on('click', '#menu-item-92', function() {
		jQuery('.none_logout a').trigger('click');
	});

   
    jQuery('.buy_yoptionumber,.change_yoptionumber').magnificPopup();

    
    jQuery(document).on('click', '.ajax-loadmorebtn', function() {
       var refObj = jQuery(this);
       refObj.html('<i class="fa fa-spinner fa-spin"></i> Please Wait..');
       var hasNumberlists = jQuery('.num-list ul li').length;
       var page    = refObj.attr('data-page');
       var limit   = refObj.attr('data-limit');
       var status  = refObj.attr('data-status');
       
       if(page>limit) { refObj.hide();return false;};
       // PASS DATA TO DB
		var data = {
			'method': 'loadmessage',
			data: {
				'page'   : page,
				'status' : status,
				'limit'  : limit
			}
		}

		AJAX_ACTION(data, '');
    });
    // SEARCH YOPTIO NUMBER 
    jQuery(document).on('click', '#search-yoptionumber', function() {
        var refObj = jQuery(this);
        refObj.html('<i class="fa fa-spinner fa-spin"></i>Wait..');
        var hasNumberlists = jQuery('.num-list ul li').length;

        var error = 0;
		button_txt = 'CHECK AVAILABILITY';
		var yon_umber = jQuery('.yo-number');
		var yonumber = yon_umber.val();
        
        if(hasNumberlists>0){
        	jQuery('.yo-avbailnumberlists').addClass('yoloader');
        }
		// CHECK THE FIELD IS EMPTY
		if (yonumber === '') {
			yon_umber.addClass('error');
			error++;
		} else {
			yon_umber.removeClass('error');
		}

		if (error > 0) {
			refObj.html(button_txt);
			jQuery('.yo-action .notification').html('Please fill all required fields').addClass('error');
			jQuery('.yo-avbailnumberlists').removeClass('yoloader');
			return false;
		}

		// PASS DATA TO DB
		var data = {
			'method': 'findnumber',
			data: {
				'yoptionumber': yonumber
			}
		}

		AJAX_ACTION(data, '');
    });
   
	//Updated by 707
    // PURCHASE YOPTIO NUMBER 
    jQuery(document).on('click', '#purchases-yoptionumber', function() {
		var has_yoptionumber = jQuery('#my_yoptionumber').val();
		if (has_yoptionumber === '') {
			jQuery('.yo-action .notification').html('Please selec atleast one numbers').addClass('error');
			return false;
		}
		else{
			jQuery('.mfp-close').trigger('click');
		}
	});
    
    /* jQuery(document).on('click', '#purchase-yoptionumber', function() {
        var refObj = jQuery(this);
        refObj.html('<i class="fa fa-spinner fa-spin"></i> Please Wait..');
        var error = 0;

        var has_yoptionumber = jQuery('#my_yoptionumber').val();
        
        // CHECK THE FIELD IS EMPTY
		if (has_yoptionumber === '') {
			jQuery('.yo-action .notification').html('Please selec atleast one numbers').addClass('error');
			return false;
		} else {
			
		}

		// PASS DATA TO DB
		var data = {
			'method': 'purchasenumber',
			data: {
				'yoptionumber': has_yoptionumber
			}
		}

		AJAX_ACTION(data, '');
    
    }); */ 

	//OFFICE Number
	jQuery('.add-followup').click(function() {
		var refObj = jQuery(this);
		refObj.html('<i class="fa fa-spinner fa-spin"></i>Wait..');
		var error = 0;
		button_txt = 'Add';
		var followup_field = jQuery('.followup-input');
		var followup_text = followup_field.val();

		// CHECK THE FIELD IS EMPTY
		if (followup_text === '') {
			followup_field.addClass('error');
			error++;
		} else {
			followup_field.removeClass('error');
		}


		if (error > 0) {
			refObj.html(button_txt);
			return false;
		}


		// PASS DATA TO DB
		jQuery('.followup-checklist').addClass('active');
		var data = {
			'method': 'addfollowup',
			data: {
				'followup_text': followup_text
			}
		}
		AJAX_ACTION(data, '');
	});

	// REMOVE MESSAGE 
	jQuery(document).on('click', '.remove-message', function() {
		var refObj = jQuery(this);
		var id = refObj.attr('data-id');
		// MOVE TO UNREAD STATE FROM READ STATE
		var data = {
			'method': 'removemsg',
			data: {
				'id': id
			}
		}
		AJAX_ACTION(data, '');
    });
    

	jQuery(document).on('click', '.followup-close', function() {
		var refObj = jQuery(this);
		var id = refObj.attr('data-id');
		// MOVE TO UNREAD STATE FROM READ STATE
		var data = {
			'method': 'removefollowup',
			data: {
				'id': id
			}
		}
		AJAX_ACTION(data, '');

	});


	//MOVE TO UNREAD STATE
	jQuery(document).on('click', '.read-btn', function() {
		var refObj = jQuery(this);
		refObj.html('<i class="fa fa-spinner fa-spin"></i>Wait..');
		var error = 0;
		var id = refObj.attr('data-id');
		// MOVE TO UNREAD STATE FROM READ STATE
		var data = {
			'method': 'moveunread',
			data: {
				'id': id
			}
		}
		AJAX_ACTION(data, '');

	});


	//MOVE TO READ STATE
	jQuery(document).on('click', '.unread-btn', function() {
		var refObj = jQuery(this);
		refObj.html('<i class="fa fa-spinner fa-spin"></i>Wait..');
		var error = 0;
		var id = refObj.attr('data-id');
		// MOVE TO UNREAD STATE FROM READ STATE
		var data = {
			'method': 'moveread',
			data: {
				'id': id
			}
		}
		AJAX_ACTION(data, '');

	});

	// CHNAGE PROFILE PIC 
	jQuery('.edit-profile-img').click(function() {
		jQuery('#upload_form #images').trigger('click');
	});

	jQuery('#images').change(function() {
		jQuery('.submit-btn').trigger('click');
	});

	jQuery(document).on('click', '.clicktostrick', function(){
	   jQuery(this).toggleClass('text-strike');
    });

    jQuery(document).on('click', '.follow-up-checks', function(){
	    var refObj = jQuery(this);
	    var id = refObj.attr('data-id');
	    console.log(id);
	    if (jQuery(this).is(':checked')) {
	         jQuery(".list-group-item-" + id).addClass('active'); 
	    }else{
             jQuery(".list-group-item-" + id).removeClass('active'); 
	    }
    });

  jQuery(document).on('click', '.yo-avbailnumberlists li .purchase_number', function(){
	    var refObj                  = jQuery(this);
	    var my_yopitonumber         = refObj.val();
	    var areacode                = refObj.attr('data-areacode');
	    var friendly                = refObj.attr('data-friendly');
        var my_yoptiofriendlynumber = areacode + friendly;
        
        if (jQuery(this).is(':checked')) {
	       jQuery('.buy_yoptionumber').hide();
	       jQuery('#my_yoptionumber').val(my_yopitonumber); 
	       jQuery('#my_yoptiofriendlynumber').val(my_yoptiofriendlynumber); 
	       jQuery('.my_yoptionumberbox').addClass('show').show();
	    }else{
           jQuery('.buy_yoptionumber').show();
           jQuery('#my_yopitonumber,#my_yoptiofriendlynumber').val(''); 
           jQuery('.my_yoptionumberbox').removeClass('show').hide();
	    }

    });
    
    

});

function AJAX_ACTION(data, container) {
	jQuery.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		dataType: 'json',
		success: function(response) {
			console.log(response);
			console.log("data"+data); 
			
			if (data.method == 'addfollowup') {
				jQuery('.followup-checklist').prepend(response.html);
				jQuery('.add-followup').html('Add');
				jQuery('.followup-checklist').removeClass('active');
				jQuery('.followup-input').val('');
				if (response.msg != '') toastmsg(response.msg);
			} else if (data.method == 'moveunread') {
				//console.log('moveunread');
				var htmlcontainer = jQuery("#unread .message-panel" + data.data.id);
				var htmlwrapper = jQuery("#unread .message-panel" + data.data.id).html();


				var newClass = 'col-lg-6 col-md-6 col-sm-6 col-xs-12 message-panel message-panel' + data.data.id;
				jQuery('#read .row').prepend("<div class='" + newClass + "'>" + htmlwrapper + "</div>");

				jQuery('#read .row').find(".m-t-" + data.data.id).html('Mark Unread');
				jQuery('#read .row').find(".m-t-" + data.data.id).addClass('unread-btn').removeClass('read-btn');

				if (response.msg != '') toastmsg(response.msg);

				jQuery("#unread .message-panel" + data.data.id).fadeOut(100);
				var get_unreadcount = jQuery('.unreadcount').text();
				jQuery('.unreadcount').text(parseInt(get_unreadcount) - parseInt(1));

			} else if (data.method == 'moveread') {
				var htmlcontainer = jQuery("#read .message-panel" + data.data.id);
				var htmlwrapper = htmlcontainer.html();

				// jQuery(".m-t-"+data.data.id).html('Mark Read');

				var newClass = 'col-lg-6 col-md-6 col-sm-6 col-xs-12 message-panel message-panel' + data.data.id;
				jQuery('#unread .row').prepend("<div class='" + newClass + "'>" + htmlwrapper + "</div>");

				jQuery('#unread .row').find(".m-t-" + data.data.id).html('Mark Read');
				jQuery('#unread .row').find(".m-t-" + data.data.id).addClass('read-btn').removeClass('unread-btn');

				if (response.msg != '') toastmsg(response.msg);

				jQuery("#read .message-panel" + data.data.id).fadeOut(100);

				var get_unreadcount = jQuery('.unreadcount').text();
				if (get_unreadcount > 0) setcount = parseInt(get_unreadcount) + parseInt(1);
				else setcount = 1;
				jQuery('.unreadcount').text(setcount);
			} else if (data.method == 'removemsg') {
				 jQuery(".message-panel" + data.data.id).fadeOut(100);
				 if (response.msg != '') toastmsg(response.msg);
			} else if (data.method == 'removefollowup') {
                 jQuery(".list-group-item-" + data.data.id).fadeOut(100);
				 if (response.msg != '') toastmsg(response.msg);   
			} else if (data.method == 'findnumber') {
                 // console.log("OBJECT"+response.is_avail); 
                  if (response.is_avail==false) { 
                  	jQuery('#yo-number').val('');
                  	jQuery('.find-yoptionumber').removeClass('yo-avail');
                    jQuery('#search-yoptionumber').html('CHECK AVAILABILITY');
                    jQuery('.yo-action .notification').html(response.numberhtml).addClass('error');
                    jQuery('.num-list ul').html('');
                  }else{
                 	jQuery('.find-yoptionumber').addClass('yo-avail'); 
                 	jQuery('#search-yoptionumber').html('CHECK AVAILABILITY');
                 	jQuery('#purchases-yoptionumber').show();
                 	jQuery('.yo-avbailnumberlists').html(response.numberhtml);
                 	jQuery('.yo-action .notification').html('').removeClass('error');
                 }
                 jQuery('.yo-avbailnumberlists').removeClass('yoloader');
			}
			else if (data.method == 'purchasenumber') {
                  //if (response.status == 1) { 
                 	jQuery('#purchases-yoptionumber').html('PURCHASE NUMBER');
                 	jQuery('.mfp-close').trigger('click');
                 //}
			}
		  else if(data.method == 'loadmessage'){
                console.log(data.data.status);
                console.log('#'+data.status);
                jQuery('#'+data.data.status+" .row").append(response.html);
                var page = parseInt(data.data.page)+parseInt(1);
                jQuery('#'+data.data.status+" .ajax-loadmorebtn").html('Load More');
                jQuery('#'+data.data.status+" .ajax-loadmorebtn").attr('data-page',page);
		  }
			
  		},
		complete: function() {}
	});
}

(function($) {
	$('#upload_form').on('submit', function(e) {
		e.preventDefault();
		var $this = $(this),
		nonce = $this.find('#image_upload_nonce').val(),
		images_wrap = $('#images_wrap'),
		status = $('#status'),
		formdata = false;

		if ($this.find('#images').val() == '') {
			alert('Please select an image to upload');
			return;
		}

		status.fadeIn().addClass('active');

		if (window.FormData) {
			formdata = new FormData();
		}
		var files_data = $('#images');

		$.each($(files_data), function(i, obj) {

			$.each(obj.files, function(j, file) {
				formdata.append('files[' + j + ']', file);
			})
		});
		// our AJAX identifier
		formdata.append('method', 'upload_images');
		formdata.append('nonce', nonce);
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: formdata,
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function(data) {
				console.log(data);
				if (data.status) {
					//images_wrap.append(data.message);
					$('.profile-img .authorimage').attr('src', data.image_url);
					status.fadeIn().removeClass('active').fadeOut(2000);
				} else {
					status.fadeIn().removeClass('active').fadeOut(2000);
					//status.fadeIn().text(data.message);
				}
			}
		});

	});
})(jQuery);


function toastmsg(msg = '') {
	jQuery.toast({
		heading: 'Yoptio',
		text: msg,
		position: 'top-right',
		loaderBg: '#ff6849',
		icon: 'success',
		hideAfter: 3000,
		stack: 6
	});

}




