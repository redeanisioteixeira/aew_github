<?php ?>
<div class="listagem">
    <ul class='listaConteudosDigitais'>
        <?php foreach($this->objetos as $topico){ 
            if ( $countLine++ == 0 ) $corLinha = ( $corLinha == "" ) ? "cinza" : "";
            if ( $countLine == 1 ) $countLine = 0;
            $class = $corLinha;?>
        <li class='' >
            <div style='font-size:14px' class='dados-topo'></div>
            <div style='font-size:14px' class='dados-conteudo conteudo-foto'>
                <a style='font-size:14px' href=''>
                    <div style='font-size:14px' class='foto'>
                        <?php echo $this->comunidade->getFotoPerfil()->cacheFoto()?>
                    </div>
                    <div style='font-size:14px' class='titulo'>Comunidade:<?php $this->comunidade->getNome() ?></div>
                        Tópico: <?php echo $topico->getTituo() . retornaNome($topico->getmensagem()) ?>
                </a>
            </div>
            <div style='font-size:14px' class='dados-base'></div>
        </li>
        <?php } ?>
    </ul>
    <div style='font-size:12px' class='PaginacaoListar'>" . $this->objetos . "</div>
    <div class="conteudo-margin">
            Nenhum tópico encontrado.
    </div>
</div>