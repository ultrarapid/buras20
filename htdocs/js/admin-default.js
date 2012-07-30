$(document).ready(function(){
	$("body").delegate("a.delete_link", "click", function(e){
		if ( confirm("\u00c4r du s\u00e4ker?") ) {
			return true;
		} else {
			return false;
		}
	});
});