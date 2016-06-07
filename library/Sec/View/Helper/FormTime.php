<?php
class Sec_View_Helper_FormTime extends Zend_View_Helper_FormElement
{
    /**
     * Generates 'select' list for time.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The option value to mark as 'selected'; if an
     * array, will mark all values in the array as 'selected' (used for
     * multiple-select elements).
     *
     * @param array|string $attribs Attributes added to the 'select' tag.
     *
     * @param array $options An array of key-value pairs where the array
     * key is the radio value, and the array value is the radio text.
     *
     * @param string $listsep When disabled, use this list separator string
     * between list values.
     *
     * @return string The select tag and options XHTML.
     */
    public function formTime($name, $value = null, $attribs = null,
        $options = null, $saltoMinuto = 10, $listsep = "<br />\n")
    {
        $info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
        extract($info); // name, id, value, attribs, options, listsep, disable

        // check if element may have multiple values
        $multiple = '';

        // now start building the XHTML.
        $disabled = '';
        if (true === $disable) {
            $disabled = ' disabled="disabled"';
        }

        if(!$value instanceof Sec_Date){
            $value = new Sec_Date($value);
        }
        //$values = explode(':', $value);
        $valueHora = $value->get('HH');
        $valueMinuto = $value->get('mm');
        $valueMinuto -= $valueMinuto % 10;

        if (substr($name, -2) != '[]') {
            $name .= '[]';
        }

        // HORA
        // Build the surrounding select element first.
        $xhtml = '<select'
                . ' name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '_hora"'
                . $multiple
                . $disabled
                . $this->_htmlAttribs($attribs)
                . ">\n    ";

        $optionsHora = array('00' => '00',
                             '01' => '01',
                             '02' => '02',
                             '03' => '03',
                             '04' => '04',
                             '05' => '05',
                             '06' => '06',
                             '07' => '07',
                             '08' => '08',
                             '09' => '09',
                             '10' => '10',
                             '11' => '11',
                             '12' => '12',
                             '13' => '13',
                             '14' => '14',
                             '15' => '15',
                             '16' => '16',
                             '17' => '17',
                             '18' => '18',
                             '19' => '19',
                             '20' => '20',
                             '21' => '21',
                             '22' => '22',
                             '23' => '23'
        );

        // build the list of options
        $list       = array();
        foreach ((array) $optionsHora as $opt_value => $opt_label) {
            $list[] = $this->_build($opt_value, $opt_label, $valueHora, $disable);
        }

        // add the options to the xhtml and close the select
        $xhtml .= implode("\n    ", $list) . "\n</select>";


        // MINUTOS
        // Build the surrounding select element first.
        $xhtml .= ' : <select'
                . ' name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '_minuto"'
                . $multiple
                . $disabled
                . $this->_htmlAttribs($attribs)
                . ">\n    ";

        $optionsMinutos = array();

        for($minuto = 0; $minuto < 60; $minuto += $saltoMinuto){
            if($minuto < 10) {
                $repMinuto = '0'.$minuto;
            } else {
                $repMinuto = $minuto;
            }
            $optionsMinutos[$repMinuto] = $repMinuto;
        }

        // build the list of options
        $list       = array();
        foreach ((array) $optionsMinutos as $opt_value => $opt_label) {
            $list[] = $this->_build($opt_value, $opt_label, $valueMinuto, $disable);
        }

        // add the options to the xhtml and close the select
        $xhtml .= implode("\n    ", $list) . "\n</select>";

        return $xhtml;
    }

    /**
     * Builds the actual <option> tag
     *
     * @param string $value Options Value
     * @param string $label Options Label
     * @param array  $selected The option value(s) to mark as 'selected'
     * @param array|bool $disable Whether the select is disabled, or individual options are
     * @return string Option Tag XHTML
     */
    protected function _build($value, $label, $selected, $disable)
    {
        if (is_bool($disable)) {
            $disable = array();
        }

        $opt = '<option'
             . ' value="' . $this->view->escape($value) . '"'
             . ' label="' . $this->view->escape($label) . '"';

        // selected?
        if ($value == $selected) {
            $opt .= ' selected="selected"';
        }

        // disabled?
        if (in_array($value, $disable)) {
            $opt .= ' disabled="disabled"';
        }

        $opt .= '>' . $this->view->escape($label) . "</option>";

        return $opt;
    }
}
