<header class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h3 class="menu-cinza headline margin-none"><b>Trocar imagem do perfil</b></h3>
</header>

<div class="modal-body">
    <div class="box">
        <p><b class="link-vermelho">ATENÇÃO:</b><p> 
        <p>Não envie fotos ou imagens que sejam de personagens de desenho animado, pessoas famosas, nudez, trabalho artístico ou material protegido por direitos autorais.</p>
        <p>Para maiores informações veja os <a href="/home/termo-condicoes-uso" target="_blank"><b>termos e condições de uso</b></a>.</p>
    </div>
    <div class="upload-form">
        <?php echo $this->trocarImagemForm;?>
        <?php echo $this->render('/_componentes/progress-bar.php');?>
        <div class="resposta"></div>
    </div>
</div>