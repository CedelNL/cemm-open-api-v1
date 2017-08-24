jQuery(document).ready(function($) {
	// Selectbox element with the available CEMM uids
	var el = jQuery("#"+cdp_vars.plugin_name+"-cemm");

	el.next(".spinner").addClass('is-active');

	// Get the saved uid
	cdp_vars.cemm = el.data("value");
	
	// AJAX request to fetch all available CEMM uids
	jQuery.post(ajaxurl, {'action': 'get_available_cemm'}, function(response) {
		if( ! response.promise ){
			UpdateCemms(response);
		}
		else {
			setTimeout(function(){
				jQuery.post(ajaxurl, {'action': 'get_available_cemm'}, function(response) {
					if( ! response.promise ){
						UpdateCemms(response);
					}					
				});
			}, 2000);

		}
		
	});
});

function UpdateCemms(){
	var cemms = response.data;
		
	for(var i=0; i<cemms.length; i++){
		var uid = cemms[i].uid;
		var opt = jQuery("<option></option>").attr("value",uid).text(uid);

		if(uid == cdp_vars.cemm){
			// Make the option selected if the uid is already saved
			opt.attr('selected','selected');
		}

		// Add the option to the selectbox
		el.append(opt);
	}

	// Make the selectbox editable
	el.removeAttr("disabled");
	// Hide the spinner
	el.next(".spinner").removeClass('is-active');
}