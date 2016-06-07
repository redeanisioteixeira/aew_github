<?php 
$allowedModerador = $this->isAllowed($this->usuarioLogado,'espaco-aberto', 'administrar') || $this->perfilModerador;

?>

<article class="box">
    <!-- Titulo do Tópico-->
    <h3 class="headline-ea">
         <a href="<?php echo $this->url(array('module' => 'espaco-aberto', 'controller' => 'forum',
             'action' => 'exibir', $this->perfilTipo => $this->usuarioPerfil->getId(), 'id' => $this->topico->getId()), null, true); ?>">
             <?php echo $this->topico->getTitulo() ?> 
         </a>
    </h3>
    <!-- Ações -->
    <hr>
    <?php if($allowedModerador): ?>
    <div class="pull-right">
         <a href="<?php echo '/espaco-aberto/forum/editar/'.$this->perfilTipo.'/'.$this->usuarioPerfil->getId().'/id/'.$this->topico->getId();?>"
           class="btn btn-default"
           data-toggle="tooltip" 
           data-placement="top"
           title="Editar topico"
            > 
             <i class="fa fa-edit"></i>
         </a>
         <a href="<?php echo $this->url(array(
                    'module' => 'espaco-aberto',
                    'controller' => 'forum',
                    'action' => 'apagar',
                    $this->perfilTipo => $this->usuarioPerfil->getId(),
                    'id' => $this->topico->getId()), null, true); ?>"
            class="btn btn-default"
            data-toggle="tooltip" 
            data-placement="top"
            title="Apagar topico"
           >
             <i class="fa fa-trash"></i>
         </a>
    </div>
    <div>
         <!-- data criação -->
         <span class="text-muted"> 
             Tópico criado em <?php echo $this->datetime($this->topico->getDatacriacao()) ?>
         </span>
         <!-- comentários -->
         <span class="text-muted margin-left-30">
             Comentários (<i class="badge-info"> <?php echo count($this->topico->selectComentarios()); ?> </i>)
         </span>
    </div>
    <?php endif; ?>
 </article>