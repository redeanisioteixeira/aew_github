<?php if($this->isAjax):?>

    <header class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="menu-cinza headline margin-none"><b><?php echo $this->pageTitle;?></b></h3>
    </header>

    <div class="modal-body">
        <p class="text-justify">
            Este espaço serve para você informar quaisquer dificuldade ocorrida na visualização do conteúdo. Tem alguma dúvida, sugestão ou crítica a fazer? Então entre em contato com a gente. Sua opinião é fundamental para o nosso aperfeiçoamento.
        </p>
        <div class="resp text-center"></div>
        
        <?php echo $this->formFaleconosco;?>
        
    </div>

<?php else:?>

    <p class="text-justify">
        Este espaço serve para você informar quaisquer dificuldade ocorrida na visualização do conteúdo. Tem alguma dúvida, sugestão ou crítica a fazer? Então entre em contato com a gente. Sua opinião é fundamental para o nosso aperfeiçoamento.
    </p>
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo $this->formFaleconosco;?>
        </div>
    </div>
    
<?php endif;?>