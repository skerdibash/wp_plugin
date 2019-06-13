jQuery(document).ready(function() {
	jQuery(".apibutton").on("click", function(){

		var data = {
			"action": "api_button_action"
		};
		
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			jQuery(".ajax_content").html(response);
		});

	});
});