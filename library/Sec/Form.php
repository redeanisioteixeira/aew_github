<?php

/**
 * Sec Form
 *
 * @author diego
 *
 */
class Sec_Form extends ZendX_JQuery_Form {

    /**
     * Groups of buttons
     * @var array
     */
    protected $_buttons = array();

    /**
     * Groups of files
     * @var array
     */
    protected $_files = array();

    /**
     * Constructor
     *
     * @param  array|Zend_Config|null $options
     * @return void
     */
    public function __construct($options = null) {
        $this->addPrefixPath('Sec_Form_Element', 'Sec/Form/Element', 'element');
        $this->addPrefixPath('Sec_Form_Decorator', 'Sec/Form/Decorator', 'decorator');
        $this->addElementPrefixPath('Sec_Validate', 'Sec/Validate/', 'validate');
        parent::__construct($options);
    }

    /**
     * Set form action
     *
     * @param  string $action
     * @return Zend_Form
     */
    public function setAction($action) {
        return parent::setAction($this->getView()->baseUrl() . $action);
    }

    /**
     * Carrega os decorators padrões
     *
     * @return void
     */
    public function loadDefaultDecorators() {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        /* Decorators */
        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array('tag' => 'ul', 'class' => 'list-unstyled'))
                ->addDecorator('Form');

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'li', 'class' => 'form-group'))
        ));

        foreach ($this->getElements() as $element) {
            /* @var $element Zend_Form_Element */
            switch ($element->getType()) {
                case 'Zend_Form_Element_File':
                    $element->setDecorators(array(
                        array('File'),
                        array('Description'),
                        array('Label', array('separator' => ' ')),
                        array('HtmlTag', array('tag' => 'li', 'class' => 'form-group ')),
                        array('Errors')
                    ));
                    break;
                case 'Zend_Form_Element_Button':
                case 'Zend_Form_Element_Submit':
                    $element->setDecorators(array(
                        array('ViewHelper'),
                        array('Description'),
                        array('HtmlTag', array('tag' => 'li', 'class' => 'form-group'))
                    ));
                    break;
                case 'Sec_Form_Element_Time':
                    $element->setDecorators(array(
                        array('ViewHelper'),
                        array('Errors'),
                        array('Description'),
                        array('Label', array('separator' => ' ')),
                        array('HtmlTag', array('tag' => 'li', 'class' => 'elemento-hora'))
                            )
                    );
                    break;
                case 'Sec_Form_Element_DateTime':
                    $element->setDecorators(array(
                        array('ViewHelpers'),
                        array('Errors'),
                        array('Description'),
                        array('Label', array('separator' => ' ')),
                        array('HtmlTag', array('tag' => 'li', 'class'=>'form-group'))
                            )
                    );
                    break;
                case 'ZendX_JQuery_Form_Element_DatePicker':
                case 'Sec_Form_Element_Date':
                    $element->setDecorators(array(
                        array('UiWidgetElement'),
                        array('Errors'),
                        array('Description'),
                        array('Label', array('separator' => ' ')),
                        array('HtmlTag', array('tag' => 'li', 'class'=>'form-group'))
                            )
                    );
                    break;
                case 'Zend_Form_Element_Hidden':
                    $element->setDecorators(array(
                        array('ViewHelper')
                            )
                    );
                    break;
                case 'Zend_Form_Element_Checkbox':
                    $element->setDecorators(array(
                        array('ViewHelper'),
                        array('Errors'),
                        array('Description'),
                        array('Label', array('separator' => ' ')),
                        array('HtmlTag', array('tag' => 'li', 'class'=>'form-group'))
                            )
                    );
                    break;
                default:
                    $element->setDecorators(array(
                        array('ViewHelper'),
                        array('Errors'),
                        array('Description'),
                        array('Label', array('separator' => ' ')),
                        array('HtmlTag', array('tag' => 'li', 'class'=>'form-group'))
                            )
                    );
            }
        }

        foreach ($this->_buttons as $button) {
            $this->setButtonDecorators($button);
        }

        foreach ($this->_files as $file) {
            $this->setFileDecorators($file);
        }

        $this->configDecorators();
    }

    /**
     * Permite a configuração dos decorators no form
     */
    public function configDecorators() {
        
    }

    public function addButton($name) {
        $this->_buttons[] = $name;
    }

    public function addFile($name) {
        $this->_files[] = $name;
    }

    public function setFileDecorators($name) {
        $element = $this->getElement($name);

        $element->setDecorators(array(
            array('File'),
            array('Description'),
            array('HtmlTag', array('tag' => 'li', 'class' => 'form-group')),
            array('Errors')
        ));
    }

    public function setButtonDecorators($name) {
        $element = $this->getElement($name);

        // buttons do not need labels
        $element->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => 'li', 'class' => 'form-group')),
        ));
    }

}
