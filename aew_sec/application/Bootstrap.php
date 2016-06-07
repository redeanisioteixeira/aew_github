<?php  
//update 1234
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Coloca o config no registro
     *
     * @return void
     */
    protected function _initRegistry()
    {
        Zend_Registry::set('config', $this->getOptions());
    }

    /**
     * Registra as rotas do sistema
     */
    protected function _initRoute()
    {
        $this->bootstrap('FrontController');
		$front = $this->getResource('FrontController');
		$router = $front->getRouter();

        $router->addRoute("denunciar",
			new Zend_Controller_Router_Route("denunciar",
			    array(
			        "module" => "aew",
			    	"controller" => "home",
					"action" => "denunciar"
			    )
			)
        );
/*        
        $router->addRoute("faleconosco",
			new Zend_Controller_Router_Route("faleconosco",
			    array(
			        "module" => "aew",
			    	"controller" => "home",
					"action" => "faleconosco"
			    )
			)
        );
        $router->addRoute("login",
			new Zend_Controller_Router_Route("login",
			    array(
			        "module" => "aew",
			    	"controller" => "usuario",
					"action" => "login"
			    )
			)
        );
*/        
    }

    /**
     * Bootstrap the view configuration
     *
     * @return void
     */
    protected function _initView()
    {
        $this->bootstrap('FrontController');

		$options = $this->getOptions();
		if (isset($options['resources']['view'])) {
		    $view = new Zend_View($options['resources']['view']);
		} else {
		    $view = new Zend_View;
		}
		if (isset($options['resources']['view']['doctype'])) {
		    $view->doctype($options['resources']['view']['doctype']);
		}
		if (isset($options['resources']['view']['contentType'])) {
			$view->headMeta()->appendHttpEquiv('Content-Type',
			$options['resources']['view']['contentType']);
		}

        // set up doctype for any view helpers that use it
        $view->doctype('HTML5');

        // add helper path to View/Helper directory within this library
        $prefix = 'Sec_View_Helper';
        $dir = realpath(LIBRARY_PATH . '/Sec/View/Helper');
        $view->addHelperPath($dir, $prefix);

        $prefix = 'Aew_View_Helper';
        $dir = realpath(APPLICATION_PATH . '/view/helpers');

        $view->addHelperPath($dir, $prefix);

        // Habilita o uso do jQuery
        $view->addHelperPath(LIBRARY_PATH . "/ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
        $view->jQuery()
             ->setRenderMode(ZendX_JQuery::RENDER_SOURCES |
                             ZendX_JQuery::RENDER_STYLESHEETS |
						 	 ZendX_JQuery::RENDER_JAVASCRIPT |
						 	 ZendX_JQuery::RENDER_JQUERY_ON_LOAD);
		$view->jQuery()->enable();

		// Adiciona o uso de view helper a partir do view base
        $view->addBasePath(APPLICATION_PATH . '/view/');

        // setup initial head place holders
        

        $view->headTitle('Ambiente Educacional Web')
             ->setSeparator(' - ')
             ->setIndent(4);

		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
			'ViewRenderer'
		);

		$viewRenderer->setView($view);
		$viewRenderer->setViewSuffix('php');
		return $view;
    }

    /**
     * Configura o FrontController
     */
    protected function _initFrontControllerConfig()
    {
	$options = $this->getOptions();
	if (!isset($options['resources']['frontControllerConfig']['contentType'])) 
        {
	    return;
	}
	$this->bootstrap('FrontController');
	$front = $this->getResource('FrontController');
	$response = new Zend_Controller_Response_Http;
	$response->setHeader('Content-Type',
	$options['resources']['frontControllerConfig']['contentType'], true);
	$front->setResponse($response);
        // Mostra os erros da a plicação
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->throwExceptions(FALSE);
    }

    /**
     * Bootstrap the resources loader
     *
     * @return void
     */
    protected function _initResourceLoader()
    {
	$resourceLoader = new Zend_Application_Module_Autoloader(array('basePath'  => APPLICATION_PATH,	'namespace' => 'Aew',));
        Zend_Controller_Action_HelperBroker::addPath(LIBRARY_PATH . '/Sec/Controller/Action/Helper/', 'Sec_Controller_Action_Helper');
	Zend_Controller_Action_HelperBroker::addHelper(new Sec_Controller_Action_Helper_Acl());
	return $resourceLoader;
    }

    protected function _initDefaultPagination()
    {
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
	Zend_View_Helper_PaginationControl::setDefaultViewPartial('_componentes/_pagination_ajax.php');
    }

    protected function _initLogger()
    {
        $logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Firebug();
		$logger->addWriter($writer);
		Zend_Registry::set('logger',$logger);

		return $logger;
    }

    protected function _initLocale()
    {
	$locale = new Zend_Locale('pt_BR');
        
	Zend_Registry::set('Zend_Locale', $locale);
	date_default_timezone_set('America/Bahia');
	//date_default_timezone_set('Etc/GMT+3');

	return $locale;
    }

    protected function _initTranslate()
    {
        $this->bootstrap('Locale');
	$locale = $this->getResource('Locale');

        $translate = new Zend_Translate('array', APPLICATION_PATH . '/configs/lang/'. $locale .'.php', 'pt_BR');
	Zend_Validate_Abstract::setDefaultTranslator($translate);
        return $translate;
    }


    protected function setconstants($constants){
        foreach ($constants as $key=>$value)
        {
            if(!defined($key)){
                define($key, $value);
            }
        }
    }
}
