<section class="panel panel-default">
    <div class="panel-heading">
        <h4><?php echo $this->escape($this->denuncia->getTitulo()); ?></h4>
    </div>
    <div class="panel-body">
        <ul class="list-unstyled">
            <li><b>Autor: </b><?php echo $this->showUsuario($this->denuncia->getUsuario()); ?></li>
            <li><b>Criada em: </b><?php echo $this->datetime($this->denuncia->getDatacriacao()); ?></li>
            <li><b>URL: </b><a href="<?php echo $this->escape($this->denuncia->getUrl()); ?>" target="_Blank"><?php echo $this->denuncia->getUrl(); ?></a></li>
            <li><b>Mensagem: </b><?php echo $this->escape($this->denuncia->getMensagem()); ?></li>
        </ul>
    </div>
    <div class="panel-footer">
        <?php  if(false == $this->denuncia->getFlvisualizada()): ?> 
            <a class="btn btn-default" href="/administracao/denuncia/visualizada/id/<?php echo $this->denuncia->getId() ?>"title="Marcar como visualizada">Marcar como visualizada</a>
        <?php endif; ?>

        <a class="btn btn-danger" href="/administracao/denuncia/apagar/id/<?php echo $this->denuncia->getId() ?>" >Apagar</a>									 
    </div>
</section>			