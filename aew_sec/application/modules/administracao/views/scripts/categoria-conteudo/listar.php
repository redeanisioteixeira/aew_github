<div class="pull-left">
    <table class="table table-striped">

        <thead class="menu-cinza">
            <tr>
                <th class="text-center" width="3%"></th>
                <th class="text-center" width="17%"><b>CATEGORIA</b></th>
                <th class="text-center hidden-xs hidden-ms" width="45%"><b>DESCRIÇÃO</b></th>
                <th class="text-center" width="15%"></th>
                <th class="text-center" width="10%"><b>ATIVO</b></th>
                <th class="text-center" width="10%"><b>DESTACADO</b></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->categorias as $categoria):?>

                <?php
                    $this->categoria = $categoria;
                    $this->dependencia = false;
                    echo $this->render('categoria-conteudo/listar-categoria.php');
                ?>

                <?php foreach($this->categoriasRelacionadas as $categoriaRelacionada):?>
                    <?php
                        if($categoriaRelacionada->getIdconteudodigitalcategoriapai() == $categoria->getId()):
                            $this->dependencia = true;
                            $this->categoria = $categoriaRelacionada;echo $this->render('categoria-conteudo/listar-categoria.php');
                        endif;
                    ?>
                <?php endforeach;?>

            <?php endforeach;?>
        </tbody>
    </table>
</div>