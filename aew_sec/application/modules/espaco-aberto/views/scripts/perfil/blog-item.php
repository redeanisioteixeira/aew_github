<h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($this->itemPerfil->getDataCriacao());?></small></h6>
<a href="<?php echo  $this->itemPerfil->getLinkPerfil();?>">
    <h5 class="link-verde uppercase"><b><?php echo $this->itemPerfil->getTitulo();?></b></h5>
</a>
<p class="link-cinza-escuro"><?php echo $this->ReadMore(trim($this->itemPerfil->getTexto()), 200, '[...]', '', $this->itemPerfil->getLinkPerfil(), 'success');?></p>