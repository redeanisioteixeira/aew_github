<?php
class ConteudosDigitais_TagController extends Sec_Controller_Action
{
    /**
     * configura permissoes de acesso as actions
     */
    public function init()
    {
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        $acl = $this->getHelper('Acl');

        $visitanteAction = array('home', 'listar-conteudos-digitais');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
    }
    /**
     * Redireciona para listar tags
     */
    public function homeAction()
    {
        $this->_redirect('/administracao/tag/listar');
    }
    /**
     * Lista nuvem de tags
     * @return Zend_View
     */
    public function listarConteudosDigitaisAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $this->setPageTitle('Nuvem de Tags');
        
        $tagsBo = new Aew_Model_Bo_Tag();

        $options = array();
        $options['where'] = 'exists(select conteudodigitaltag.idtag from conteudodigitaltag where conteudodigitaltag.idtag = tag.idtag)';
        $options['orderBy'] = array('left(sem_acentos(nometag),1)', 'sem_acentos(nometag)');
        
        $tags = $tagsBo->select(0, 0, $options);
        
        $this->view->href = $opcoes;
        $this->view->tags = $tags;
        
        $this->render('listar');
    }
    /**
     * Lista tags de ambientes de apoio
     * @return Zend_View
     */
    public function listarAmbientesDeApoioAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $this->setPageTitle('Nuvem de Tags');
        
        $tagsBo = new Aew_Model_Bo_Tag();

        $opcoes = $this->opcoesAcessoConteudo($tagsBo);
        
        $options = array();
        $options['where'] = 'exists(select ambientedeapoiotag.idtag from ambientedeapoiotag where ambientedeapoiotag.idtag = tag.idtag)';
        $options['orderBy'] = array('left(sem_acentos(nometag),1)', 'sem_acentos(nometag)');
        
        $tags = $tagsBo->select(0, 0, $options);
        
        $this->view->href = $opcoes;
        $this->view->tags = $tags;
        
        $this->render('listar');
    }
}