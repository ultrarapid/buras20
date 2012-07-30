$(function() {

// selects / deselects all enabled checkboxes

	$('p#par-link').delegate("a#a-select-all", "click", function(e){
		e.preventDefault();
		(function(){
			var cb_control_value = $('#checkbox_controller').val();
			$('input.checkbox-select-all').each(function() {
				if ( !this.disabled ) {
					if ( cb_control_value == '0' ) {
						$(this).attr('checked', true);
						$('#checkbox_controller').val('1');
						$('a#a-select-all').html('Avmarkera alla');
					} else if ( cb_control_value == '1' ) {
						$(this).attr('checked', false);
						$('#checkbox_controller').val('0');
						$('a#a-select-all').html('Markera alla');
					}
				}
			});
		})();
	});
});