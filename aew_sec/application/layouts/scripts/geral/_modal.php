<?php $this->placeholder('modal')->captureStart();?>
    <!-- Modal -->
    <div id="modalGeral" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog margin-top-50">
            
            <!-- conteúdo carregado da View --> 
            <div class="modal-content"></div>
        </div>
    </div>

    <!-- Box de mensagem ajax -->
    <div class="box-ajax fade">
        <div class="content">
            <center>
                <div class="link-branco" style="margin-top: 20%">
                    <div class="shadow rounded padding-all-20 col-lg-offset-5 col-lg-2 col-md-offset-5 col-md-2 col-sm-offset-2 col-sm-8  col-xs-offset-2 col-xs-8" style="background-color: rgba(0,0,0,0.5);">
                        <span class="clearfix">
                            <i class="fa fa-refresh fa-spin font-size-150"></i>
                        </span>
                        <span class="clearfix"><b class="menssage-box-ajax">Carregando</b></span>
                        <span class="clearfix"><small>aguarde um momento</small></span>
                    </div>
                </div>
            </center>
        </div>
    </div>
<?php $this->placeholder('modal')->captureEnd();?>
