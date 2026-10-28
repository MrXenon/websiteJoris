//banner
var cw = $('header').width();
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

var bool = true;
var lees_verder_hidden = false;

//navbar position
var runOnScroll = function() {
    var nav_h = $('header').height() + $('.small_not .notification').outerHeight();
    var scr_h = $(window).scrollTop();
    var win_h = $(window).height();

    if( !lees_verder_hidden && scr_h + win_h > $('header').outerHeight() ) {
        $('#lees_verder').css({'display':'none'});
        lees_verder_hidden = true;
    }
    else if( lees_verder_hidden && scr_h + win_h < $('header').outerHeight() ) {
        $('#lees_verder').css({'display':'block'});
        lees_verder_hidden = false;
    }

    if (bool && scr_h > nav_h ) {
        bool = false;
        $('#nav').attr('class','affix');
        $('#sec_break').css({'display':'block'});
    }
    else if (!bool && scr_h < nav_h) {
        bool = true;
        $('#nav').attr('class','affix-top');
        $('#sec_break').css({'display':'none'});
    }
}

var scroll2jQ = function(to_jQuery) {
    var nav_h = $('header').height() + $('.small_not .notification').outerHeight();

    $("html, body").animate({ scrollTop: $(to_jQuery).offset().top }, 1000);

    return false;
}

var toggleMenu = function() {
    var disp = $('#navbar_content').attr('style');

    if( disp == "display: block;") {
        $('#navbar_content').css({'display':'none'});
        $('button.navbar-toggle').css({'background-color':'#274e89'});
    }
    else {
        $('#navbar_content').css({'display':'block'});
        $('button.navbar-toggle').css({'background-color':'#dddddd'});
    }
    

    return false;
}

runOnScroll();


window.addEventListener("scroll", runOnScroll);

    	window.onresize = function(event) {
    		var cw = $(' header ').width();
		

    		$('#bannerContainer').css({'height':((cw*2/3)-cw/9.75)+'px'});
    		$('#bannerVideo').css({'height':(cw*2/3)+'px'});
    		$('#bannerVideo').css({'top':(-cw/18.5  )+'px'});
    		$('header').css({'height':((cw*2/3)-cw/9.75)+'px'});
    		$('header .container').css({'height':((cw*2/3)-cw/9.75)+'px'});
		
		var height = $('header .notification p').height();
		$('header .notification .glyphicon').css({'margin-top': ( (height)/2) + 'px'});

		var h2 = $('.small_not .notification p').height();
		$('.small_not .notification .glyphicon').css({'margin-top': ( (h2)/2) + 'px'});

        runOnScroll();
		
    	};
