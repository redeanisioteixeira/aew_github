<?php
class Sec_View_Helper_FormDate extends ZendX_JQuery_View_Helper_DatePicker
{
	/**
	 * Create a jQuery UI Widget Date Picker
	 *
	 * @link   http://docs.jquery.com/UI/Datepicker
	 * @param  string $id
	 * @param  string $value
	 * @param  array  $params jQuery Widget Parameters
	 * @param  array  $attribs HTML Element Attributes
	 * @return string
	 */
    public function formDate($id, $value = null, array $params = array(), array $attribs = array())
    {
        $value = $this->view->date($value);

        $attribs['maxlength'] = 10;
        $attribs['size'] = 10;

        return $this->datePicker($id, $value, $params, $attribs);
    }
}