jQuery(document).ready(function ($) {
	
	$('input[name="phone-number"]').mask('000-00-000-00-00');
	
	$('.fcb-main-button').show();
	$('#fcb-btn,.fcb-menus').click(function() {

		$('.fcb-menus-container').show();
		$('.fcb-menus-container').toggleClass('fcb-scale-out');
		$('.fcb-cross-icons,.fcb-close-menu').toggle();

	});
	$("#fcb-phn-num").keyup(function(){
		var len = $("#fcb-phn-num").val().length;
		if( len<7){
			$("input[type=submit]").attr("disabled", "disabled");
		}else{
			$("input[type=submit]").removeAttr("disabled");
		}
	})
	$("#fcb-phone,.fcb-close-icon").click(function() {
		$("input[type=submit]").attr("disabled", "disabled");
		$('.fcb-callback').show();
		$("#fcb-success-msg").hide();
		$(".fcb-callback-details").show();
		$('.fcb-callback').toggleClass('fcb-scale-out');
	});


	$("#fcb-callback-submit").click(function(e){
		var fcb_phn_num = $("#fcb-phn-num").val();
			e.preventDefault();
			jQuery.ajax({
				
				url : fcb_callback_ajax.ajax_url,
				beforeSend: function( xhr ) {
					$("#fcb-loader-wrapper").show();
				},
				type : 'post',
				
				data : {
					action : 'fcb_send_email',
					private_key: fcb_callback_ajax.private_key,
					phone_num : fcb_phn_num,
				},
			
				success: function(data) {
					$(".fcb-callback-details,#fcb-loader-wrapper").hide();
					$("#fcb-success-msg").show();
				}
			
			});
			
	});


});