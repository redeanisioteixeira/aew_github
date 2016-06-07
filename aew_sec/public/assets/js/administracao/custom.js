var statusApagar = $('input[name=apagar-tag]');

jQuery(document).ready(function($){ // No Conflict
	if(statusApagar.length == 0)
	{
		$('input[name=marcar-todos]').remove();
	}
	
	$('input[name=apagar-tag]').click(function(){
		var status = $('input:checked[name=apagar-tag]').val();

		if(typeof status !== 'undefined')
		{
			$('a#apagar-multiplo').removeClass('desativado');
		}
		else
		{
			$('a#apagar-multiplo').addClass('desativado');
			$('tr#apagar-multiplo').addClass('desativado');
		}				
	});

	$('input[name=marcar-todos]').click(function(){
		var status = $(this).is(":checked");

		if(status == true)
		{
			$('a#apagar-multiplo').removeClass('desativado');
		}
		else
		{
			$('a#apagar-multiplo').addClass('desativado');
		}

		$('input[name=apagar-tag]').each(function(){
			$(this).prop("checked", status);
		});
	});


	$('a#apagar-multiplo').click(function(){
		$('tr#apagar-multiplo').removeClass('desativado');
	});

	$('form.form-apagar input[name=sim]').click(function(){
		var tags = $('input[name=tagsApagar]');

		if(typeof tags !== 'undefined')
		{
			var tagsApagar = new Array();

			$('input:checked[name=apagar-tag]').each(function(){
				tagsApagar.push($(this).val());
			});

			tags.val(tagsApagar.join());
		}
	});

	$('form.form-apagar input[name=nao]').click(function(){
		var tags = $('input[name=tagsApagar]');

		if(typeof tags !== 'undefined')
		{
			tags.val('');
			$('input:checked[name=apagar-tag]').each(function(){
				$(this).prop('checked', false);
			});

			$('input[name=marcar-todos]').prop('checked', false);
			$('a#apagar-multiplo').addClass('desativado');
			$('tr#apagar-multiplo').addClass('desativado');
			return false;
		}
	});
});
