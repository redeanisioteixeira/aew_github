<style>
    body{
        font-family: Arial, sans-serif;
        font-size: 10pt;
    }

    h1{
        text-align: center;
        font-size: 14px;
    }

    table.listaObjetos {
        width: 100%;
        border: 1px solid #CCCCCC;
        border-collapse:collapse;
    }

    table.listaObjetos tr td,
    table.listaObjetos tr th{
        border: 1px solid #CCCCCC;
        padding: 4px;
        text-align: left;
    }

    table.listaObjetos .impar tr{
        background-color: #f0f0f0;
    }
</style>

<?php
if(count($this->objetos)>0){
?>
    <table class="listaObjetos">
	<tr>
            <th style="with: 20px;">No.</th>
                <th>Nome</th>
		<th>Login</th>
		<th>Categoria</th>
		<th>E-mail Institucional</th>
		<th>Tipo de usuário</th>
		<th>Data criação</th>
        </tr>
	<?php   $cabecalho = true; $contador = 1;
	foreach($this->objetos as $objeto)
        {
            $class = $this->cycle(array('impar',''))->next();
        ?>
        <tr class="">
            <td><?php echo $contador ?></td>
            <td><?php echo strtoupper($objeto->getNome())?></td>
            <td><?php echo strtoupper($objeto->getUsername())?></td>';			
            <td><?php echo $objeto->categoria() ?></td>
	    <td><?php echo strtoupper($objeto->getEmail())?></td>
	    <td><?php echo strtoupper($objeto->getUsuarioTipo()->getNome())?></td>';
            <td><?php echo $this->date($objeto->getDataCriacao())?></td>
	</tr>
        <?php 	$contador++; } ?>
    </table>
<?php  }?>
