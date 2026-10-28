var bool = true;

var ww = $(window).width();

$('.banner img').css({'width':ww});
$('.banner h1').css({'padding-top':(ww/1319*444/2-30)});


//navbar position
var runOnScroll = function() {
    var nav_h = $('.banner').height() + $('.small_not .notification').outerHeight();
    var scr_h = $(window).scrollTop();
    var win_h = $(window).height();

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
    		var ww = $(window).width();

            $('.banner img').css({'width':ww});
            $('.banner h1').css({'padding-top':(ww/1319*444/2-30)});
		
		var height = $('header .notification p').height();
		$('header .notification .glyphicon').css({'margin-top': ( (height)/2) + 'px'});

		var h2 = $('.small_not .notification p').height();
		$('.small_not .notification .glyphicon').css({'margin-top': ( (h2)/2) + 'px'});

        runOnScroll();
		
    	};
