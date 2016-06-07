<?php
class Sec_View_Helper_FormRichText extends Zend_View_Helper_FormElement
{
    /**
     * The default number of rows for a textarea.
     *
     * @access public
     *
     * @var int
     */
    public $rows = 30;

    /**
     * The default number of columns for a textarea.
     *
     * @access public
     *
     * @var int
     */
    public $cols = 20;

    /**
     * Generates a 'textarea' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formRichText($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // is it disabled?
        $disabled = '';
        if ($disable) {
            // disabled.
            $disabled = ' disabled="disabled"';
        }

        if(!empty($attribs['class'])){
            $attribs['class'] = $attribs['class'] . ' richText';
        } else {
            $attribs['class'] = 'richText';
        }

        // Make sure that there are 'rows' and 'cols' values
        // as required by the spec.  noted by Orjan Persson.
        if (empty($attribs['rows'])) {
            $attribs['rows'] = (int) $this->rows;
        }
        if (empty($attribs['cols'])) {
            $attribs['cols'] = (int) $this->cols;
        }

        $this->view->headLink()->appendStylesheet('/assets/plugins/summernote/css/custom.css')
                ->appendStylesheet('/assets/plugins/summernote/css/summernote-emoji.css');
        
        $this->view->headScript()
                ->appendFile('/assets/plugins/summernote/js/summernote.js')
                ->appendFile('/assets/plugins/summernote/js/summernote-emoji.js')
                ->appendFile('/assets/plugins/summernote/locale/summernote-pt-BR.js')
                ->appendFile('/assets/plugins/summernote/js/custom-summernote.js');
        
        $xhtml = '<textarea name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs) . '>'
                . $this->view->escape($value) . '</textarea>';

        return $xhtml;
    }
}