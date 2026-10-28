var height = $('header .notification p').height();
$('header .notification .glyphicon').css({'padding-top': ( (height-30)/2) + 'px','padding-bottom': ((height-30)/2)+'px'});

window.onresize = function(event) {
	var height = $('header .notification p').height();
	$('header .notification .glyphicon').css({'padding-top': ( (height-30)/2) + 'px','padding-bottom': ((height-30)/2)+'px'});
};

