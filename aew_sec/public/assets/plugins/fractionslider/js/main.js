$(window).load(function(){
	var altura = $(this).outerHeight()-101;

	$('.slider').fractionSlider({
		'fullWidth'        : true,
		'controls'         : true, 
		'pager'            : true,
		'responsive'       : true,
		'dimensions'       : "1000,"+altura,
		'increase'         : false,
		'pauseOnHover'     : false,
		'slideEndAnimation': false
	});
});
