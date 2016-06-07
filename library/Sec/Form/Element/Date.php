<?php
class Sec_Form_Element_Date extends ZendX_JQuery_Form_Element_DatePicker
{
    /**
     * Set element value
     *
     * @param  mixed $value
     * @return Zend_Form_Element
     */
    public function setValue($value)
    {
        $value = Sec_Date::getPresentationDate($value);
        return parent::setValue($value);
    }
}