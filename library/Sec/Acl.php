<?php
/**
 * Sec Access Control List
 *
 * @author diego
 *
 */
class Sec_Acl extends Zend_Acl
{
    /**
     * Constantes de erro
     * @var string
     */
    const ERROR_LOGADO = 'Você deve estar logado para acessar esse recurso';

    private $_noAuth;
    private $_noAcl;
    private static $_instance;
    protected $_permissions = array();

    /**
     * Retorna uma instancia
     * @return Sec_Acl
     */
    public static function getInstance()
    {
    	if(null === self::$_instance){
    		self::$_instance = new Sec_Acl();
    	}
    	return self::$_instance;
    }

    protected function __construct()
    {
        $config = Zend_Registry::get('config');

        $roles = $config['acl']['roles'];
        $resources = $config['acl']['resources'];
        $permissions = $config['acl']['permissions'];

        $this->_addRoles($roles);
        $this->_addResources($resources);
        $this->_addPermissions($permissions);

        $this->_loadRedirectionActions();
    }

    public function setNoAuthAction($noAuth)
    {
        $this->_noAuth = $noAuth;
    }

    public function setNoAclAction($noAcl)
    {
        $this->_noAcl = $noAcl;
    }
    public function getNoAuthAction()
    {
        return $this->_noAuth;
    }

    public function getNoAclAction()
    {
        return $this->_noAcl;
    }

    /**
     * Adiciona os papéis do sistema
     * @param array $roles
     */
    protected function _addRoles($roles)
    {
        foreach ($roles as $name=>$parents) {
            if (!$this->hasRole($name)) {
                if (empty($parents)) {
                    $parents = null;
                } else {
                    $parents = explode(',', $parents);
                }
                $this->addRole(new Zend_Acl_Role($name), $parents);
            }
        }
    }

    /**
     * Adiciona os recursos do sistema
     * @param array $resources
     */
    protected function _addResources($resources)
    {
        foreach ($resources as $name=>$parents) {
            if (!$this->has($name)) {
                if (empty($parents)) {
                    $parents = null;
                } else {
                    $parents = explode(',', $parents);
                }
                $this->add(new Zend_Acl_Resource($name), $parents);
            }
        }
    }

    /**
     * Adiciona as permissoes do sistema
     * @param array $permissions
     */
    protected function _addPermissions($permissions)
    {
        foreach ($permissions as $resource => $permissions) {
            foreach($permissions as $permission => $role) {
                if ($this->has($resource)) {
                    $roles = explode(',', $role);
                    $this->allow($roles, $resource, $permission);
                    $this->_permissions[$resource][$permission] = $role;
                } else {
	                throw new Zend_Exception('Recurso '.$resource.' não encontrado');
                }
            }
        }
    }

    protected function _loadRedirectionActions()
    {
		// Logado
		$this->_noAuth = array('module' => 'aew', 'controller' => 'home', 'action' => 'home');

		// Privilégio
		$this->_noAcl = array('module' => 'aew', 'controller' => 'usuario','action' => 'acesso-negado');
    }
    
    /**
     * Returns an array of registered roles.
     *
     * Note that this method does not return instances of registered roles,
     * but only the role identifiers.
     *
     * @return array of registered roles
     */
    public function getRoles()
    {
        return array_keys($this->_getRoleRegistry()->getRoles());
    }

    /**
     * @return array of registered resources
     */
    public function getResources()
    {
        return array_keys($this->_resources);
    }
    
        /**
     * @return array of registered resources
     */
    public function getPermissions()
    {
        return $this->_permissions;
    }

}
