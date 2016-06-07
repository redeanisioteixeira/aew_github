<div id="tags-lista">
    <div class="col-lg-12">
        <div class="itens-isotope">
            <?php $alfabeto = '';?>
            <?php foreach($this->tags as $tag):?>

                <?php $letraPrimeira = strtoupper(substr($this->retiraAcentuacao($tag->getNome()),0,1));?>

                <?php if($letraPrimeira):?>
                    <?php if($alfabeto != $letraPrimeira):?>
                            <?php if($alfabeto):?>
                                </ul></div>
                            <?php endif;?>

                            <div class="col-lg-4">
                            <h3 class="menu-azul page-header"><b><?php echo $letraPrimeira;?></b></h3>
                            <ul class="list-unstyled list-inline">

                            <?php $alfabeto = $letraPrimeira;?>
                    <?php endif;?>

                    <li class="comma">
                        <?php if(isset($this->href['editar_tag'])):?>
                            <a class="link-cinza-escuro" href="/conteudos-digitais/conteudos/tags/id/<?php echo $tag->getId();?>"><?php echo trim($this->escape($tag->getNome()));?></a>
                        <?php else:?>
                            <?php echo trim($this->escape($tag->getNome()));?>
                        <?php endif;?>
                    </li>
                    
                <?php endif;?>
            <?php endforeach;?>
            </ul></div>
        </div>
    </div>
</div>
