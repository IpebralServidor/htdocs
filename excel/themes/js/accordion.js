$(document).ready(function(){
	$('p.accordion').click(function(){
		$(this).parent().find('div.accordion').slideToggle("slow");
	});
});