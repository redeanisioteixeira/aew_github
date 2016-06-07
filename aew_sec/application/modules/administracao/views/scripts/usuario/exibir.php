<div class="panel panel-default">
    <div class="panel-body">
        <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Criado em <?php echo $this->SetupDate($this->usuario->getDataCriacao());?></small></h6>
        
        <div class="col-lg-4">
            <div class="row">
                <figure><?php echo $this->usuario->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_160X160,false,0, 200,false, 'thumbnail shadow-center margin-auto');?></figure>

                <?php if($this->trocarSenha):?>
                    <div class="panel panel-warning margin-top-10 shadow-center">
                        <div class="panel-heading">
                            <h5 class="margin-none">Digite e confirme a nova senha do usuário:</h5>
                        </div>
                        <div class="panel-body">
                            <?php echo $this->trocarSenha;?>
                        </div>
                    </div>
                <?php endif;?>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Dados do usuário -->
            <table class="table table-striped table-responsive">
                    
                <tbody>
                    <tr>
                        <td class="uppercase link-laranja"><i class="fa fa-user"></i> Login</td> 
                        <td class="link-laranja">:</td>
                        <td class="text-left"><?php echo $this->escape($this->usuario->getUsername());?></td>
                    </tr>

                    <tr>
                        <td class="uppercase link-laranja"><i class="fa fa-check-circle-o"></i> Nome</td>
                        <td class="link-laranja">:</td>
                        <td class="text-left"><?php echo $this->escape($this->usuario->getNome());?></td>
                    </tr>

                    <?php if($this->usuario->getMatricula() != ''):?>
                        <tr>
                            <td class="uppercase link-laranja"><i class="fa fa-check-circle-o"></i> Matricula Nº</td> 
                            <td class="link-laranja">:</td>
                            <td class="text-left"><?php echo number_format($this->usuario->getMatricula()/10, 1, '-', '');?></td>
                        </tr>
                    <?php endif;?>
                    
                    <tr>
                        <td class="uppercase link-laranja"><i class="fa fa-check-circle-o"></i> Tipo usuário</td> 
                        <td class="link-laranja">:</td>
                        <td class="text-left"><?php echo $this->escape($this->usuario->getUsuarioTipo()->getNome());?></td>
                    </tr>

                    <tr>
                        <td class="uppercase link-laranja"><i class="fa fa-check-circle-o"></i> Categoria</td> 
                        <td class="link-laranja">:</td>
                        <td class="text-left"><?php echo $this->usuario->categoria();?></td>
                    </tr>

                    <tr>
                        <td class="uppercase link-laranja"><i class="fa fa-envelope"></i> E-mail institucional</td> 
                        <td class="link-laranja">:</td>
                        <td class="text-left"><?php echo $this->escape($this->usuario->getEmail());?></td>
                    </tr>

                    <tr>
                        <td class="uppercase link-laranja"><i class="fa fa-envelope"></i> Email pessoal</td> 
                        <td class="link-laranja">:</td>
                        <td class="text-left"><?php echo $this->escape($this->usuario->getEmailPessoal());?></td>
                    </tr>

                    <tr>
                        <td class="uppercase link-laranja"><i class="fa fa-calendar-check-o"></i> Data nascimento</td> 
                        <td class="link-laranja">:</td>
                        <td class="text-left"><?php echo date("d/m/Y",strtotime($this->usuario->getDataNascimento()));?></td>
                    </tr>

                    <tr>
                        <td class="uppercase link-laranja"><i class="fa fa-venus-mars"></i> Sexo</td> 
                        <td class="link-laranja">:</td>
                        <td class="text-left"><?php echo ($this->usuario->getSexo()=='m'?'Masculino':'Feminino');?></td>
                    </tr>

                    <?php if($this->usuario->getTelefone() && $this->usuario->getTelefone() != '0000000000'):?>
                        <tr>
                            <td class="uppercase link-laranja"><i class="fa fa-phone-square"></i> Telefone</td> 
                            <td class="link-laranja">:</td>
                            <td class="text-left"><?php echo $this->escape($this->usuario->getTelefone());?></td>
                        </tr>
                    <?php endif;?>

                    <?php if($this->usuario->getRg()!= '' && $this->usuario->getRg()!= '00000000000'):?>
                        <tr>
                            <td class="uppercase link-laranja"><i class="fa fa-check-circle-o"></i> RG</td> 
                            <td class="link-laranja">:</td>
                            <td class="text-left"><?php echo number_format($this->usuario->getRg()/100, 2,'-','');?></td>
                        </tr>
                    <?php endif;?>

                    <?php if($this->usuario->getCpf() != ''):?>
                        <tr>
                            <td class="uppercase link-laranja"><i class="fa fa-check-circle-o"></i> CPF</td> 
                            <td class="link-laranja">:</td>
                            <td class="text-left"><?php echo number_format($this->usuario->getCpf()/100, 2, '-', '.');?></td>
                        </tr>
                    <?php endif;?>


                    <?php
                        $endereco =  ($this->usuario->getEndereco() && $this->usuario->getEndereco() != '0000000000' ? $this->escape($this->usuario->getEndereco()) : '')
                            .($this->usuario->getComplemento() ? ', '.$this->usuario->getComplemento() : '')
                            .($this->usuario->getNumero() ? ', Nº '.$this->usuario->getNumero() : '')
                            .($this->usuario->getBairro() && $this->usuario->getBairro() != '00000000000' ? ', '.$this->usuario->getBairro() : '')
                            .($this->usuario->getMunicipio()->getNome() != '' ? ', '.$this->usuario->getMunicipio()->getNome() : '')
                            .($this->usuario->getEstado()->getNome() != '' ? ', '.$this->usuario->getEstado()->getNome() : '')
                            .($this->usuario->getCep() && $this->usuario->getCep() != '000000000' ? ', CEP: '.number_format($this->usuario->getCep()/1000, 3, '-', '.') : '');                                
                    ?>
                        
                    <?php if($endereco):?>
                        <tr>
                            <td class="uppercase link-laranja"><i class="fa fa-map-marker"></i> Endereço</td> 
                            <td class="link-laranja">:</td>
                            <td class="text-left"><?php echo $endereco;?></td>
                        </tr>
                    <?php endif;?>
                        
                    <?php if($this->usuario->getEscola()->getNome() != ''):?>
                        <tr>
                            <td class="uppercase link-laranja"><i class="fa fa-check-circle-o"></i> Escola</td> 
                            <td class="link-laranja">:</td>
                            <td class="text-left"><?php echo $this->usuario->getEscola()->getNome()?></td>
                        </tr>
                    <?php endif;?> 

                    <?php if($this->usuario->getSerie()->getNome() != ''):?>
                        <tr>
                            <td class="uppercase link-laranja"><i class="fa fa-check-circle-o"></i> Serie</td> 
                            <td class="link-laranja">:</td>
                            <td class="text-left"><?php echo $this->usuario->getSerie()->getNome() ?></td>
                        </tr>
                    <?php endif;?>
                        
                </tbody>
            </table>
            
            <?php if($this->apagar):?>
                <div class="alert alert-danger">
                    <?php echo $this->apagar;?>
                </div>
            <?php endif;?>
            
        </div>
    </div>
    
    <?php if(isset($this->href['editar_usuario']) || isset($this->href['apagar_usuario']) || isset($this->href['trocarsenha_usuario'])):?>    
        <div class="panel-footer text-center">
            <div class="btn-group">

                <?php if(isset($this->href['editar_usuario'])):?>
                    <a class="btn btn-primary" href="<?php echo $this->href['editar_usuario'].DS.'id'.DS.$this->usuario->getId();?>" title="editar">
                        <i class="fa fa-edit"></i> editar
                    </a>
                <?php endif;?>

                <?php if(isset($this->href['trocarsenha_usuario'])):?>
                    <a class="btn btn-warning" href="<?php echo $this->href['trocarsenha_usuario'].DS.'id'.DS.$this->usuario->getId();?>" title="trocar senha">
                        <i class="fa fa-key"></i> trocar senha
                    </a>
                <?php endif;?>

                <?php if(isset($this->href['apagar_usuario'])):?>
                    <a class="btn btn-danger" href="<?php echo $this->href['apagar_usuario'].DS.'id'.DS.$this->usuario->getId();?>" title="apagar">
                        <i class="fa fa-trash"></i> apagar
                    </a>
                <?php endif;?>

                <a class="btn btn-success" href="<?php echo $this->href['perfil_usuario'].DS.'usuario'.DS.$this->usuario->getId();?>" title="Ver perfil">
                    <i class="fa fa-eye"></i> ver perfil
                </a>

            </div>
        </div>
    <?php endif;?>
    
</div>