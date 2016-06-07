<?php
return; 
$usuario = Sec_Controller_Action::getLoggedUserObject();
if (!$usuario || $this->getModule() == 'administracao'):
    return; 
endif;

/*--- Se o chat estiver desativado então oculta a janela ---*/

$ativar = $usuario->selectFeedContagem()->getFlchatativo();
$this->headScript()
    ->setAllowArbitraryAttributes(true)
    ->appendFile('/assets/js/chat.js', 'text/javascript', array('async'=>'async'));

$this->placeholder('barraChat')->captureStart();
?>
    <aside id="chat">
        <input type="hidden" name="alerta" value="<?php echo ($this->getModule() != 'espaco-aberto' ? "0" : "1");?>"/>
               
        <audio id="beep-chat-one" preload="auto">
            <source src="/assets/audio/utopiacr.wav" controls>
        </audio>

        <audio id="beep-chat-two" preload="auto">
            <source src="/assets/audio/online.wav" controls>
        </audio>

        <div id="area-chat" class="shadow-left <?php echo ($ativar == false ? 'ativar' : 'desativar');?>">

            <?php if ($ativar == false):?>
            <div id="aviso-chat-desativado" class="hidden-xs hidden-ms">
                   <i class="fa fa-cloud absolute" <?php echo $this->corfonte;?>></i><h5 class="text-center link-branco absolute"><b>Ative o chat e converse com seus colegas</b></h5>
               </div>
            <?php endif;?>

            <a id="botao-chat" title="<?php echo ($ativar == false ? 'abrir':'fechar');?> chat" class="shadow-left <?php echo ($ativar == false ? 'abrir-chat' : '');?> <?php echo ($this->getModule() == 'espaco-aberto' ? 'margin-top-10' : '');?>">
                <i class="fa fa-comments fa-2x"></i>
            </a>
                
            <div id="contatos" class="margin-top-<?php echo ($this->getModule() != 'espaco-aberto' ? '40' : '100');?>">
                
                <?php if($this->getModule() != 'espaco-aberto'):?>
                    <div class="margin-bottom-10 page-header hidden-sm hidden-xs">
                        <?php echo $this->placeholder('barraSuperior');?>
                    </div>
                <?php endif;?>

                <textarea class="filtrar_usuario hidden-xs hidden-ms" name="filtrar_usuario" rows="1" maxlength="80" placeholder="buscar colega..."></textarea>
                <ul class="contatos"></ul>

            </div>
        </div>

        <div id="janelas"></div>

    </aside>

<?php $this->placeholder('barraChat')->captureEnd();?>
