<div class="col-lg-12">
    <?php echo $this->paginationControl($this->usuarios,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#usuarios-lista','divUrl' => $this->urlPaginator));?>
</div>

<div class="col-lg-12">
    <div class="row">
        <table class="table table-striped">

            <!-- Cabeçalho da tabela -->
            <thead class="menu-cinza">
                <tr>
                    <th class="text-center"></th>
                    <th class="text-center">Nome</th>
                    <th class="text-center hidden-xs">E-mail</th>
                    <th class="text-center hidden-xs">Tipo de usuário</th>
                    <th class="text-center hidden-xs">Categoria</th>
                    <th class="text-center hidden-xs">
                        <?php if(isset($this->href['relatorio_usuario'])):?>
                            <a class="btn btn-default btn-xs    " href="<?php echo $this->href['relatorio_usuario'];?>" title="Gerar relatório em .PDF" ><i class="fa fa-file-pdf-o"></i> Gerar relatório em .PDF</a>
                        <?php endif;?>
                    </th>
                </tr>
            </thead>

            <!-- Corpo da tabela -->
            <tbody>    

                <?php foreach($this->usuarios as $usuario):?>
                <tr class="<?php echo ($usuario->getFlativo() ? '' : 'inativo');?>">
                        <td class="text-center">
                            <a href="/administracao/usuario/exibir/id/<?php echo $usuario->getId();?>" title="Ver dados do usuário">
                                <?php echo $usuario->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_64X64,false, 40, 40, false, 'img-circle shadow-center');?>
                            </a>
                        </td>

                        <td><?php echo $usuario->getNome();?></td>
                        <td class="hidden-xs"><?php echo strtolower($usuario->getEmail());?></td>
                        <td class="hidden-xs"><?php echo ucfirst($usuario->getUsuarioTipo()->getNome());?></td>
                        <td class="hidden-xs"><?php echo $usuario->categoria();?></td>
                        <td class="text-center hidden-xs">

                            <?php if(isset($this->href['editar_usuario']) || isset($this->href['apagar_usuario']) || isset($this->href['trocarsenha_usuario'])):?>
                                <div class="btn-group btn-group-sm">

                                    <?php if(isset($this->href['editar_usuario'])):?>
                                        <a class="btn btn-primary" href="<?php echo $this->href['editar_usuario'].DS.'id'.DS.$usuario->getId();?>" title="editar">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif;?>

                                    <?php if(isset($this->href['trocarsenha_usuario'])):?>
                                        <a class="btn btn-warning" href="<?php echo $this->href['trocarsenha_usuario'].DS.'id'.DS.$usuario->getId();?>" title="trocar senha">
                                            <i class="fa fa-key"></i>
                                        </a>
                                    <?php endif;?>

                                    <?php if(isset($this->href['apagar_usuario'])):?>
                                        <a class="btn btn-danger" href="<?php echo $this->href['apagar_usuario'].DS.'id'.DS.$usuario->getId();?>" title="apagar">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php endif;?>

                                    <a class="btn btn-success" href="<?php echo $this->href['perfil_usuario'].DS.'usuario'.DS.$usuario->getId();?>" title="Ver perfil">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                </div>
                            <?php endif;?>

                         </td>
                    </tr> 
                <?php endforeach;?>

            </tbody>
        </table>
    </div>
</div>

<div class="col-lg-12">
    <?php echo $this->paginationControl($this->usuarios,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#usuarios-lista','divUrl' => $this->urlPaginator));?>
</div>