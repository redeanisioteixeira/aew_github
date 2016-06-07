<div class="row">
    <?php
        /*--- Solicitações pendentes ---*/
        $this->itensPerfil   = $this->solicitacoes;
        $this->linkTitulo    = $this->usuarioPerfil->getUrlListaColegas();
        $this->titulo        = "Solicitações pendentes";
        $this->acaoTitulo    = "Minhas solicitações pendentes";
        $this->icone         = "fa-thumbs-up";
        $this->cor           = "vermelho";
        $this->opcaoPendente = true;
        echo $this->render('_componentes/_perfil-item-container.php');

        $this->cor           = "verde";
        $this->opcaoPendente = false;
    
        /*--- Colegas ---*/
        $this->itensPerfil = $this->colegas; 
        $this->linkTitulo  = $this->usuarioPerfil->getUrlListaColegas();
        $this->titulo      = "Meus colegas";
        $this->acaoTitulo  = "Ver todos meus colegas";
        $this->icone       = "fa-users"; 
        echo $this->render('_componentes/_perfil-item-container.php'); 

        /*--- Comunidades ---*/
        $this->itensPerfil = $this->comunidades; 
        $this->linkTitulo  = $this->usuarioPerfil->getUrlListaComunidades();
        $this->titulo      = "Minhas Comunidades";
        $this->acaoTitulo  = "Ver todas minhas comunidades";
        $this->icone       = "fa-comments-o";
        echo $this->render('_componentes/_perfil-item-container.php');
    
        /*--- Blog ---*/
        $this->itensPerfil = $this->blogs; 
        $this->linkTitulo  = $this->usuarioPerfil->getUrlListaBlogs();
        $this->titulo      = "Meus Blogs";
        $this->acaoTitulo  = "Ver todos meus blogs";
        $this->icone       = "fa-rss-square";
        echo $this->render('_componentes/_perfil-item-container.php');

        /*--- Últimos recados ---*/
        $this->itensPerfil = $this->recados;
        $this->linkTitulo  = $this->usuarioPerfil->getUrlListaRecados();
        $this->titulo      = "Últimos recados";
        $this->acaoTitulo  = "Meus últimos recados";
        $this->icone       = "fa-envelope";
        echo $this->render('_componentes/_perfil-item-container.php');
    ?>    
</div>