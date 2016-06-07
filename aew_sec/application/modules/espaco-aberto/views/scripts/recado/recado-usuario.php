<?php $recadosRelacionados = $this->recado->selectComentariosRelacionados();?>

<li id="recado<?php echo $this->recado->getId();?>" class="box-recado recado  box padding-all-10 col-lg-12" <?php echo $this->style;?>>
    
    <!-- Imagem usuário -->
    <figure class="media-left">
        <img class="img-circle shadow-center" src="<?php echo $this->recado->getUsuarioAutor()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30,false, 30, 30,true);?>" alt="Foto do Usuário" width="36" height="36">
    </figure>    
    
    <!-- Corpo do recado -->
    <div class="media-body">
        
        <h6 class="page-header">
            <small>
                <i class="fa fa-calendar"></i> Enviado em <?php echo $this->SetupDate($this->recado->getData());?> por 
                <b><?php echo $this->ShowUsuario($this->recado->getUsuarioAutor());?></b>
            </small>
        </h6>
        
        <!-- corpo do recado e ações-->
        <div class="recado-corpo">
            
            <p><?php echo $this->recado->getComentario();?></p>
            <div class="acao-item text-right">
                
                <?php if($this->recado->getUrlResponder($this->usuarioLogado)):?> 
                <a class="btn btn-link" name="responder-recado" rel="<?php echo $this->recado->getUrlResponder($this->usuarioLogado);?>" id="<?php echo  $this->recado->getId();?>" usuario="<?php echo $this->recado->getUsuarioAutor()->getId();?>" title="Responder" data-toggle="tooltip"  data-placement="top">
                    <i class="fa fa-reply fa-cinza-escuro"></i>
                </a>
                <?php endif;?>
                
                <?php if($this->recado->getUrlApagar($this->usuarioLogado) && !$recadosRelacionados):?>
                <a class="btn btn-link" name="apagar-recado" rel="<?php echo  $this->recado->getUrlApagar($this->usuarioLogado);?>" id="<?php echo  $this->recado->getId();?>" title="Apagar" data-toggle="tooltip"  data-placement="top" idrecado="<?php echo $this->recado->getId();?>">
                    <i class="fa fa-trash-o fa-vermelho"></i>
                </a>
                <?php endif;?>
            </div>
            
            <!-- Formulario para resposta -->
            <div class="form-resposta"></div>
        </div>
    </div>
    
    <?php if($recadosRelacionados):?>
        <!-- recados relacionados -->
        <ul class="media-list">

            <?php 
                $this->opacity += 0.03;
                $this->style = "style='background-color:".$this->ConverterHexRgba('#00AF00',$this->opacity).";border-color:#CECECE;margin-bottom:0 '";
            ?>
            
            <?php foreach($recadosRelacionados as $recadoRelacionado):
                $this->recado = $recadoRelacionado;
                echo $this->render('recado/recado-usuario.php');
            endforeach;?>
        </ul>
        
        <?php $this->style = '';?>
    <?php endif;?>
    
</li>