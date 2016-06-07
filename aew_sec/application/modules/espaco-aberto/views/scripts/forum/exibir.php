<?php 	$allowedParticipante = $this->participante;?>

    <!-- Tópico do Forum -->
    <article class="box">
        <header class="text-center">
            <h2 class="headline-ea"><?php echo $this->topico->getTitulo() ?></h2>
        </header>
        <section class="margin-top-20">
            <?php echo $this->topico->getMensagem() ?>
        </section>
        <footer class="">
            <div class="text-muted pull-left">
                <span class="clearfix padding-top-05">
                    <i>Postado por:</i>
                    <a href="<?php echo $this->topico->getUsuarioAutor()->getLinkPerfil() ?>">
                        <?php echo $this->escape($this->topico->getUsuarioAutor()->getNome()); ?> 
                    </a>
                </span>    
                <span class="clearfix padding-top-05">
                    <?php echo $this->SetupDate($this->topico->getDatacriacao(), 1); ?>
                </span>
            </div>
            
            <div class="pull-right">
             <?php $perfil = $this->perfilTipo.'/'.$this->usuarioPerfil->getId().'/id/'.$this->topico->getId() ?>   
            <a href="/espaco-aberto/forum/editar/<?php echo $perfil ?>"
                class="btn btn-default"
                data-toggle="tooltip" 
                data-placement="top"
                title="Editar topico"
            > 
             <i class="fa fa-edit"></i>
            </a>
                <a href="/espaco-aberto/forum/apagar/<?php echo $perfil ?>"
            class="btn btn-default"
            data-toggle="tooltip" 
            data-placement="top"
            title="Apagar topico"
           >
             <i class="fa fa-trash"></i>
         </a>
            </div>
        </footer>    
    </article>
    <!-- Formulário con summernote-->
    <section class="box">
        <h4 class="text-center">Escreva um comentário</h4>
        <?php
        $this->form->setName("Form0");
        $this->form->getElement('idcomutopicomsg')->setValue(0);
        $this->form->getElement('idcomutopico')->setValue($this->topico->getId());
        $this->form->getElement('mensagem')->setAttrib('id', "Text0");
        
        echo $this->form;
        ?>
    </section>
    <?php
        $this->topicoId = $this->topico->getId(); 
        echo $this->render('forum/lista-comentarios.php');
    ?>

