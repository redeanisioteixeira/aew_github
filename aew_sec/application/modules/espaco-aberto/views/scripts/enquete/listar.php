<?php ?>
<div class="listagem">
    <div class="acoes">
        <div class="clear"></div>
    </div>
<?php
// Carrega permissões de dono e moderador do perfil
$allowedDono = $this->isAllowed($this->usuarioLogado,'espaco-aberto', 'administrar') || $this->perfilDono;
$allowedModerador = $this->isAllowed($this->usuarioLogado,'espaco-aberto', 'administrar') || $this->perfilModerador;

if($allowedModerador):
?>
    <div class="acoes">
        <?php if($allowedModerador): ?>
            <a style='font-size:14px' href="<?php echo $this->url(array('module' => 'espaco-aberto', 'controller' => 'enquete',
						           'action' => 'adicionar', $this->perfilTipo => $this->perfilId), null, true); ?>"
						           title="Adicionar Enquete">Adicionar Enquete</a>
	<?php endif; ?>
    </div>
<?php endif; ?>
    <ul class='listaConteudosDigitais'>
	<?php foreach($this->enquetes as $enquete){ ?>
            <a style='font-size:14px' href=''>
                <div class='acao-item'>
                    <a style='font-size:12px' href='""'>apagar</a>
                </div>
                <div class='acao-item'>
                    <a style='font-size:12px' href='""'>editar</a> |
                    <a style='font-size:12px' href='".."'>apagar</a>
                </div>
	    </a>
	    <li class='". $class ." ' >
		<div style='font-size:14px' class='dados-topo'></div>
		<div style='font-size:14px' class='dados-conteudo lista-simples'>
		<div style='font-size:14px' class='data'>
                <div>
                <div style='font-size:14px' class='titulo'>
                    ". $abreLink . $enquete['pergunta']. $fechaLink . "
                </div> 
                                                                    $adminPost . "
                </div>
                <div class='dados-base'></div>
            </li>
        <?php } ?>
    </ul>
    <div style='font-size:12px' class='PaginacaoListar'>" . $this->enquetes . "</div>
    <?php if(($this->enquetes)==0) {?>
        <div style='font-size:14px' class="conteudo-margin">
            Nenhuma enquete encontrada.
        </div>
    <?php }?>
</div>