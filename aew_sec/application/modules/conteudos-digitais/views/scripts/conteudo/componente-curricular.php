<table class="table table-striped table-responsive">
    <thead class="menu-cinza">
        <th>Nivel Ensino/Categoria</th>
        <th>Nome</th>
    </thead>
    <tbody>
        <?php foreach($this->componentes as $componente):?>
            <tr>
                <td>
                    <b><?php echo ($componente->getNivelEnsino()->getNome() ? $componente->getNivelEnsino()->getNome() : $componente->getCategoriaComponentCurricular()->getNome());?></b>
                </td> 
                <td><?php echo $componente->getNome();?></td> 
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
