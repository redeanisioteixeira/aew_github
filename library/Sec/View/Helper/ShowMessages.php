<?php

class Sec_View_Helper_ShowMessages
{
    protected $view;

	/**
	 * @var Zend_Controller_Action_Helper_FlashMessenger
	 */
    protected $_flashMessenger;

    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * Mostra as mensagens de alerta e erro na sessão
     * @param $mensagens
     * @param $options
     */
    public function ShowMessages($mensagens = null, $options = null)
    {
        $result = '';

        if(false == isset($options['noMessages'])):
            $mensagens = $this->getMessages();

            if($mensagens):
                $result .= '<ul id="flash-mensagens" class="flash-mensagens alert alert-success list-unstyled margin-top-10" role="alert">';
                foreach($mensagens as $mensagem):
                    $result .= $this->view->partial('_componentes/_mensagens.php', array('mensagem' => $mensagem));
                endforeach;
                $result .= '</ul>';
            endif;
        endif;

        if(false == isset($options['noErrors'])):
            $erros = $this->getErrors();

            if($erros):
                $result .= '<ul id="flash-erros" class="flash-erros alert alert-danger list-unstyled margin-top-10"  role="alert">';
                foreach($erros as $erro):
                   $result .= $this->view->partial('_componentes/_erros.php', array('erro' => $this->view->escape($erro)));
                endforeach;
                $result .= '</ul>';
            endif;
        endif;

        return $result;
    }

    /**
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function getFlashMessennger()
    {
        if(null == $this->_flashMessenger){
            $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('flashMessenger');
        }
        return $this->_flashMessenger;
    }

    /**
     * Retorna um array de mensagens do namespace actionMessages
     *
     * @return array
     */
    public function getMessages(){
        $flash = $this->getFlashMessennger();
        $flash->setNamespace('actionMessages');
        $mensagens = array();
        $mensagens = array_merge($mensagens, $flash->getMessages());
        $mensagens = array_merge($mensagens, $flash->getCurrentMessages());
        $flash->clearCurrentMessages();

        return $mensagens;
    }

    /**
     * Retorna um array de erros do namespace actionErrors
     *
     * @return array
     */
    public function getErrors(){
        $flash = $this->getFlashMessennger();
        $flash->setNamespace('actionErrors');
        $mensagens = array();
        $mensagens = array_merge($mensagens, $flash->getMessages());
        $mensagens = array_merge($mensagens, $flash->getCurrentMessages());
        $flash->clearCurrentMessages();

        return $mensagens;
    }
}
