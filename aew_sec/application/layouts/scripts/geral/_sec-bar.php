<?php $this->placeholder('secBar')->captureStart(); ?>
<section id="portal" class="navbar-wrapper<?php echo ($this->usuarioLogado ? ' fixed':'');?>">
        <div class="container">
            <a class="btn pull-left" target="_top" rel="acceso portal da educação" href="http://educadores.educacao.ba.gov.br">
                <h4 class="link-cinza-claro">
                    <b class="link-cinza-escuro">educacao</b>.ba.gov.br
                </h4>
            </a>

            <ul class="list-inline hidden-sm hidden-xs pull-right">
                <li>
                    <a class="btn link-laranja hover-laranja" target="_top" href="http://escolas.educacao.ba.gov.br/">
                        <i class="fa fa-caret-right fa-lg"></i> escolas
                    </a>
                </li>
                <li>
                    <a class="btn link-verde hover-verde" target="_top" href="http://estudantes.educacao.ba.gov.br/">
                        <i class="fa fa-caret-right fa-lg"></i> estudantes
                    </a>
                </li>
                <li>
                    <a class="btn link-vermelho hover-vermelho" target="_top" href="http://educadores.educacao.ba.gov.br/">
                        <i class="fa fa-caret-right fa-lg"></i> educadores
                    </a>
                </li>
                <li>
                    <a class="btn link-azul hover-azul" target="_top" href="http://institucional.educacao.ba.gov.br/">
                        <i class="fa fa-caret-right fa-lg"></i> institucional
                    </a>
                </li>
                <li>
                    <a class="btn link-roxo hover-roxo" target="_top" href="http://municipios.educacao.ba.gov.br/">
                        <i class="fa fa-caret-right fa-lg"></i> municípios
                    </a>
                </li>
            </ul>
            
            <!--
            <ul class="list-inline navbar-toggle pull-right" style="padding: 0;">
                <li><a class="btn link-laranja" target="_top" href="http://escolas.educacao.ba.gov.br/"><i class="fa fa-university fa-lg"></i></a></li>
                <li><a class="btn link-verde" target="_top" href="http://estudantes.educacao.ba.gov.br/"><i class="fa fa-child fa-lg"></i></a></li>
                <li><a class="btn link-vermelho" target="_top" href="http://educadores.educacao.ba.gov.br/"><i class="fa fa-users fa-lg"></i></a></li>
                <li><a class="btn link-azul" target="_top" href="http://institucional.educacao.ba.gov.br/"><i class="fa fa-building fa-lg"></i></a></li>
                <li><a class="btn link-roxo" target="_top" href="http://municipios.educacao.ba.gov.br/"><i class="fa fa-building-o fa-lg"></i></a></li>
            </ul>

            <iframe src="http://educadores.educacao.ba.gov.br/ambiente_educacional" id="portal_educacao" frameborder="0" height="38" width="100%" scrolling="no" style="display:none"></iframe>
            -->

        </div>    
    </section>
<?php $this->placeholder('secBar')->captureEnd(); ?>