<?php
class ConteudosDigitais_DisciplinasController extends Sec_Controller_Action
{
    private $itemInicio;
    private $resumo;
    private $itemSelecionado;

    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $visitanteAction = array('topicos');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
        $colaboradorAction = array('salvar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::COLABORADOR, $colaboradorAction);
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    function initScripts()
    {
        $this->view->headScript()->appendFile('/assets/js/jquery.scrollTo.min.js');
    }   
    
    function getItemInicio() {
        return $this->itemInicio;
    }

    function setItemInicio($itemInicio) {
        $this->itemInicio = $itemInicio;
    }

    function getResumo() {
        return $this->resumo;
    }

    function getItemSelecionado() {
        return $this->itemSelecionado;
    }

    function setResumo($resumo) {
        $this->resumo = $resumo;
    }

    function setItemSelecionado($itemSelecionado) {
        $this->itemSelecionado = $itemSelecionado;
    }
    /**
     * Salva Disciplina
     * @return 
     */
    public function salvarAction()
    {
    	$this->disableLayout();

        $iddisciplina = $this->getParam('id', false);
        $idtopico     = $this->getParam('topico', false);
        $idtopicopai  = $this->getParam('pai', false);

        $componenteTopico = new Aew_Model_Bo_ComponenteCurricularTopico();
        $options = array();
        $options['where']['e.idComponenteCurricularTopico = ? AND e.idComponenteCurricular = ?'] = array($iddisciplina, $idtopico);

        $topico = $componenteTopico->get($idtopico);

        if($topico == true):

            //--- Verifica se o pai pertence à mesma disciplina
            $topicoPai = $componenteTopico->get($idtopicopai);
            if($topicoPai == false):
                $this->flashError('Tópico pai não encontrado');
                return;
            endif;

            if($topicoPai['idComponenteCurricular'] != $iddisciplina):
                $this->flashError('Tópico pai não pertenece à mesma disciplina');
                return;
            endif;	
						
            $topico['idComponenteCurricularTopicoPai'] = $idtopicopai;
            $resultado = $componenteTopico->save($topico);
            if($resultado):
                $this->flashMessage('Tópico salvado com sucesso');
            else:
                $this->flashError('Tópico não salvo. Tente novamente');
            endif;	
        endif;
    }
    /**
     * Lista tópicos
     * @return Zend_View
     */
    public function topicosAction()
    {
        $usuario = $this->getLoggedUserObject();
        $this->view->isAjax = $this->isAjax();
        if($this->isAjax() == true):
            $this->getHelper('layout')->disableLayout();
        endif;

        $permissao = false;
        if($usuario != null)
        {
            $permissao = ($usuario->getUsuarioTipo()->getNome() == "super administrador" || $usuario->getUsuarioTipo()->getNome() == "administrador" || $usuario->getUsuarioTipo()->getNome() == "coordenador" ? true : false);
        }

        $arr_itens = array();
        $arr_topicos = array();

        $iddisciplina = $this->getParam('id', false);
        
        $this->setResumo($this->getParam('resumo', false));
        $this->setItemSelecionado($this->getParam('item', false));

        $nivelEnsino = new Aew_Model_Bo_NivelEnsino();
        $nivelEnsino->setId(5);
        $disciplinas  = $nivelEnsino->selectComponentesCurriculares();        

        $componenteCurricular = new Aew_Model_Bo_ComponenteCurricular();

        $componenteCurricular->setId($iddisciplina);
        $componente = $componenteCurricular->select(1);
        
        $componenteTopicos = $componenteCurricular->selectComponenteCurricularTopicos();
		
        foreach($componenteTopicos as $topico):

            $arr_itens[$topico->getId()] = array(
                                            0 => $topico->getId(),
                                            1 => $topico->getNome(),
                                            2 => $topico->getIdcomponentecurriculartopicopai(),
                                            3 => $topico->geturl(),
                                            4 => $topico->getflvisivel());

            $id = ($topico->getIdcomponentecurriculartopicopai() == '' ? 0 : $topico->getIdcomponentecurriculartopicopai());

            $arr_topicos[$id][$topico->getId()] = $topico->getNome();
        endforeach;

        $cor  = ($this->isAjax() ? "menu-azul" : "menu-azul headline");

        $div  = "";
        $div .= "<ul id='topicos' class='disciplina-grupos list-unstyled'>";
        foreach($arr_topicos as $key=>$value):
            if($key == 0):
                foreach($value as $key=>$value):
                    $subitens = array_key_exists($key,$arr_topicos);
                    $visivel  = $arr_itens[$key][4]; //--- Verifica se deve visualizar os subitens
                    $itempai  = $arr_itens[$key][2];  

                    $editar = ($permissao == false ? "" : " <div class='editar-topico desativado'>($key/<input class='editar-topico' type='text' value='$itempai' size='4' maxlength='4' idtopico='$key' iddisciplina='$iddisciplina' idanterior='$itempai'>)</div>");
                    $col = ($this->getResumo() ? 6 : 12);    
                    $isotope = ($this->isAjax() ? " class='col-lg-$col itens-isotope'" : "");

                    $div .= "<li$isotope>";

                    $topico = "<h5 class='$cor'><b>$value</b> ($key)</h5>";
                    if($subitens == true):
                        $topico = "<h5 class='$cor'><i class='fa-id$key fa fa-".($visivel == false ? "plus" : "minus")."-circle'></i> <b>$value</b>$editar</h5>";
                        $topico = "<a class='subtopico".($visivel == false ? " collapsed" : "")."' data-toggle='collapse' data-parent='#topicos' href='#subtopico$key' idtopico='$key'>$topico</a>";
                    endif;

                    $div .= $topico;
                    $div .= "<ul id='subtopico$key' class='disciplina-topicos list-unstyled subtopicos ".($subitens == false ? "" : " panel-collapse collapse ".($visivel == false ? "out" : "in"))."'>";

                    if($subitens == true):
                        $div .= $this->_VisualizarSubTopicos($arr_topicos[$key], $arr_topicos, $arr_itens, $iddisciplina, true, $permissao, $col);
                    endif;

                    $div .= "</ul>";
                    $div .= "</li>";
                endforeach;
            endif;
        endforeach;

        $div .= "</ul>";

        $this->setPageTitle($componente->getNome().($iddisciplina == 38 ? ' e Literatura' : ''));
        
        $this->view->componente = $componente;
        if($this->isAjax()):
            $this->view->topicos = "<div class='row'>$div</div>";
        else:
            $this->view->topicos = $div;
        endif;

        $this->view->id = $iddisciplina;
        $this->view->disciplinas = $disciplinas;
        $this->view->isAjax = $this->isAjax();
    }
    /**
     * Visualiza o subtópico
     * @param type $arr_subtopicos
     * @param type $arr_topicos
     * @param type $arr_itens
     * @param type $iddisciplina
     * @param type $unico
     * @param type $permissao
     * @param type $col
     * @return string
     */
    public function _VisualizarSubTopicos($arr_subtopicos, $arr_topicos, $arr_itens, $iddisciplina, $unico = true, $permissao = false, $col = 12)
    {
        $div = "";
        $cor  = ($this->isAjax() ? "azul" : "azul");
        
        foreach($arr_subtopicos as $key=>$value):
            $subitens = array_key_exists($key,$arr_topicos);
            $itemid   = $arr_itens[$key][0];
            $visivel  = $arr_itens[$key][4]; //--- Verifica se deve visualizar os subitens
            $bitly    = $arr_itens[$key][3];
            $itempai  = $arr_itens[$key][2];

            $editar = ($permissao == false ? "" : " <div class='editar-topico desativado'>($key/<input class='editar-topico' type='text' value='$itempai' size='4' maxlength='4' idtopico='$key' iddisciplina='$iddisciplina' idanterior='$itempai'>)</div>");

            if($subitens == true):
                $div .= "<li class='subtopico'>";
                $div .= "<i class='fa-id$key fa fa-".($visivel == false ? "plus" : "minus")."-circle'> <span><a class='subtopico link-cinza-escuro".($visivel == false ? " collapsed" : "")."' data-toggle='collapse' data-parent='#topicos' href='#subtopico$key' idtopico='$key'><b>$value</b></a>$editar</span></i>";
                $div .= "<ul id='subtopico$key' class='disciplina-topicos list-unstyled panel-collapse collapse ".($visivel == false ? "out" : "in")."'>";
                $div .= $this->_VisualizarSubTopicos($arr_topicos[$key], $arr_topicos, $arr_itens, $iddisciplina, false, $permissao, $col);
                $div .= "</ul>";
                $div .= "</li>";
            else:
                $status = (!$this->getItemSelecionado() ? "" : ($this->getItemSelecionado() == $itemid ? "active" : ""));
                if(!$status && !$this->getItemInicio() && !$this->getResumo()):
                    $status = "active";
                    $this->setItemInicio($itemid);
                endif;
                
                if($bitly != ""):
                    $bitly = end(explode('/', $bitly));
                    $url = "/conteudos-digitais/disciplinas/topicos/id/$iddisciplina/item/$itemid";
                    $opcaoTopico = "<i class='fa-$cor fa fa-circle-o'> <span><a name='opcao-topico' class='com-link link-cinza-escuro disciplina-topico $status' href='$url' item='$itemid' bitly='$bitly'>$value</a>$editar</span></i>";
                else:
                    $opcaoTopico = "<i class='fa fa-circle-o'> <span><a class='cursor-normal sem-link link-none $status' item='$itemid'>$value</a>$editar</span></i>";
                endif;
                
                if($unico == false):
                    $div .= "<li>$opcaoTopico</li>";
                else:
                    $div .= "<li class='subtopico'>";
                    $div .= "<ul id='subtopico$key' class='disciplina-topicos list-unstyled padding-none'>";
                    $div .= "<li>$opcaoTopico</li>";
                    $div .= "</ul>";
                    $div .= "</li>";
                endif;
            endif;
        endforeach;

        return $div;
    }
    
    
}
