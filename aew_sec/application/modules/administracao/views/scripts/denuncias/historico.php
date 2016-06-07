<div class="listagem">
	<div class="acoes">
		<a href="<?php echo $this->urlListaDenuncias?>" title="Listar denúncias">Listar denúncias</a>
	</div>
	<br/>
	<div>
            <ul class='listaDenuncias'>
                <?php foreach ($this->denuncias as $denuncia){?>        
                    <li class='<?php $this->cycle(array("","cinza"))->next() ?>'>
			<div class='dados'><b>
                            <a href='<?php echo $denuncia->getLinkPerfil()?>'> 
                                <?php echo $denuncia->getTitulo() ?>
                            </a></b>
                            <br /><?php echo  $this->datetime($denuncia->getDatacriacao()) ?>
			</div>
                    </li>
                <?php } ?>
            </ul>
            <?php if(count($this->denuncias)==0){?> 
            <div>
                Nenhuma denúncia encontrada.
            </div>
            <?php } ?>
	</div>
</div>