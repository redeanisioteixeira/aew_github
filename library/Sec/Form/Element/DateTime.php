<?php

class Sec_Form_Element_DateTime extends Zend_Form_Element_Xhtml
{
    /**
     * Use formTime view helper by default
     * @var string
     */
    public $helper = array('formDate', 'formTime');

    /**
     * Retrieve filtered element value
     *
     * @return mixed
     */
    public function getValue()
    {
        $value = parent::getValue();

        return $this->getView()->datetime($value);
    }

    /**
     * Set element value
     *
     * @param  mixed $value
     * @return Zend_Form_Element
     */
    public function setValue($value)
    {
        if(is_array($value)){
            $value = $value[0].' '.$value[1].':'.$value[2];
        }

        $value = new Sec_Date($value);

        return parent::setValue($value);
    }
}
