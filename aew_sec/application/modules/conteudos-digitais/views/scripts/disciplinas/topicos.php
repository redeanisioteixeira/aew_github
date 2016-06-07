<?php if($this->isAjax == true):?>
    <?php echo $this->topicos;?>
<?php else:?>
    
        <!-- ativa offcanvas menu -->
        <a id="botao-topico" class="visible-sm visible-xs" data-toggle="offcanvas">
            <i class="btn btn-info btn-sm fa fa-list" title="topicos" data-toggle="tooltip" data-placement="top"></i>
        </a>
        
        <div id="box-canvas-topicos" class="row-offcanvas row-offcanvas-left">

            <aside id="inicio" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sidebar-offcanvas">
                <div class=" row">
                    <div class="panel panel-info">
                        <h5 class="panel-heading menu-preto margin-none"><b>ASSUNTOS DA DISCIPLINA:</b></h5>
                        <div class="panel-body">
                            <?php echo $this->topicos;?>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <a id="topo-conteudo" href="#inicio" class="scroll_nag hidden">topo</a>

                <div class="panel panel-default">
                    <h5 name="topico" class="panel-heading margin-none"><b>Assunto selecionado : </b></h5>
                    <div id="lista_conteudos" class="panel-body"></div>
                </div>
            </div>
        </div>
<?php endif;?>