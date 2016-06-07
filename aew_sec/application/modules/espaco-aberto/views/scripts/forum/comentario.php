<?php $comentariosRelacionados = $this->comentario->selectComentariosRelacionados();?>
<?php $topicoId = (isset($this->topicoId)) ? $this->topicoId : 0; ?>
<li id="recado<?php echo $this->comentario->getId();?>" class="box-recado recado  box padding-all-10 col-lg-12 margin-top-05" <?php echo $this->style;?>>
    
    <!-- Imagem usuário -->
    <figure class="media-left">
        <img class="img-circle shadow-center" src="<?php echo $this->comentario->getUsuarioAutor()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30,false, 30, 30,true);?>" alt="Foto do Usuário" width="36" height="36">
    </figure>    
    
    <!-- Corpo do recado -->
    <div class="media-body">
        
        <h6 class="page-header">
            <small>
                <i class="fa fa-calendar"></i> Enviado em <?php echo $this->SetupDate($this->comentario->getData());?> por 
                <b><?php echo $this->ShowUsuario($this->comentario->getUsuarioAutor());?></b>
            </small>
        </h6>
        
        <!-- corpo do recado e ações-->
        <div class="recado-corpo">
            
            <p><?php echo $this->comentario->getComentario();?></p>
            
            <div class="acao-item text-right">
                <?php if($this->comentario->getUrlResponder($this->usuarioLogado)):?> 
                    <a class="btn btn-link" name="responder-recado" rel="<?php echo $this->comentario->getUrlResponder($this->usuarioLogado);?>/topico/<?php echo $topicoId ?>" id="<?php echo  $this->comentario->getId();?>" usuario="<?php echo $this->comentario->getUsuarioAutor()->getId();?>" title="Responder" data-toggle="tooltip"  data-placement="top">
                        <i class="fa fa-reply fa-cinza-escuro"></i>
                    </a>
                <?php endif;?>
                
                <?php if($this->comentario->getUrlApagar($this->usuarioLogado) && !$recadosRelacionados):?>
                    <a class="btn btn-link" name="apagar-recado" rel="<?php echo  $this->comentario->getUrlApagar($this->usuarioLogado);?>" id="<?php echo  $this->comentario->getId();?>" title="Apagar" data-toggle="tooltip"  data-placement="top" idrecado="<?php echo $this->comentario->getId();?>">
                        <i class="fa fa-trash-o fa-vermelho"></i>
                    </a>
                <?php endif;?>
                    
            </div>
            
            <!-- Formulario para resposta -->
            <div class="form-resposta"></div>
        </div>
    </div>
    
    <?php if($comentariosRelacionados):?>
        <!-- recados relacionados -->
        <ul class="media-list padding-top-15">

            <?php 
                $this->opacity += 0.03;
                $corfundo = $this->ConverterHexRgba('#00AF00',$this->opacity);
                $this->style = "style='background-color:$corfundo; border-color: #FFF;'";
            ?>
            
            <?php foreach($comentariosRelacionados as $recadoRelacionado):
                $this->comentario = $recadoRelacionado;
                echo $this->render('forum/comentario.php');
            endforeach;?>
        </ul>
        
        <?php $this->style = '';?>
    <?php endif;?>
    
</li>
