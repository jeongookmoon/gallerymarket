$(document).ready(function(){
	$('#effects a').click(function(e){
		e.preventDefault();
		$('#imgs').PhotoJShop({
			'effect' : $(this).attr("data-effect")
		});
		return true;
	});
	$('#filters a').click(function(e){
		e.preventDefault();
		$('#imgs').PhotoJShop({
			'color' : $(this).attr("data-effect")
		});
		return true;
	});
});