<?php
//Date Picker adicionado para melhorar e otimizar o cadastro
//$this->headScript()->appendFile('/assets/js/plugins/datepicker/js/bootstrap-datepicker.js', null,array('async'=>''));
//$this->headLink()->appendStylesheet('/assets/js/plugins/datepicker/css/datepicker.css');

//$this->render('_componentes/_cadastro-aluno.php');
$this->render('_componentes/_cadastro-servidor.php');

?>
<section id="cadastro-usuario">
    
    <!--  Renderiza formulario -->
    <div class="col-lg-6 col-md-6">
        <div class="row">
            <?php echo $this->placeholder('cadastroServidor');?>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-6">
        <h4 class="link-preto page-header"><b>Infomações sobre o cadastro</b></h4>
        <p>O <b class="link-verde">Espaço Aberto</b> é uma Rede Social Educacional voltada para estudantes e professores da escolas públicas baianas, tem como objetivo potencializar a construção e a troca de conhecimentos, estimulando a socialização e a colaboração no ambiente escolar.</p> 
        <p><strong>Sobre o cadastro:</strong></br>
        <ol>
            <li>Informe os dados solicitados no formulário de cadastramento;</li>
            <li>Será enviada uma mensagem para o seu email com as informações referentes ao acesso. Favor, cetificar-se de ter digitado o email corretamente;</li>
            <li>Visite a página de acesso e aproveite as funcionalidades disponíveis neste ambiente.</li>
        </ol>

        <p>Saiba mais <a href="/home/sobre">sobre o AEW</a></p>

        <h4 class="link-preto page-header"><b>Estudantes,</b></h4>
        <p>Em breve vocês também poderão acessar a rede social <b class="link-verde">Espaço Aberto</b>. Até lá, continuem utilizando o Ambiente Educacional Web para compartilhar os conteúdos digitais educacionais, sites temáticos e softwares de apoio a produção.</p>
    </div>
    
</section>
