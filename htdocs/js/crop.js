$(document).ready(function(){
	$('#cropbox_thumb_ar1').Jcrop({
		onChange: showPreview,
		onSelect: showPreview,
		aspectRatio: 1
	});
	
	$('#cropbox').Jcrop({
		onChange: updateCoords,
		onSelect: updateCoords
	});
	
	if ( $('img.thumb').length > 0 ) {
		var side = parseInt($('#thumb_side').val(), 10);
		var src  = $('img.thumb').attr('src');
		$('img.thumb').after(
			$('<div/>')
				.css('width', side + 'px')
				.css('height', side + 'px')
				.css('overflow', 'hidden')
				.css('margin-left', '5px')
				.append(
					$('<img/>')
						.attr('src', src)
						.attr('id', 'preview')
				)
		);
	}

	if ($('form#cropform').length > 0) {
		$('form#cropform :submit').before(
			$('<input/>')
				.attr('type', 'hidden')
				.attr('id', 'x')
				.attr('name', 'x')
		);
		$('form#cropform :submit').before(
			$('<input/>')
				.attr('type', 'hidden')
				.attr('id', 'y')
				.attr('name', 'y')
		);
		$('form#cropform :submit').before(
			$('<input/>')
				.attr('type', 'hidden')
				.attr('id', 'w')
				.attr('name', 'w')
		);
		$('form#cropform:submit').before(
			$('<input/>')
				.attr('type', 'hidden')
				.attr('id', 'h')
				.attr('name', 'h')
		);
	}
});

function showPreview(c) {
	var side = parseInt($('#thumb_side').val(), 10);
	var rx = side / c.w;
	var ry = side / c.h;
	var fh = parseInt($('#src_h').val(), 10);
	var fw = parseInt($('#src_w').val(), 10);
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);

	$('#preview').css({
		width: Math.round(rx * fw) + 'px',
		height: Math.round(ry * fh) + 'px',
		marginLeft: '-' + Math.round(rx * c.x) + 'px',
		marginTop: '-' + Math.round(ry * c.y) + 'px'
	});
}

function updateCoords(c) {
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
}

function checkCoords() {
	if ( parseInt($('#w').val(), 10) ) return true;
	alert('Markera i bilden');
	return false;
}