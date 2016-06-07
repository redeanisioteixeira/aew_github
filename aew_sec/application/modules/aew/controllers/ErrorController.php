<?php
/**
 * Gerencia paginas de erros do modulo principal
 */
class ErrorController extends Sec_Controller_Action 
{
    /**
     * @return Zend_View
     */
    public function errorAction() 
    {
        $this->setPageTitle('Ops, aconteceu um pequeno erro');
	$errors = $this->_getParam('error_handler');
	switch ($errors->type) 
        {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER :
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION :
            //case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:    
            // 404 error -- controller or action not found
            $this->getResponse()->setHttpResponseCode(404);
            $this->view->message = 'Página não encontrada';
            break;
            default :  	// application error
            $this->getResponse()->setHttpResponseCode(500);
            $this->view->message = 'Ocorreu um erro na aplicação';
            break;
        }
	$this->view->exception = $errors->exception;
	$this->view->request = $errors->request;
    }

    /**
     * @return Zend_View
     */
    public function csrfAction() 
    {
        $this->view->message = 'Erro de validação de formulário';
	$this->render('error');
    }
    /**
     *  view atrelada a usuario/accesso-negado    
     * @return Zend_View
     */
    public function deniedAction()
    {
        //$this->_helper->ViewRenderer->setNoRender(true);
        //$this->_helper->layout->disableLayout();
        $this->view->message = 'Usuário sem autorização para este recurso do sistema'; 
        
    }
}