/**
 * Created by timoschmidt on 25.06.15.
 */

$(function() {
	if(typeof 	$('.datetimepicker').datetimepicker == "function") {
		$('.datetimepicker').datetimepicker({
			format: 'DD.MM.YYYY HH:mm',
			locale: 'de'
		});
	}

	var el = $("*[autofocus='autofocus']");

	if(el.length > 0) {
		var elOffset = el.offset().top;
		var elHeight = el.height();
		var windowHeight = $(window).height();
		var offset;

		if (elHeight < windowHeight) {
			offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
		}
		else {
			offset = elOffset;
		}

		var speed = 700;
		$('html, body').animate({scrollTop:offset}, speed);
	}

	jQuery("a").bind("click",function() {
		jQuery("#content-inner").fadeOut();
	//	return false;
	})
});





