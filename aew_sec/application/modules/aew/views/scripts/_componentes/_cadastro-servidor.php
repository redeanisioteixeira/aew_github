<?php $this->placeholder('cadastroServidor')->captureStart();?>
    <div id="cadastro">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 title="Cadastro de Servidor Público" class="margin-none"><i class="fa fa-pencil"></i> Cadastro de Servidor Público</h4>
            </div>

            <div class="panel-body">
                <?php echo $this->formServidor;?>
            </div>
            
            <div class="panel-footer">
                Já tem cadastro no <b class="link-verde">Espaço Aberto</b>? Então, efetue seu <b><a href="/">login</a></b> ou se esqueceu a sua senha clique <a href="/usuario/esqueci-a-senha"><b>aqui</b></a>
            </div>
            
        </div>
    </div>
<?php $this->placeholder('cadastroServidor')->captureEnd();?>