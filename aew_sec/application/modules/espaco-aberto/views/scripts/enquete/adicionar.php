<?php ?>
<script type="text/javascript">

function alteraData(){
	var data1 = document.getElementById('dataInicio').value;
	var data2 = document.getElementById('dataFim').value;

	if (!( parseInt( data2.split( "/" )[2].toString() + data2.split( "/" )[1].toString() + data2.split( "/" )[0].toString() ) > parseInt( data1.split( "/" )[2].toString() + data1.split( "/" )[1].toString() + data1.split( "/" )[0].toString() ) ))
	{
		document.getElementById('dataFim').value = document.getElementById('dataInicio').value;
	}

}

</script>
<div class="Adicionar">
    <?php echo $this->adicionar; ?>
</div>