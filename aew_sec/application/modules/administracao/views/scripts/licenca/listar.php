<div class="pull-left" <?php echo ($this->isAjax ? 'style="max-height: 660px; overflow-x: hidden; overflow-y: auto"' : '');?>">
    <table class="table table-striped">

        <thead class="menu-cinza">
            <tr>
                <th class="text-center" width="3%"></th>
                <th class="text-center" width="27%"><b>LICENÇA</b></th>
                <?php if(isset($this->href['editar_licenca']) || isset($this->href['apagar_licenca'])):?>
                    <th class="text-center hidden-xs hidden-ms" width="45%"><b>DESCRIÇÃO</b></th>
                    <th class="text-center" width="18%"><b>SITE REFERENCIADO</b></th>
                    <th class="text-center" width="7%"></th>
                <?php else:?>
                    <th class="text-center hidden-xs hidden-ms" width="55%"><b>DESCRIÇÃO</b></th>
                    <th class="text-center" width="15%"><b>SITE REFERENCIADO</b></th>
                <?php endif;?>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->licencas as $licenca):?>

                <?php
                    $this->licenca = $licenca;
                    $this->dependencia = false;
                    echo $this->render('licenca/listar-licenca.php');
                ?>

                <?php foreach($this->licencasRelacionadas as $licencaRelacionada):?>
                    <?php if($licencaRelacionada->getIdconteudolicencapai() == $licenca->getId()):?>
                        <?php
                            $this->dependencia = true;
                            $this->licenca = $licencaRelacionada;
                        ?>
                        <?php echo $this->render('licenca/listar-licenca.php');?>
                    <?php endif;?>
                <?php endforeach;?>

            <?php endforeach;?>
        </tbody>
    </table>
</div>