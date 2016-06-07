<?php ?>

<div class="Adicionar">
	<?php echo $this->adicionar; ?>
</div>

<script type="text/javascript" src="<?php echo $this->baseUrl();?>/js/autocomplete/jquery.aceditable.js"></script>
<script type="text/javascript">
	function alteraData(){
		var data1 = document.getElementById('dataInicio').value;
		var data2 = document.getElementById('dataFim').value;

		if (!( parseInt( data2.split( "/" )[2].toString() + data2.split( "/" )[1].toString() + data2.split( "/" )[0].toString() ) > parseInt( data1.split( "/" )[2].toString() + data1.split( "/" )[1].toString() + data1.split( "/" )[0].toString() ) ))
			{
				document.getElementById('dataFim').value = document.getElementById('dataInicio').value;
			}
	}

	function retornaNome(val){
		i=val.length-1;

		for (;i>=0;i--){
			if(val.charAt(i-2) == '<' && val.charAt(i-1) == 'p' && val.charAt(i) == '>')
				return val.substring(0, i-2);
		}
		return val;
	}

	function obtemId(val) {
		i=val.length-1;
		for (;i>=0;i--){
			if(val.charAt(i-4) == '<' && val.charAt(i-3) == '/' && val.charAt(i-2) == 'd' && val.charAt(i-1) == 'i' && val.charAt(i) == 'v'){
				for (j=i-4;j>=0;j--){
					//alert(val.charAt(j));
					if(val.charAt(j) == '>'){
						return val.substring(j+1, i-4);
					}
				}
				return '';
			}
		}
		return val;
	}

	//tempo para que o IE (infelizmente) processe os dados
	jQuery(document).ready(setTimeout(function() {

	cities = ''; //fazendo com que o ajax trabalhe de maneira assincrona para carregar os dados inicialmente
	$.ajax({
		type: "GET",
		url: '<?php echo $this->baseUrl();?>/aew/usuario/json',
		async: false,
		dataType: "json",
		success: function(data) {
				cities = data; }
		});

		$("#ce1").autocomplete(cities, {
		    matchContains: true,
		    scroll: false,
		    hotkeymode:true,
		    noresultsmsg: 'Nenhum resultado encontrado: <a href="#">{q}</a>'
		  });

		$('[contenteditable]').autocomplete(cities, {
			matchContains: true,
			scroll: false,
			hotkeymode:true,
			formatResult: function(row) {
        			corpo = "<?php echo $this->url(array('module' => 'espaco-aberto', 'controller' => 'perfil','action' => 'exibir', 'usuario' => '' ), null, true);?>";
					auxRow = row[0];
      	      idUsuario = obtemId(auxRow);
      	      var UA = navigator.userAgent;
					textoSpan = '';
					fechaSpan = '';
					//firefox exige essa ação
            	if (UA.indexOf('Firefox') > -1) {
            		textoSpan = '<span contenteditable="true">';
    					fechaSpan = '</span>';
            	}
        		return '<a style="background-color:#aaaaaa; padding:0" contenteditable="false" class="custom" href="'+corpo+idUsuario+'" tabindex="-1" >'+ textoSpan + retornaNome(row[0]) + fechaSpan +'</a>&nbsp;';}
        });

		},1000));
</script>
