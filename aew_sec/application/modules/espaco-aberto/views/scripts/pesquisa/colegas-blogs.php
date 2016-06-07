<li class="grupo-busca">Blog de colegas</li>
<li class="colega-blog"> 
    <a class="colega-blog" id="2438" comunidade="0" usuario="97">
        <span class="criado">Publicado em 
            <span class="data"><?php echo $this->view->SetupDate($conteudo->getDatacriacao(),1)?></span> por 
            <span class="usuario"><?php echo $conteudo->getNomeDono() ?></span>
        </span>
        <img src="" align="middle">
        <span class="titulo">Um outro olhar sobre a Inclusão Digital</span>   
        <span class="descricao">
            <?php echo strip_tags($conteudo->getDescricao()); ?>
        </span> 
    </a>
</li>