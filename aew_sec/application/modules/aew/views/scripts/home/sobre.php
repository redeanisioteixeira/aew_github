<div class="row">
    <div class=" col-lg-12">

        <div class=" col-lg-7 col-md-7 col-sm-12 col-xs-12">
            <div class="row">
                <p class="text-justify">Espaço pedagógico multidisciplinar criado para que estudantes e professores possam acessar, compartilhar e construir conhecimentos por meio das novas tecnologias da informação e da comunicação. O Ambiente Educacional Web está com uma nova versão adaptada para dispositivos móveis e, em breve, contará com um acervo de Conteúdos Digitais proveniente da produção de todos os projetos estruturantes da Secretaria e do Instituto Anísio Teixeira.</p>

				<div id="box-destaque" class="box-video-destaque margin-top-20 margin-bottom-20">
					  <div class="box-conteudo-video text-center">
						<video id="video-destaque" class="trans90 rounded shadow-center" poster="/destaques/apresentacao-aew.png" controls="" preload="">
							<source src="/destaques/apresentacao-aew.1980p.webm" type="video/webm">
						</video>
					  </div>  
				</div>

                <p class="text-justify"><b class="link-preto">O Ambiente Educacional Web é constituído pelas seguintes seções:</b></p>

                <ul class="list-unstyled">
                    <li>
						<a href="<?php echo $this->urlConteudosDigitais;?>"><h5 class="menu-azul"><b><i class="fa fa-check-square-o"></i> Conteúdos Digitais</b></h5></a>
						<p class="text-justify">Conteúdos digitais registrados em licenças livres, produzidos, pesquisados e catalogados pelos Educadores da Rede Anísio Teixeira e dos Projetos Estruturantes . São mais de 3.500 conteúdos organizados por modalidades de ensino, componentes curriculares, áreas do conhecimento e temas transversais: sequências didáticas, animações, simulações, jogos, vídeos, áudios, aulas, imagens e textos das mais variadas disciplinas, acompanhados por documentos de orientação pedagógica.</p>
					</li>

                    <li>
						<a href="<?php echo $this->urlSitesTematicos;?>"><h5 class="menu-vermelho"><b><i class="fa fa-check-square-o"></i> Sites Temáticos</b></h5></a>
						<p class="text-justify">Sites desenvolvidos por instituições parceiras que disponibilizam Conteúdos Digitais Educacionais Livres das mais variadas áreas de conhecimento e temas transversais.</p>
					</li>

                    <li>
						<a href="<?php echo $this->urlAmbientesApoio;?>"><h5 class="menu-amarelo"><b><i class="fa fa-check-square-o"></i> Apoio a Produção e Colaboração</b></h5></a>
						<p class="text-justify">Softwares livres e ambientes digitais pedagógicos de aplicação específica para a produção de conteúdos digitais educacionais e de colaboração.</p>
					</li>

                    <li class="desativado"><a href="<?php echo $this->urlEspacoAberto;?>"><h5 class="menu-verde"><b><i class="fa fa-check-square-o"></i> Espaço Aberto</b></h5></a></li>

                    <li>
						<a href="<?php echo $this->urlProfessorWeb;?>"><h5 class="menu-roxo"><b><i class="fa fa-check-square-o"></i> Blog da Professora Online e Professor Web</b></h5></a>
						<p class="text-justify">Produção de conteúdos, elaborados e estabelecidos com base nos princípios do uso pedagógico, tecnológico, lúdico e interdisciplinar. As postagens, além de informativas, utilizam uma linguagem apropriada para a comunidade escolar, de fácil acesso ao estudante e ao professor, e estão classificadas por área de conhecimento e temas transversais.</p>
					</li>

                    <li>
						<a href="<?php echo $this->urlTvAnisioTeixeira;?>"><h5 class="menu-marron"><b><i class="fa fa-check-square-o"></i> TV Anísio Teixeira</b></h5></a>
						<p class="text-justify">As produções da TV Anísio Teixeira abordam conteúdos curriculares e temas transversais de uma forma lúdica e interdisciplinar, e possuem o diferencial de relacionar estes conteúdos com o cotidiano das escolas baianas e das suas comunidades, suas histórias e suas culturas. A concepção, criação e produção das peças audiovisuais educacionais fazem parte de um processo de formação contínuo no qual os professores são motivados a compreender, criar e experimentar novos processos educacionais, utilizando a linguagem audiovisual.</p>	
					</li>

                    <li><a href="<?php echo $this->urlSobreFotos;?>"><h5 class="menu-preto"><b><i class="fa fa-check-square-o"></i> Sobre a exposição fotográfica da tela de acesso</b></h5></a></li>
                </ul>
            </div>
            <!-- <a class="credit" href="<?php echo $this->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'creditos'), null, true); ?>">Créditos</a>-->
        </div>

        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 hidden-sm hidden-xs">
            <a href="<?php echo $this->url(array('controller' => 'home', 'action' => 'home', 'module' => 'aew' ));?>">
                <img class="img-responsive" alt="Tela inicial da seção Conteúdos Digitais" src="/assets/img/img_aew00.png">
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <h5 class="menu-cinza"><b>Informações de Contato:</b></h5>
    <div class="row">
        <div class="col-lg-2">
            <label><i class="fa fa-phone-square fa-lg"></i> Telefone</label>
        </div>
        <div class="col-lg-10">
            <b class="link-preto">+55 71 3116-9061</b>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2">
            <label><i class="fa fa-envelope-square fa-lg"></i> E-mail</label>
        </div>
        <div class="col-lg-10">
            <a class="link-preto" href="mailto:rede.anisio@educacao.ba.gov.br"><b>rede.anisio@educacao.ba.gov.br</b></a>
        </div>
    </div>

    <div class="row desativado">
        <div class="col-lg-2">
            <label><i class="fa fa-comments-o fa-lg"></i> Blog</label>
        </div>
        <div class="col-lg-10">
            <a class="link-preto" href="http://educadores-tvat.blogspot.com.br" target="_blank"><b>Educadores da TV Anísio Teixeira</b></a>
        </div>
    </div>

</div>