// JavaScript Document
	
	function spcomment_display_form(data_id,data_sarg,data_hideargs) {
		
		var target = "#spcomments-form-block-" + data_id;
		
		jQuery(target).toggle();
		
		//
		// seleziona l'opzione da selezionare in caso fosse richiesto
		//
		
		if(data_sarg!="none") {
			
			jQuery(target + " input[type='radio'][value='" + data_sarg + "']").prop('checked', true);
			
			}
			
		if(data_hideargs=="yes") {
			
			jQuery(target + " fieldset#spcomments_arguments").hide();
			
			} else {
				
			jQuery(target + " fieldset#spcomments_arguments").show();
				
		}
		
	};

       jQuery(document).ready(function() {
		   
	
		jQuery(".spcomments-button").on("click", function(event) {
			
			var data_id = jQuery(this).data("id");
			var data_sarg = jQuery(this).data("sarg");
			var data_hideargs = jQuery(this).data("hideargs");
			
			spcomment_display_form(data_id,data_sarg,data_hideargs);
		
		});
		
		jQuery(".spcomment-close").on("click", function() {
			
			var data_id = jQuery(this).data("id");
			spcomment_display_form(data_id,"none","");
		
		});
		
		jQuery(".spcomments-form-block").on("click", function(event) {
			
			var data_id = jQuery(this).data("id");
			if (event.target.id == "spcomments-form-block-" + data_id) spcomment_display_form(data_id,"none","");
			
		});
				
	
	});