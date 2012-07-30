$(function() {
	$('#input_season_start, #input_season_end').datepicker();
	$('#input_game_date').datepicker();
	$('.input_date').datepicker();
	$('.input_time').timepicker({
		hour: 15,
		stepMinute: 5
	});
	$('#input_game_time').timepicker({
		hour: 15,
		stepMinute: 5
	});
	$('#input_game_gather').timepicker({
		hour: 15,
		stepMinute: 5
	});
});