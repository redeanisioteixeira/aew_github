<?php if($this->isAjax):?>

    <header class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="menu-cinza headline margin-none"><b><?php echo $this->pageTitle;?></b></h3>
    </header>

    <div class="modal-body">
        <p class="text-justify">
            Este espaço serve para você denunciar qualquer coisa que você considere imprópria, basta fornecer o endereço da página onde esse conteúdo se localiza e uma mensagem descrevendo do que se trata e por que você acha que essa página merece a denuncia.
        </p>
        
        <div class="resp text-center"></div>
        
        <?php echo $this->formDenunciar;?>
    </div>

<?php else:?>
    <p class="text-justify">
        Este espaço serve para você denunciar qualquer coisa que você considere imprópria, basta fornecer o endereço da página onde esse conteúdo se localiza e uma mensagem descrevendo do que se trata e por que você acha que essa página merece a denuncia.
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo $this->formDenunciar;?>
        </div>
    </div>
<?php endif;?>