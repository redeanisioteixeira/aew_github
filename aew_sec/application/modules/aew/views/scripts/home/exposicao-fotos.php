<section id="maximage-main">

    <div id="maximage-buttons">
        <a id="arrow_left" class="fa fa-angle-left fa-4x"></a>
        <a id="arrow_right" class="fa fa-angle-right fa-4x"></a>
    </div>

    <div id="maximage">
        <?php
            foreach($this->imagens as $imagens):
                $foto = new Aew_Model_Bo_Foto($imagens);
                $texto_credito = "";
                if($foto->selectMetaDados()):
                    $texto_credito .= ($foto->getTituloOriginal() == "" ? "" : ucfirst($foto->getTituloOriginal())." ");
                    $texto_credito .= ($foto->getComentarioFoto() == "" ? "" : "<br/>".ucfirst($foto->getComentarioFoto())." ");
                    $texto_credito .= ($foto->getArtista() == "" ? "" : "por <b>".$foto->getArtista()."</b>");
                endif;
                echo "<img src='/$imagens' tooltip='$texto_credito'/>";
            endforeach;
        ?>
    </div>

    <div class="container hidden-xs hidden-ms">
        <div class="row">
            <article class="col-lg-4 pull-right">

                <div class="col-lg-12">

                    <div class="row">
                        
                        <h4 class="menu-amarelo page-header text-center"><b>Bahia: pessoas, lugares, culturas e olhares. Exposição fotográfica do Ambiente Educacional Web</b></h4>
                        <p class="link-branco">No intuito de criar um ambiente que considere diferentes olhares e perspectivas sobre os patrimônios materiais e imateriais do nosso Estado, a tela de acesso ao Ambiente Educacional Web foi transformada em um espaço de exposição fotográfica. Neste local, será possível apresentar variados olhares fotográficos sobre as diversas regiões da Bahia, suas pessoas e seus lugares.</p>
                        <p class="link-branco">Caso seja fotógrafo e considere que sua região ou cultura ainda não estejam representados nesta exposição entre em contato através do <a class="faleConosco" data-toggle="modal" data-target="#modalGeral" href="/home/faleconosco"><b class="link-branco">fale conosco</b></a> para ampliarmos este acervo e oferecer subsídios para os professores e estudantes contarem com mais material para conhecer nossas “Bahias”.</p>
                    
                    </div>
                    
                </div>

            </article>
        </div>
    </div>

</section>