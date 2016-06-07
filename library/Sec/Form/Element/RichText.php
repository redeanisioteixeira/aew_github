<?php

class Sec_Form_Element_RichText extends Zend_Form_Element_Textarea
{
	/**
     * Use formTextarea view helper by default
     * @var string
     */
    public $helper = 'formRichText';

    /**
     * Retrieve filtered element value
     *
     * @return mixed
     */
    public function getValue()
    {
        $value = parent::getValue();
        return  stripslashes($value);
    }
}
