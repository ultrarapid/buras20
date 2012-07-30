$(document).ready(function(){
	$('div.div_radio').delegate('input', 'click', function(e){
		$p = $('p.p_result');
		$p.css('display', 'block');
		if ( this.id == 'input_game_homegame' ) {
			$('p.p_result').html('Bur&aring;s IK - ' + $('#input_game_opponent').val());
		}
		else if ( this.id == 'input_game_awaygame' ) {
			$('p.p_result').html($('#input_game_opponent').val() + ' - Bur&aring;s IK');
		}
	});
});