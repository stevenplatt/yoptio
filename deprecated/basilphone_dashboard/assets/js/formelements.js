$(document).ready(function() {
    $('input[type="checkbox"].custom-checkbox, input[type="radio"].custom-radio').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%'
    });
   
    //

    $('select.table_select').select2({
        placeholder: "Search",
        theme:"bootstrap"
    });
   
   
    $('#reset').click(function() {
        $('input').iCheck('uncheck');
    });
	

	var d1, d2, data, Options;

    d1 = [
        [1262304000000, 100], [1264982400000,560], [1267401600000, 1605], [1270080000000, 1129], 
        [1272672000000, 2163], [1275350400000, 1905], [1277942400000, 2002], [1280620800000, 2917], 
        [1283299200000, 2700], [1285891200000, 2700], [1288569600000, 2100], [1291161600000, 2700]
    ];
 
    d2 = [
        [1262304000000, 434], [1264982400000,232], [1267401600000, 875], [1270080000000, 553],
        [1272672000000, 975], [1275350400000, 1379], [1277942400000, 789], [1280620800000, 1026], 
        [1283299200000, 1240], [1285891200000, 1892], [1288569600000, 1147], [1291161600000, 2256]
    ];

    data = [{ 
        label: "Total visitors", 
        data: d1,
         color: "#EF6F6C"
    }, {
        label: "Total Sales",
        data: d2,
         color: "#01bc8c"
    }];
 
   Options = {
        xaxis: {
            min: (new Date(2009, 12, 1)).getTime(),
            max: (new Date(2010, 11, 2)).getTime(),
            mode: "time",
            tickSize: [1, "month"],
            monthNames: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"],
            tickLength: 0
        },
        yaxis: {

        },
        series: {
            lines: {
                show: true, 
                fill: false,
                lineWidth: 2
            },
            points: {
                show: true,
                radius: 4.5,
                fill: true,
                fillColor: "#ffffff",
                lineWidth: 2
            }
        },
       grid: { 
            hoverable: true, 
            clickable: false, 
            borderWidth: 0 
        },
        legend: {
             container: '#basicFlotLegend',
            show: true
         },
        
        tooltip: true,
        tooltipOpts: {
            content: '%s: %y'
        }
       
    };
 

    var holder = $('#line-chart');

    if (holder.length) {
        $.plot(holder, data, Options );
    }
	
	
	// ADD 	USER EXTENSION
	$('.addextensions').click(function(){
		$('.addextensions').html('Please wait');
		var error=false;
		var error_msg='';
		$('.req').each(function(){
			if($(this).val()=='') { 
			  error=true; $(this).addClass('error');
			  error_msg = 'Please fill all required fields';
			 }
			else if($(this).attr('name')=='email' && !isValidEmailAddress($(this).val())){ 
			   error=true;
			   $(this).addClass('error');
			   error_msg = 'Please enter valid email address';
			}
			else { $(this).removeClass('error');}
		});
		
		
		
		if(error){  jQuery('.notification').removeClass('noerror').addClass('error').html('<p class="error_info">'+error_msg+'</p>');$('.addextensions').html('Add');return false; }
	    else { var extensionsform=$( '.add-extensions-form').serializeArray();    var data = {action:"extensions",method:"add",form_data:extensionsform}; BA_AJAX_ACTION(data,'add');}
	});
	
	// ADD 	VIRTUAL RECEPTION
	$('.btn-updatevirtual').click(function(){
		$(this).html('Please wait');
		var error=true;
		var error_msg='';
		
		$('.custom-radio').each(function(){
			if($(this).is(':checked')) { 
			  error=false; 
			 }
			else { $(this).removeClass('error'); }
		});
		
		if(error){  error_msg = 'Please select atleast one greetings'; jQuery('.notification').removeClass('noerror').addClass('error').html('<p class="error_info">'+error_msg+'</p>');$('.btn-updatevirtual').html('Update');return false; }
	    else { var extensionsform=$( '#greetingtype-form').serializeArray();    var data = {action:"extensions",method:"addgreeting",form_data:extensionsform}; BA_AJAX_ACTION(data,'addgreeting');}
	});
	
	
	
   // REMOVE EXTENSION ITEM 
   $(document).on('click','.remove-item',function(){
		//$(this).html('Please wait');
		 var _id = $(this).data('id');
		  swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55  ",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
			var data = {action:"extensions",method:"remove",extensions_id:_id}; BA_AJAX_ACTION(data,'remove');
        });
   });	
    
});
function Ajax_UpdatedTable(){
	    
		$('#table1').DataTable( {
		"columnDefs": [{
		"defaultContent": "-",
		"targets": "_all"
		}],
		"responsive":true,
		"searching": true,
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
				console.log(column);
                var select = $('<select class="table_select"><option value=""></option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
		
    } );

    $('select.table_select').select2({
        placeholder: "Search",
        theme:"bootstrap"
    });
	
	
}
function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};
function  BA_AJAX_ACTION(data,method){
        
	jQuery.ajax({
		type     : "POST",
		url      : APPAJAX,	
		data     : data,
		dataType : 'json',
		success  : function (response){
			 if(method=='add')
			 {  
				     if(response.status==1){
					   
					   
					   
					    jQuery('.extensionslists .table-scrollable').addClass('isloader');

						$('html,body').animate({
						   scrollTop: $(".extensionslists").offset().top},
						'slow');
					   
					   var extensions_id       = jQuery('#extensions_id').val();
					   var first_name          = jQuery('#first_name').val();
					   var last_name           = jQuery('#last_name').val();
					   var email               = jQuery('#email').val();
					   var mobile_number       = jQuery('#mobile_number').val();
					   var areacode            = jQuery('#areacode').val();
					   
					   var extensions_row = '<tr class="extension'+extensions_id+'"><td>'+extensions_id+'</td><td>'+extensions_id+'</td><td>'+first_name+'</td><td>'+last_name+'</td><td>'+email+'</td><td>'+areacode+" "+mobile_number+'</td><td><a href="javascript:void(0)" class="remove-item" data-id="'+response.prev_extension_id+'">Remove</a></td></tr>';
					   if($(".extensionslists  tbody tr td").hasClass('dataTables_empty')){
						  $(".extensionslists  tbody tr td.dataTables_empty").parent().remove(); 
					   }
					   
					   jQuery(".extensionslists  tbody:first").prepend(extensions_row);
					   jQuery('.extensions span,.conference-id span').text(response.extension_id);
					   jQuery('#extensions_id,#conference_id').val(response.extension_id);
					   
					   setTimeout(function(){ 
					   jQuery('.extensionslists .table-scrollable').removeClass('isloader'); 
					   jQuery('.notification .error_info').hide(); 
					   jQuery('#first_name').val('');
					   jQuery('#last_name').val('');
					   jQuery('#email').val('');
					   jQuery('#mobile_number').val('');
					   },2000);
	                   jQuery('.notification').removeClass('error').addClass('noerror').html('<p class="error_info">'+response.msg+'</p>');
					  }else{ console.log(response);console.log('response.status'+response.status);
							var msg = '';
							$.each(response.msg,function(i, item) {
								 $('#'+i).addClass('error');  
								 msg += item+'<br />';
							});  
							
					  jQuery('.notification').removeClass('noerror').addClass('error').html('<p class="error_info">'+msg+'</p>');
							
				  }
				  jQuery('.addextensions').html('Add');
				 // Ajax_UpdatedTable();
			 }
			 else if(method=='remove'){
				  jQuery('.extension'+response.extension_id).fadeOut(500); 
				  jQuery('.extensions span,.conference-id span').text(response.next_extension_id);
				  jQuery('#extensions_id,#conference_id').val(response.next_extension_id);
				  swal("Deleted!", "Your imaginary file has been deleted.", "success");
				    
				//  console.log($(".extensionslists  tbody tr"));
				   if($(".extensionslists  tbody tr").length<2){
					     var empty_row='<tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">No data available in table</td></tr>';
				 		 $(".extensionslists  tbody:first").prepend(empty_row); 
				   }
				// Ajax_UpdatedTable();
			 }
			 else if(method=='addgreeting'){
				console.log(response);
 if(response.status==1){
				   jQuery('.notification').removeClass('error').addClass('noerror').html('<p class="error_info">'+response.msg+'</p>');
				   jQuery('.btn-updatevirtual').html('Update')
				 }
			 }
			// console.log(response);
			/*if(response.status==0){
			   if(response.field=='edd-email')
					 jQuery('#'+response.field).addClass('error');
			   set_notification('.tab-info',response.msg, 'error');
			}
			if(response.status==1){
				jQuery('.notify-text').html('').removeClass('error').addClass('success');
				jQuery('.basilphonetab-inner').find('li.active').attr('data-complete',1);
				showTab(response.tab);
				if(response.tab=='checkout') { jQuery('.tab-info').hide(); jQuery('#edd_purchase_submit,#edd_cc_fields,#edd_cc_fields_extra').show();} 
			}*/
		},
		complete : function(){ }
	});
}
