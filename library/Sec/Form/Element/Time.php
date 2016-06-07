<?php

class Sec_Form_Element_Time extends Zend_Form_Element_Xhtml
{
    /**
     * Use formTime view helper by default
     * @var string
     */
    public $helper = 'formTime';

    /**
     * Retrieve filtered element value
     *
     * @return mixed
     */
    public function getValue()
    {
        $value = parent::getValue();
        return  $value[0].':'.$value[1];
    }
}
