$(document).ready( function() {
	$('ul#nav-menu > li > a').click(function() {
		$(this).next().slideToggle();
	});
});
