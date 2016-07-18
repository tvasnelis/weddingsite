jQuery.fn.extend({
    live: function (event, callback) {
	       if (this.selector) {
	            jQuery(document).on(event, this.selector, callback);
	        }
	    }
	});

	$('#Guest').live('change', function(){
		if ( $(this).is(':checked') ) {
			$('#guest_info').show();
		} else {
			$('#guest_info').hide();
		}
	});

