<?php
/**
 * Action Helper respons�vel por verificar a permiss�o do usu�rio de acessar
 * um determinado recurso no sistema.
 *
 * @author Diego Potapczuk
 *
 */
class Sec_Controller_Action_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Controller_Action
     */
    protected $_action;

    /**
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     * @var Sec_Acl
     */
    protected $_acl;

    /**
     * @var string
     */
    protected $_controllerName;

    /**
     * @var string
     */
    protected $_moduleName;

    /**
     * Hook into action controller initialization
     *
     * @return void
     */
    public function init()
    {
        $this->_action = $this->getActionController();
        $this->_acl = Sec_Acl::getInstance();
        $this->_auth = Zend_Auth::getInstance();

        // add resource for this controller
        $controller = $this->_action->getRequest()->getControllerName();
        $module = $this->_action->getRequest()->getModuleName();
        $this->_controllerName = $controller;
        $this->_moduleName = $module;
        $resource = $module.":".$controller;
        if(!$this->_acl->has($resource)) {
            $this->_acl->add(new Zend_Acl_Resource($resource), $module);
        }
    }

    /**
     * Hook into action controller preDispatch() workflow
     *
     * @return void
     */
    public function preDispatch()
    {
    	$request = $this->_action->getRequest();

    	$controller = $request->getControllerName();
        $action = $request->getActionName();
        $module = $request->getModuleName();

        $role = Aew_Model_Bo_UsuarioTipo::VISITANTE;
        if ($this->_auth->hasIdentity()) {
            $user = $this->_auth->getIdentity();
            if($user->getUsuarioTipo()->getNome() != null) {
                $role = $user->getUsuarioTipo()->getNome();
            }
        }

        $resource = $module.":".$controller;
        $privilege = $action;

//		  Zend_Debug::dump($role, '$role');
//        Zend_Debug::dump($resource, '$resource');
//        Zend_Debug::dump($privilege, '$privilege');
//        Zend_Debug::dump($this->_acl->isAllowed($role, $resource, $privilege), 'isAllowed');
        if($module == "aew" || $controller == "error"){
        	return;
        }

        if (!$this->_acl->has($resource)) {
            $resource = null;
        }

        if (!$this->_acl->isAllowed($role, $resource, $privilege)) {
        	// Usuario nao logado
            if (!$this->_auth->hasIdentity()) {

            	$mensagem = Sec_Acl::ERROR_LOGADO;

            	$this->_action->getFlashMessennger()
            			      ->setNamespace('actionErrors')
            			      ->addMessage($mensagem);

            	$noAuth = $this->_acl->getNoAuthAction();

                $request->setModuleName($noAuth['module'])
					->setControllerName($noAuth['controller'])
					->setActionName($noAuth['action'])
					->setDispatched(false);
			// Usuario sem permissao
            } else {
            	$noAcl = $this->_acl->getNoAclAction();

                $request->setModuleName($noAcl['module'])
					->setControllerName($noAcl['controller'])
					->setActionName($noAcl['action'])
					->setDispatched(false);
            }
        }
    }

    /**
     * Proxy to the underlying Zend_Acl's allow()
     *
     * We use the controller's name as the resource and the
     * action name(s) as the privilege(s)
     *
     * @param  Zend_Acl_Role_Interface|string|array     $roles
     * @param  string|array                             $actions
     * @uses   Zend_Acl::setRule()
     * @return Places_Controller_Action_Helper_Acl Provides a fluent interface
     */
    public function allow($roles = null, $actions = null)
    {
        $controller = $this->_controllerName;
        $module = $this->_moduleName;
        $resource = $module.":".$controller;

        $this->_acl->allow($roles, $resource, $actions);
        return $this;
    }

    /**
     * Proxy to the underlying Zend_Acl's deny()
     *
     * We use the controller's name as the resource and the
     * action name(s) as the privilege(s)
     *
     * @param  Zend_Acl_Role_Interface|string|array     $roles
     * @param  string|array                             $actions
     * @uses   Zend_Acl::setRule()
     * @return Places_Controller_Action_Helper_Acl Provides a fluent interface
     */
    public function deny($roles = null, $actions = null)
    {
        $controller = $this->_controllerName;
        $module = $this->_moduleName;
        $resource = $module.":".$controller;

        $this->_acl->deny($roles, $resource, $actions);
        return $this;
    }
}
