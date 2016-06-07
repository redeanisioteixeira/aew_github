<?php
/**
 * controller para gerencia de url's de acoes para edicao de tags
 */
class Administracao_TagController extends Sec_Controller_Action
{
    public function init()
    {
	parent::init();
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        $acl = $this->getHelper('Acl');
        $visitanteAction = array('listar-conteudos-digitais', 'listar-ambientes-de-apoio');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
        $editorAction = array('listar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::EDITOR, $editorAction);
        $coordenadorAction = array('adicionar', 'editar', 'apagar', 'exibir');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::COORDENADOR, $coordenadorAction);
        $this->setLinkListagem('/administracao/tag/listar');
        $this->setLinkExibicao('/administracao/tag/exibir/id/');
        $this->setActionApagar('/administracao/tag/apagar/id/');
        $this->setActionSalvar('/administracao/tag/salvar/id/');
        $this->setActionAdicionar('/administracao/tag/adicionar');
        $this->setActionEditar('/administracao/tag/editar/id/');
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    public function initScripts()
    {
        parent::initScripts();
        $this->view->headScript()->appendFile('/assets/js/administracao/custom.js');
    }
    /**
     * Redireciona para lista de tags
     */
    public function homeAction()
    {
        $this->_redirect('/administracao/tag/listar');
    }
    /**
     * Lista de palavras chaves de um conteúdo digital
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

        $opcoes = $this->opcoesAcessoConteudo($tagsBo);
        
        $options = array();
        $options['where'] = 'exists(select conteudodigitaltag.idtag from conteudodigitaltag where conteudodigitaltag.idtag = tag.idtag AND tag.busca > 0)';
        $options['orderBy'] = array('left(sem_acentos(nometag),1)', 'sem_acentos(nometag)');
        
        $tags = $tagsBo->select(0, 0, $options);
        
        $this->view->href = $opcoes;
        $this->view->tags = $tags;
    }
    /**
     * Lista de palavras chaves de um Ambiententes de apoio
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
    }
    /**
     * Adiciona nova palavra chave
     * @return Zend_View 
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar Tag');
        
    	$form = new Administracao_Form_Tag();
        
        $form->setAction($this->getActionAdicionar());

        if($this->getRequest()->isPost())
        {
		    if($form->isValid($this->getRequest()->getPost()))
            {
                $this->salvarTag($form);
            }
		}

		$this->carregarTags();
        $this->view->adicionar = $form;
    }
    /**
     * Edita palavra chave
     * @return Zend_View
     */
    public function editarAction()
    {
        $this->setPageTitle('Editar Tag');
    	$tag = new Aew_Model_Bo_Tag();
        
        $id = $this->getParam("id",0);
        $tag->setId($id);
        if(!$tag->selectAutoDados())
        {
    	    $this->flashError('Tag não encontrada');
            $this->_redirect($this->getLinkListagem());
    	}
        
        $form = new Administracao_Form_Tag();
        $form->setAction($this->getActionEditar($id));
        $form->populate($tag->toArray());

		if($this->getRequest()->isPost())
        {
		    if($form->isValid($this->getRequest()->getPost()))
            {
                $this->salvarTag($form);
            }
		}

		$this->carregarTags();
        $this->view->editar = $form;
    }
    /**
     * Lista de palavras chaves
     * @return Zend_View
     */
    public function listarAction()
    {
        if($this->isPost())
        {
            if($this->getPost('sim'))
            {
				$this->apagarTagMultiplo($this->getPost('tagsApagar'));
			}
		}

        $options = array();
        if($this->isAjax())
        {
			echo $this->view->headScript()->setFile('/assets/js/administracao/custom.js');
            $this->view->isAjax = true;
            $this->disableLayout();
        }
        
        $this->setPageTitle('Lista de tags');
        
        $limite = 50;

        $formFiltrar = new Administracao_Form_FiltroTag();
        $formFiltrar->populate($this->getRequest()->getParams());

        $pagina  = $this->getParam("pagina", 1);
        $letra   = $this->getRequest()->getParam('letra', false);
        $tag     = $this->getRequest()->getParam('tag', false);
        $semuso  = $this->getRequest()->getParam('semuso', false);
        $buscada = $this->getRequest()->getParam('buscada', false);
        
        if(!empty($letra))
		{
            $options['where']['UPPER(sem_acentos(LEFT(tag.nometag,1))) = ?'] = $letra;
        }

        if(!empty($tag))
		{
			$expRegular = "CONCAT('[\',CHR(39),'\',CHR(34),'\',CHR(46),'\',CHR(45),'\',CHR(58),'\',CHR(59),'\',CHR(44),'\s”“]+')";
			//$options["where"]["LOWER(sem_acentos(REGEXP_REPLACE(tag.nometag,$expRegular, '','g'))) LIKE LOWER(sem_acentos(REGEXP_REPLACE(?,$expRegular, '','g')))"] = "%$tag%";
            $options['where']['LOWER(sem_acentos(tag.nometag)) LIKE LOWER(sem_acentos(?))'] = "%$tag%";
        }

        if(!empty($semuso))
		{
	        $options['where']['not exists(select conteudodigitaltag.idtag from conteudodigitaltag where conteudodigitaltag.idtag = tag.idtag)'] = "";
	        $options['where']['not exists(select ambientedeapoiotag.idtag from ambientedeapoiotag where ambientedeapoiotag.idtag = tag.idtag)'] = "";
		}

        if(!empty($buscada))
		{
            $options['where']['tag.busca > ?'] = 0;
			$options['orderBy'] = 'tag.busca DESC';
        }
        
    	$tagsBo = new Aew_Model_Bo_Tag();

        $tags = $tagsBo->selectNumUsos(null, $limite, $pagina, $options);
        $tags = $tagsBo->getAsPagination($tags, $pagina, $limite);

        $opcoes = $this->opcoesAcessoConteudo($tagsBo);

        $this->view->params = $this->getRequest()->getParams();
        $this->view->formFiltrar = $formFiltrar;
        $this->view->tags = $tags;
        $this->view->href = $opcoes;

        $formApagar = new Aew_Form_Apagar();
		$formApagar->setAction($this->getLinkListagem());

		$classes = $formApagar->getElement('sim')->getAttrib('class');

		$formApagar->getElement('sim')->setAttrib('class',$classes.' box-loading-ajax');
		$formApagar->getElement('sim')->setAttrib('data-message','Apagando tags');

		$tags = $formApagar->createElement('hidden','tagsApagar');
		$formApagar->addElement($tags);

		$letra = $formApagar->createElement('hidden','letra');
		$formApagar->addElement($letra);

		$tag = $formApagar->createElement('hidden','tag');
		$formApagar->addElement($tag);

		$semuso = $formApagar->createElement('hidden','semuso');
		$formApagar->addElement($semuso);

		$pagina = $formApagar->createElement('hidden','pagina');
		$formApagar->addElement($pagina);

		$formApagar->populate($this->getRequest()->getParams());

        $formApagar->getElement('mensagem')->setValue('Tem certeza que deseja apagar a(s) tag(s) selecionada(s)?');
		$this->view->formApagar = $formApagar;
    }
    /**
     * Exibe uma tag
     * @return Zend_View
     */
    public function exibirAction()
    {
        $this->setPageTitle('Exibir Tag');
    	$tagBo = new Aew_Model_Bo_Tag();
        
        $id = $this->getParam("id", 0);

        $opcoes = $this->opcoesAcessoConteudo($tagBo);
        
        $tagBo->setId($id);
        $tag = $tagBo->select(1);
                
        if(!$tag)
        {
    	    $this->flashError('Tag não encontrada');
            $this->_redirect($this->getLinkListagem());
    	}

        $tagRef = $tagBo->selectNumUsos($id);
        
        $this->view->tag    = $tag;
        $this->view->tagRef = $tagRef[0];
       
        $this->view->href  = $opcoes;
    }
    /**
     * Apaga uma tag
     * @return Zend_View
     */
    public function apagarAction()
    {
        $form = new Aew_Form_Apagar();
        
        $id = $this->getParam('id', false);
        
        $tag = new Aew_Model_Bo_Tag();
        $tag->setId($id);
        
        if (!$tag->selectAutoDados()){
            $this->flashError('Nenhum registro encontrado');
            $this->_redirect($this->getLinkListagem());
        }

        $this->setPageTitle('Apagar tag : '.$tag->getNome());
        
        $form->setAction($this->getActionApagar($id));
        $form->getElement('mensagem')->setValue('Tem certeza que deseja apagar esta tag?');
        
        if($this->isPost())
        {
            if($this->getPost('nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            
            if($tag->delete())
            {
                $this->flashMessage('Tag apagada com sucesso', $this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar apagar o registro', $this->getLinkListagem());
            }
        }

        $this->view->apagar = $form;
        $this->view->tag = $tag;
    }
    /**
     * Salva Mudanças 
     * @return type flash message ou redireciona
     */
    public function salvarTag($form)
    {
		$id = $form->getValue('idtag');

        $acao = ($id ? 'editada' : 'inserida');

        $tagBo = new Aew_Model_Bo_Tag();

		$options = array();

		//--- Expressão regular que evita criar tag iguais por causa de carateres especiais como ponto (.)
		//$expRegular = "CONCAT('[',CHR(92),CHR(39),CHR(92),CHR(34),CHR(92),CHR(46),CHR(45),CHR(58),CHR(59),CHR(44),'\s”“]+')"; 
		$expRegular = "CONCAT('[\',CHR(39),'\',CHR(34),'\',CHR(46),'\',CHR(45),'\',CHR(58),'\',CHR(59),'\',CHR(44),'\s”“]+')";
		$options["where"]["LOWER(sem_acentos(REGEXP_REPLACE(tag.nometag,$expRegular, '','g'))) = LOWER(sem_acentos(REGEXP_REPLACE(?,$expRegular, '','g')))"] = $form->getValue('nometag');

		$tag = $tagBo->select(1, 0, $options);
		if($tag)
		{
			if($tag->getId() != $id)
			{
				$this->flashError('Nome de tag já foi cadastrada', ($id ? $this->getActionEditar($id) : $this->getActionAdicionar()));
			}
		}

        $tagBo->exchangeArray($form->getValues());
		$tagBo->setNome(strtolower($form->getValue('nometag')));

        if(!$tagBo->save())
        {
            $this->flashError('Erro ao salvar tag', ($id ? $this->getActionEditar($id) : $this->getActionAdicionar()));
        }
        else 
        {
            $this->flashMessage('Tag '.$acao.' com sucesso', $this->getLinkExibicao($tagBo->getId()));
        }
    }
    /**
     * Apaga palavras chave em masa
     * @param array() $tags
     * @return type array associativo de tags
     */
    public function apagarTagMultiplo($tags)
    {
        $erro = false;
        if(!empty($tags))
        {
                $tags = explode(',',$tags);
            $tag = new Aew_Model_Bo_Tag();

                foreach($tags as $id)
                {
                    $tag->setId($id);
                        if(!$tag->delete())
                        {
                            $this->flashError('Houve um problema ao tentar apagar a(s) tag(s)');
                                $erro = true;
                                break;
                        }
                }

                if(!$erro)
                {
                        $this->flashMessage('Tag(s) apagada(s) com sucesso');
                }
        }
    }
    /**
     * Carrega palavras chaves
     */
    public function carregarTags()
    {
    $template = "";
    //--- Nuvens de tag
    $tagsBo = new Aew_Model_Bo_Tag();
    $options = array();
    $options['where'] = "tag.nometag NOT LIKE '%lote%' AND tag.nometag NOT LIKE CONCAT('%',CHR(34),'%') AND tag.nometag NOT LIKE CONCAT('%',CHR(39),'%') AND tag.nometag NOT LIKE CONCAT('%',CHR(13),'%') AND nometag = regexp_replace(nometag, '[.;:]','')";
    $options['orderBy'] = 'tag.nometag ASC';

    $tags = $tagsBo->select(0,0, $options);
    if($tags)
    {
        $stringTags = '';
        foreach($tags as $tag)
        {
            if($tag->getNome())
            {
                $nomeTag = str_replace(chr(34), '', $tag->getNome());
                $nomeTag = str_replace(chr(39), '', $tag->getNome());
                $nomeTag = str_replace(chr(13), '', $tag->getNome());
                $stringTags .= '"'.$nomeTag.'",'."\n";
            }
        }
        $template .= "var availableTags = [$stringTags];"."\n";
    }

    $this->view->headLink()
            ->appendStylesheet('/assets/plugins/jquery-ui-themes-1.11.4/themes/blitzer/theme.css')
            ->appendStylesheet('/assets/plugins/jquery-ui-themes-1.11.4/themes/blitzer/jquery-ui.css');

    $this->view->headScript()
            ->appendFile('/assets/plugins/jquery-ui-1.11.4/jquery-ui.js','text/javascript')
            ->appendFile('/assets/js/autocomplete.js','text/javascript')
            ->prependScript($template,'text/javascript');
    }
}
