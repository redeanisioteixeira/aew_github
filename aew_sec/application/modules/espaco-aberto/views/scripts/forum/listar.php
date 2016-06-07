<?php 
// Carrega permissões de dono e moderador do perfil
$allowedDono = $this->isAllowed($this->usuarioLogado,'espaco-aberto', 'administrar') || $this->perfilDono;
$allowedParticipante = $this->paticipante;
 ?>
<section class="box">    
    <div><?php echo $this->form; ?></div>
    <?php if($allowedDono): ?>
            <a class="btn btn-circle-sm btn-success pull-right"
                href="<?php echo $this->url(array('module' => 'espaco-aberto',
                                                  'controller' => 'forum',
                                                  'action' => 'adicionar',
                                                  $this->perfilTipo => $this->usuarioPerfil->getId()),
                                                  null, true); ?>" 
               data-toggle="tooltip"
               data-placement="top"
               title="Adicionar Tópico">
                <i class="fa fa-plus"></i>
            </a>
    <?php endif; ?>
</section>


<section class="load-scroll" rel="/espaco-aberto/forum/lista-foruns/comunidade/<?php echo $this->usuarioPerfil->getId()?>">
     <h2 class="text-center">Tópicos</h2>
    <?php echo $this->render('forum/lista-foruns.php'); ?>
    <?php if(count($this->topicos)== 0): ?>
    <section class="box text-center">
        <span class="text-danger"><i class="fa fa-exclamation"></i>Nenhum tópico encontrado</span>
    </section>
    <?php endif;?>
</section>