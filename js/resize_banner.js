//banner
var cw = $('#bannerVideo').width();
$('#bannerContainer').css({'height':((cw*2/3)-cw/9.75)+'px'});
$('#bannerVideo').css({'height':(cw*2/3)+'px'});
$('#bannerVideo').css({'top':(-cw/18.5)+'px'});
$('header').css({'height':((cw*2/3)-cw/9.75)+'px'});
$('header .container').css({'height':((cw*2/3)-cw/9.75)+'px'});

//notification top
var height = $('header .notification p').height();
$('header .notification .glyphicon').css({'margin-top': ( (height)/2) + 'px'});

//notification bottom
var h2 = $('.small_not .notification p').height();
$('.small_not .notification .glyphicon').css({'margin-top': ( (h2)/2) + 'px'});

    	window.onresize = function(event) {
    		var cw = $('#bannerVideo').width();
		

    		$('#bannerContainer').css({'height':((cw*2/3)-cw/9.75)+'px'});
    		$('#bannerVideo').css({'height':(cw*2/3)+'px'});
    		$('#bannerVideo').css({'top':(-cw/18.5  )+'px'});
    		$('header').css({'height':((cw*2/3)-cw/9.75)+'px'});
    		$('header .container').css({'height':((cw*2/3)-cw/9.75)+'px'});
		
		var height = $('header .notification p').height();
		$('header .notification .glyphicon').css({'margin-top': ( (height)/2) + 'px'});

		var h2 = $('.small_not .notification p').height();
		$('.small_not .notification .glyphicon').css({'margin-top': ( (h2)/2) + 'px'});

		
    	};
