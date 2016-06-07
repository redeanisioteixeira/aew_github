<?php
$usuario = Sec_Controller_Action::getLoggedUserObject();
if (!$usuario):
    return; 
endif;

$this->placeholder('barraSuperior')->captureStart();
?>
    <ul class="opcoes-menu-espaco-aberto list-inline text-center padding-all-05 margin-none">

        <li class="recados">
            <a name="alerta" title="recados" idalerta="1">
                <i class="fa fa-envelope contador" <?php echo $this->corfonte;?>></i>
                <?php echo $this->contadorPendentes(1);?>
            </a>
        </li>

        <li class="colegas">
            <a name="alerta" title="colegas" idalerta="2">
                <i class="fa fa-users contador" <?php echo $this->corfonte;?>></i>
                <?php echo $this->contadorPendentes(2);?>
            </a>
        </li>

        <li class="comunidades">
            <a name="alerta" title="comunidades" idalerta="3">
                <i class="fa fa-comments-o contador" <?php echo $this->corfonte;?>></i>
                <?php echo $this->contadorPendentes(3);?>
            </a>
        </li>

        <li class="albuns">
            <a name="alerta" title="álbuns" idalerta="4">
                <i class="fa fa-camera contador" <?php echo $this->corfonte;?>></i>
                <?php echo $this->contadorPendentes(4);?>
            </a>
        </li>

        <li class="agenda" style="display: none">
            <a name="alerta" title="agenda" idalerta="5">
                <i class="fa fa-calendar contador" <?php echo $this->corfonte;?>></i>
                <?php echo $this->contadorPendentes(5);?>
            </a>
        </li>

        <li class="blog">
            <a name="alerta" title="blog" idalerta="6">
                <i class="fa fa-rss-square contador" <?php echo $this->corfonte;?>></i>
                <?php echo $this->contadorPendentes(6);?>
            </a>
        </li>
        
        <?php if($usuario->isSuperAdmin()):?>
            <li class="online">
                <a name="alerta-online"  class="cursor-normal" title="usuário(s) conectado(s)">
                    <i class="fa fa-plug contador link-<?php echo ($this->getModule() == 'espaco-aberto' ? 'branco': 'cinza-escuro');?>"></i>
                    <?php echo $this->ContadorPendentes(7);?>
                </a>
            </li>
        <?php endif;?>
            
    </ul>
<?php $this->placeholder('barraSuperior')->captureEnd();?> 