<?php
/**
 * Sec_Form_Decorator_ViewHelpers
 *
 * Decorate an element by using various view helpers to render it.
 *
 * Accepts the following options:
 * - separator: string with which to separate passed in content and generated content
 * - placement: whether to append or prepend the generated content to the passed in content
 * - helpers:    the name of the view helper to use
 *
 * Assumes the view helper accepts three parameters, the name, value, and
 * optional attributes; these will be provided by the element.
 */
class Sec_Form_Decorator_ViewHelpers extends ZendX_JQuery_Form_Decorator_UiWidgetElement
{

    /**
     * Set view helper to use when rendering
     *
     * @param  string $helper
     * @return Zend_Form_Decorator_Element_ViewHelper
     */
    public function setHelper($helper)
    {
        $this->_helper = $helper;
        return $this;
    }

    /**
     * Render an element using a view helper
     *
     * Determine view helper from 'viewHelper' option, or, if none set, from
     * the element type. Then call as
     * helper($element->getName(), $element->getValue(), $element->getAttribs())
     *
     * @param  string $content
     * @return string
     * @throws Zend_Form_Decorator_Exception if element or view are not registered
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view = $element->getView();
        if (null === $view) {
            throw new Zend_Form_Decorator_Exception('UiWidgetElement decorator cannot render without a registered view object');
        }

        if(method_exists($element, 'getJQueryParams')) {
            $this->setJQueryParams($element->getJQueryParams());
        }
        $jQueryParams = $this->getJQueryParams();

        if (method_exists($element, 'getMultiOptions')) {
            $element->getMultiOptions();
        }

        $helpers    = $this->getHelper();
        $separator = $this->getSeparator();
        $value     = $this->getValue($element);
        $attribs   = $this->getElementAttribs();
        $name      = $element->getFullyQualifiedName();

        $id = $element->getId();
        $attribs['id'] = $id;

        if(!is_array($helpers)){
            $helpers = array($helpers);
        }

        $elementContent = '';

        foreach($helpers as $helper){
	        $helperObject  = $view->getHelper($helper);
	        if (method_exists($helperObject, 'setTranslator')) {
	            $helperObject->setTranslator($element->getTranslator());
	        }

	        $elementContent .= $view->$helper($name, $value, $jQueryParams, $attribs);

        }

        switch ($this->getPlacement()) {
            case self::APPEND:
                return $content . $separator . $elementContent;
            case self::PREPEND:
                return $elementContent . $separator . $content;
            default:
                return $elementContent;
        }
    }
}
