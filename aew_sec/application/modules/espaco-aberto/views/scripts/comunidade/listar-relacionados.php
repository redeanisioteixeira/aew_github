<section class="row margin-top-30">
    <?php if(count($this->comunidade)==0): ?>
    <div class="box text-center">
        <span class="text-info">
            <i class="fa fa-exclamation"></i> Nenhuma comunidade relacionada.
        </span>
    </div>
    <?php else :?>    
    <ul class="list-unstyle ">
        
        <?php foreach($this->comunidade as $comunidade): ?>
        <li class="margin-all-10">
            <a class="" href="/espaco-aberto/comunidade/exibir/comunidade/<?php echo $comunidade->getIdComunidade() ?>">
                <?php echo $comunidade->getNome() ?>
            </a>
            <a class="btn btn-danger btn-xs"
               id="btnRemoverRelacao"
               href="<?php echo $this->url(array('module' => 'espaco-aberto', 
                                         'controller' => 'comunidade',
                                         'action' => 'remover-relacao', 
                                         'id' => $this->id, 'relacionado' => $comunidade->getIdComunidade()), null, true)?>" >
                Remover relação
            </a>
             
        </li>

        <?php endforeach ?>
    </ul>        
    <?php endif ?>
    
</section>
